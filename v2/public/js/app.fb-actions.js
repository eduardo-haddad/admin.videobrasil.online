$(document).ready(() => {
    
    $('.bulk-action').on('click', '#pause', function(e){
        e.preventDefault();
        rows = [];
        var checked = $(this).parents('.bulk-action').find('table')[0].getChecked();
        
        if(checked.length){
            checked.forEach(function(value){
                rows.push(document.getElementById(value));
            });

            rows.forEach((value, key) => {
                var checkbox = $(value).find('.status')
                if (!$(checkbox).is(':checked')) {
                    $(checkbox).attr('checked', 'checked');
                    $(checkbox).attr('value', 'ACTIVE');
                    rows[key] = {'element': value, 'status': 'ACTIVE'}
                } else {
                    $(checkbox).removeAttr('checked');
                    $(checkbox).attr('value', 'PAUSED');
                    rows[key] = {'element': value, 'status': 'PAUSED'}
                }

                $.ajax({
                    method: "POST",
                    url: $(rows[key].element).find('form').attr('action'),
                    data: $(rows[key].element).find('form').serialize()+'&status='+rows[key].status
                })
                .success(( response ) => {
                    notify('Campanha Atualizda', 'Status alterado', 'success')
                })
                .error((error)=>{
                    console.log(error)
                    notify('Erro', error.responseText, 'error')
                })
            })
        }
    });

    $('.bulk-action').on('click', '#clone', function(e){
        e.preventDefault();
        rows = [];
        var checked = $(this).parents('.bulk-action').find('table')[0].getChecked();
        var prop;

        $('form[name=bulk]').attr('data-action', 'copy')
        $('#actions-modal').find('.clone-fields').not('.hidden').remove();

        if(checked.length){
            checked.forEach(function(value){
                rows.push(document.getElementById(value));
            });

            rows.forEach((value, key) => {
                prop = $(value).attr('id').split('-')
                var newElement = $('#actions-modal').find('.clone-fields.hidden').clone()

                newElement.removeClass('hidden')
                newElement.addClass('mt-15')
                newElement.find('input, select').removeAttr('disabled')
                newElement.find('input[name='+prop[0]+'_id]').val(prop[1])
                newElement.find('input[name='+prop[0]+'_name]').val($(value).find('.'+prop[0]+'-name').text())
                newElement.find('label.title').text((key+1))

                if(prop[0] == 'adset') {
                    selectCampaigns(newElement.find('select[name=campaign_id]'));
                }

                $('#actions-modal').find('.btn').before(newElement)
            })

            $('#actions-modal').find('.modal-title').text('Clonar '+prop[0]);
            $('#actions-modal').find('.btn').val('Clonar '+prop[0]);

            $('#actions-modal').modal('show')
            $('form[name=bulk]').attr('data-type', prop[0])

        }
    });

    $('.bulk-action').on('click', '#edit', function(e){
        e.preventDefault();
        rows = [];
        var checked = $(this).parents('.bulk-action').find('table')[0].getChecked();
        var prop

        $('form[name=bulk]').attr('data-action', 'edit')
        $('#actions-modal').find('.clone-fields').not('.hidden').remove();
        
        if(checked.length){
            checked.forEach(function(value){
                rows.push(document.getElementById(value));
            });

            rows.forEach((value, key) => {
                prop = $(value).attr('id').split('-')
                if(prop[0] == 'adset') {
                    window.open(BASE_URL+'/campaigns/facebook/adset/'+prop[1]+'/edit', '_blank');
                }
                var newElement = $('#actions-modal').find('.clone-fields.hidden').clone()

                newElement.removeClass('hidden')
                newElement.addClass('mt-15')
                newElement.find('input, select').removeAttr('disabled')
                newElement.find('input[name='+prop[0]+'_id]').val(prop[1])
                newElement.find('input[name='+prop[0]+'_name]').val($(value).find('.'+prop[0]+'-name').text())
                newElement.find('label.title').text((key+1))

                $('#actions-modal').find('.btn').before(newElement)
            })

            if(prop[0] == 'adset') return false;

            $('#actions-modal').find('.modal-title').text(prop[0]+' Campanha');
            $('#actions-modal').find('.btn').val('Atualizar '+prop[0]);
            $('#actions-modal').modal('show')

            $('form[name=bulk]').attr('data-type', prop[0])
        }
    });

    $('.bulk-action').on('click', '#add', function(e){
        e.preventDefault();
        rows = [];
        var checked = $(this).parents('.bulk-action').find('table')[0].getChecked()
        var prop;
        
        if(checked.length){
            checked.forEach(function(value){
                rows.push(document.getElementById(value));
            });

            rows.forEach((value, key) => {
                prop = $(value).attr('id').split('-')
                var name = $(value).find('.'+prop[0]+'-name').text(); 
                if(prop[0] == 'adset') {
                    window.open(BASE_URL+'/campaigns/facebook/adset/'+prop[1]+'/ad/create?adset_name='+name, '_blank');
                }else if(prop[0] == 'campaign'){
                    window.open(BASE_URL+'/campaigns/facebook/'+prop[1]+'/edit?name='+name, '_blank');
                }
            })
        }
    });

    $('.bulk-action').on('click', '#delete', function(e){
        $('#modal-delete').modal('show')
    });

    $('form[name=bulk]').on('submit', (e) => {
        e.preventDefault()

        var fields = $(e.target).serializeArray();
        var data = [];
        var action = $(e.target).attr('data-action')
        var type = $(e.target).attr('data-type')

        for (let index = 0; index <= $(e.target).find('input[name='+type+'_id]').not('[disabled]').length-1; index++) {
            data[index] = []
            fields.forEach((value, i) => {
                data[index][value.name]
                if(data[index][value.name] == undefined && value.name !== '_token') {
                    data[index][value.name] = value.value
                    delete fields[i]
                }
                
            })

            var sendData = fields[0].name+'='+fields[0].value+
                        '&'+type+'_id='+data[index][type+'_id']+
                        '&name='+data[index][type+'_name']+
                        '&status='+data[index][type+'_status']
                        +(type == 'adset' ? '&campaign_id='+data[index]['campaign_id'] : '')
            
            var url = BASE_URL+'/campaigns/facebook/'+(type == 'adset' ? 'adset/' : '')+data[index][type+'_id']+'/'+action

            $.ajax({
                method: "POST",
                url: url,
                data: sendData
            })
            .success(( response ) => {
                notify('Sucesso', response, 'success')
            })
            .error((error)=>{
                console.log(error)
                notify('Erro', error.responseText, 'error')
            })
        }
    })

    $('form[name=delete]').on('submit', (e) => {
        e.preventDefault();
        var checked = $('table')[0].getChecked()
        var prop;
        var rows = []
        
        if(checked.length){
            checked.forEach(function(value){
                rows.push(document.getElementById(value));
            });

            rows.forEach((value, key) => {
                prop = $(value).attr('id').split('-')
                var name = $(value).find('.'+prop[0]+'-name').text();

                if(prop[0] == 'campaign') {
                    var url = BASE_URL+'/campaigns/facebook/'+prop[1]+'/delete'
                }else{
                    var url = BASE_URL+'/campaigns/facebook/'+prop[0]+'/'+prop[1]+'/delete'
                }

                $.ajax({
                    method: "POST",
                    url: url,
                    data: '',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                })
                .success(( response ) => {
                    notify('Sucesso', response, 'success')
                    $(value).remove()
                })
                .error((error)=>{
                    console.log(error)
                    notify('Erro', error.responseText, 'error')
                })
            })
        }
    })
    
    $('input.status').change((e) => {
        if ($(e.target).is(':checked')) {
            $(e.target).attr('value', 'ACTIVE');
        } else {
            $(e.target).attr('value', 'PAUSED');
        }
        
        $.ajax({
            method: "POST",
            url: $(e.target).parents('form[name=campaings]').attr('action'),
            data: $(e.target).parents('form[name=campaings]').serialize()+'&status='+$(e.target).val()
        })
        .success(( response ) => {
            notify('Campanha Atualizada', 'Status alterado', 'success')
        })
        .error((error)=>{
            console.log(error)
            notify('Erro', error.responseText, 'error')
        })
        
    })
})