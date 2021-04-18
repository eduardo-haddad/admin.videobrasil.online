var currentRequest = null

$(document).ready(() => {
    $(document).on('change', 'textarea.cep', (e) => {
        getReachEstimate(e)
    })
})

function getReachEstimate(e){
    var data = {
        'custom_token': sessionStorage.getItem('user_access_token'),
        'cep' : $(e.target).parents('.adset-targeting').find('.cep').val(),
        'age_min' : $(e.target).parents('.adset-targeting').find('.age-min').val(),
        'age_max' : $(e.target).parents('.adset-targeting').find('.age-max').val(),
        'interest' : $(e.target).parents('.adset-targeting').find('.interest').val(),
        'life_events' : $(e.target).parents('.adset-targeting').find('.life_events').val(),
        'behaviors' : $(e.target).parents('.adset-targeting').find('.behaviors').val(),
        'exclude_interest' : $(e.target).parents('.adset-targeting').find('.exclude.interest').val(),
        'exclude_life_events' : $(e.target).parents('.adset-targeting').find('.exclude.life-events').val(),
        'exclude_behaviors' : $(e.target).parents('.adset-targeting').find('.exclude.behaviors').val()
    }

    $(e.target).parents('div.tab-pane.active').find('.reach-estimate > .panel-body').html('<i class="fa fa-spinner fa-spin" style="font-size: 20px;"></i><br><strong> Carregando dados')

    currentRequest = $.ajax({
        url: BASE_URL + '/campaigns/facebook/reachestimate',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend : function()    {           
            if(currentRequest !== null) {
                currentRequest.abort();
            }
        },
        data: $.param(data),
        success: (data) => {
            $('.reach-estimate').removeClass('d-none');
            $(e.target).parents('div.tab-pane.active').find('.reach-estimate > .panel-body').html('Esse AdSet pode atingir <b>'+data.users.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')+'</b> usuários')
        },
        error: (error) => {
          console.log(error)
        },
    })
}