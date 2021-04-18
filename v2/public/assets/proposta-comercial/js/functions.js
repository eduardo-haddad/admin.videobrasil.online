$( document ).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /// Edit Banner - Nome Empresa
    $("#btn-edit-company").click(function(){
        var input = $("#input-edit-company"),
            edit = $("#btn-edit-company"),
            save = $("#btn-save-company"),
            cancel = $("#btn-cancel-company");

        input.removeClass("disabled");
        input.select();
        edit.fadeOut(500);
        save.fadeIn(500).css("display","inline-block");
        cancel.fadeIn(500).css("display","inline-block");

        $("#btn-save-company").click(function (){
            var value = input.val(),
                size  = value.length + 2,
                company = input.val()
    
            input.attr('size', size);
            input.addClass("disabled");
            edit.fadeIn(500).css("display","inline-block");
            save.fadeOut(500);
            cancel.fadeOut(500);
            $('#nome-empresa').text(company);
        });
    });

    /// Edit Nome Contato
    $("#btn-edit-contact").click(function(){
        var input = $("#input-edit-contact"),
            edit = $("#btn-edit-contact"),
            save = $("#btn-save-contact"),
            txt_contato = $('#txt-contato');
        
        input.removeClass("disabled");
        txt_contato.removeClass("disabled");
        input.select();
        edit.fadeOut(500);
        save.fadeIn(500).css("display","inline-block");

        $("#btn-save-contact").click(function (){
            var value = input.val(),
                size  = value.length + 1;
    
            input.attr('size', size);
            input.addClass("disabled");
            txt_contato.addClass("disabled");
            edit.fadeIn(500).css("display","inline-block");
            save.fadeOut(500);
    
        });

    });

    /// Edit O Desafio
    $("#btn-edit-challenge").click(function(){
        var input = $("#input-edit-challenge"),
            edit = $("#btn-edit-challenge"),
            save = $("#btn-save-challenge"),
            txt_desafio = $('#txt-desafio');
        
        input.removeClass("disabled");
        txt_desafio.removeClass("disabled");
        input.select();
        edit.fadeOut(500);
        save.fadeIn(500).css("display","inline-block");

        $("#btn-save-challenge").click(function (){
            var value = input.val(),
                size  = value.length + 1,
                company = input.val();
    
            input.attr('size', size);
            input.addClass("disabled");
            txt_desafio.addClass("disabled");
            edit.fadeIn(500).css("display","inline-block");
            save.fadeOut(500);
    
        });

    });

    /// Edit Plano de Ação
    $("#btn-edit-plan").click(function(){
        var input = $("#input-edit-plan"),
            edit = $("#btn-edit-plan"),
            save = $("#btn-save-plan"),
            itens = $(".itens-plano"),
            check = $("#plano .switch"),
            item01 = $("#item01"),
            item02 = $("#item02"),
            item03 = $("#item03"),
            item04 = $("#item04"),
            item05 = $("#item05"),
            item06 = $("#item06");
        
        input.removeClass("disabled");
        itens.removeClass("disabled");
        itens.find("li").addClass("active");
        check.fadeIn(500);
        input.select();
        edit.fadeOut(500);
        save.fadeIn(500).css("display","inline-block");

        $("#btn-save-plan").click(function (){
            var value = input.val(),
                size  = value.length + 1;

            if (item01.is(":checked")) {
                item01.parent().parent("li").addClass("active");
                item01.parent().parent("li").removeClass("disabled");
            } else {
                item01.parent().parent("li").removeClass("active");
                item01.parent().parent("li").addClass("disabled");
            }

            if (item02.is(":checked")) {
                item02.parent().parent("li").addClass("active");
                item02.parent().parent("li").removeClass("disabled");
            } else {
                item02.parent().parent("li").removeClass("active");
                item02.parent().parent("li").addClass("disabled");
            }

            if (item03.is(":checked")) {
                item03.parent().parent("li").addClass("active");
                item03.parent().parent("li").removeClass("disabled");
            } else {
                item03.parent().parent("li").removeClass("active");
                item03.parent().parent("li").addClass("disabled");
            }

            if (item04.is(":checked")) {
                item04.parent().parent("li").addClass("active");
                item04.parent().parent("li").removeClass("disabled");
            } else {
                item04.parent().parent("li").removeClass("active");
                item04.parent().parent("li").addClass("disabled");
            }

            if (item05.is(":checked")) {
                item05.parent().parent("li").addClass("active");
                item05.parent().parent("li").removeClass("disabled");
            } else {
                item05.parent().parent("li").removeClass("active");
                item05.parent().parent("li").addClass("disabled");
            }

            if (item06.is(":checked")) {
                item06.parent().parent("li").addClass("active");
                item06.parent().parent("li").removeClass("disabled");
            } else {
                item06.parent().parent("li").removeClass("active");
                item06.parent().parent("li").addClass("disabled");
            }
    
            input.attr("size", size);
            input.addClass("disabled");
            itens.addClass("disabled");
            edit.fadeIn(500).css("display","inline-block");
            check.fadeOut(500);
            save.fadeOut(500);
    
        });
    
    });

    /// Edit Validade
    $("#btn-edit-validity").click(function(){
        var input = $("#input-edit-validity"),
            edit = $("#btn-edit-validity"),
            save = $("#btn-save-validity");
        
        input.removeClass("disabled");
        input.select();
        edit.fadeOut(500);
        save.fadeIn(500).css("display","inline-block");

        $("#btn-save-validity").click(function (){
            var value = input.val();
                size  = value.length + 3;
    
            input.attr('size', size);
    
            input.addClass("disabled");
            edit.fadeIn(500).css("display","inline-block");
            save.fadeOut(500);
    
        });

    });

    /// Edit Vendedor
    $("#btn-edit-seller").click(function(){
        var input_nome = $("#input-edit-seller-name"),
            input_role = $("#input-edit-seller-role"),
            input_email = $("#input-edit-seller-email"),
            input_tel = $("#input-edit-seller-tel"),
            edit = $("#btn-edit-seller"),
            save = $("#btn-save-seller"),
            upload = $("#btn-upload");

            //$('#file-upload').hide();
            $('#btn-upload').on('click', function () {
                $('#file-upload').click();
            });

            $('#file-upload').change(function () {
                var file = this.files[0];
                var reader = new FileReader();
                reader.onloadend = function () {
                    $('#seller-photo').css('background-image', 'url("' + reader.result + '")');
                }
                if (file) {
                    reader.readAsDataURL(file);
                } else {
                }
            });
        
        input_nome.removeClass("disabled");
        input_role.removeClass("disabled");
        input_email.removeClass("disabled");
        input_tel.removeClass("disabled");
        edit.fadeOut(500);
        upload.fadeIn(500);
        save.fadeIn(500).css("display","inline-block");

        $("#btn-save-seller").click(function (){
            var value_nome = input_nome.val(),
                value_role = input_role.val();
                value_email = input_email.val();
                value_tel = input_tel.val();
                size_nome  = value_nome.length + 3,
                size_role  = value_role.length + 3,
                size_email  = value_email.length + 3,
                size_tel  = value_tel.length + 3;
    
            input_nome.attr('size', size_nome);
            input_role.attr('size', size_role);
            input_email.attr('size', size_email);
            input_tel.attr('size', size_tel);
            input_nome.addClass("disabled");
            input_role.addClass("disabled");
            input_email.addClass("disabled");
            input_tel.addClass("disabled");
            edit.fadeIn(500).css("display","inline-block");
            upload.fadeOut(500);
            save.fadeOut(500);
    
        });
    });

    /// Finalizar
    $("#finalizar").click(function(){

        $(".buttons").fadeOut(250);
        $("#download").fadeOut(250);
        $(".table.disabled").parent().fadeOut(250);

        // jquery-ajax-native plugin: https://github.com/acigna/jquery-ajax-native
        // $.ajax({
        //     method: "POST",
        //     dataType: 'native',
        //     url: "/proposta/pdf/",
        //     data: {
        //         html: $("html").html()
        //     },
        //     xhrFields: {
        //         responseType: 'blob'
        //     },
        //     success: function(blob){
        //         var link=document.createElement('a');
        //         link.href=window.URL.createObjectURL(blob);
        //         link.download="proposta.pdf";
        //         link.click();
        //     }
        // });
    });

    // Voltar a editar
    $("#editable").click(function(){
        $(".buttons").fadeIn(250);
        $("#download").fadeIn(250);

        $(".table.disabled").parent().fadeIn(250);
    });

    function getPageHTML() {
        return "<html>" + $("html").html() + "</html>";
    }

});
