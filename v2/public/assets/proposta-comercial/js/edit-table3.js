$(function(){

    var edit = $("#btn-edit-table03"),
        save = $("#btn-save-table03"),
        vadd_row = $("#btn-add-row03"),
        vadd_column = $("#btn-add-column03"),
        delete_row = $("#btn-delete-row03"),
        delete_column = $("#btn-delete-column03"),
        switch_discount = $("#btn-switch-discount03"),
        discount = true,
        titulo = $("#input-edit-table03"),
        check = $(".holder-table03 .switch"),
        table = $("#hs-table03"),
        colunas = $("#table03").find('tr')[0].cells.length;

    function edit_table(){
        edit.fadeOut(500);
        save.fadeIn(500).css("display","inline-block");
        switch_discount.fadeIn(500).css("display","inline-block");
        vadd_row.fadeIn(500).css("display","inline-block");
        vadd_column.fadeIn(500).css("display","inline-block");
        delete_row.fadeIn(500).css("display","inline-block");
        //delete_column.fadeIn(500).css("display","inline-block");
        check.fadeIn(500).css("display","inline-block");
        $("#table03 .input").removeClass("disabled");
        titulo.removeClass("disabled");
        $("#table03").removeClass("disabled");

        var ghostdiv = $("#ghostdiv3");
        $("#table03 td .input").keypress( function(){
            var ME = $(this);
            var px = 14;
            var txtlength = ME.val().length;
            $(this).css({width: txtlength * px });
        });

        var ghostdiv = $("#ghostdiv3_th");
        $("#table03 tr.titulo .input").keypress( function(){
            var ME = $(this);
            var px = 16;
            var txtlength = ME.val().length;
            $(this).css({width: txtlength * px });
        });
    };

    function add_row() {

        $("#table03 tr.total:first").before("<tr class='tabline'></tr>");

        for(var i = 0; i < colunas; i++){
            $("#table03 tr.tabline:last")
                .append("<td><input type='text' value='Coluna 1' placeholder='Coluna 1' class='input new-item new-column new-row' size='8'></td>");

            if(i === 0){
                $("#table03 tr.tabline:last td input.new-row").css({'text-align':'left'});
            }
        }
    }
    
    function erase_row() {
        if($("#table03 tr.tabline").length > 0) {
            $("#table03 tr.tabline:last").remove();
        }
    }

    function add_column() {
        colunas++;
        $("#table03 tr.tabline, #table03 tr.titulo").append("<td><input type='text' value='Coluna 1' placeholder='Coluna 1' class='input new-item new-column' size='8'></td>");
        var value = $("#table03 tr.total td:first-child").prop("colspan");
        value = isNaN(value) ? 0 : value;
        value++;
        $("#table03 tr.total td:first-child").attr("colspan", value);
        delete_column.fadeIn(50).css("display","inline-block");
        console.log(colunas);
    }
    
    function erase_column() {
        colunas--;
        $("#table03").find("tr.tabline, tr.titulo").each(function(){
            $(this).find("td:last-child").remove();
        });
        var value = $("#table03 tr.total td:first-child").prop("colspan");
        value = isNaN(value) ? 0 : value;
        value < 1 ? value = 1 : '';
        value--;
        $("#table03 tr.total td:first-child").attr("colspan", value);

        if (colunas <= '2'){
            delete_column.fadeOut(50);
            console.log(colunas);
        }else{
            delete_column.fadeIn(50).css("display","inline-block");
            console.log('not' + colunas);
        }
    }

    switch_discount.on('click', function(){
        if(discount){
            $(this).css({'background-color':'#f0a534'});
        } else {
            $(this).css({'background-color':'#515f66'});
        }
        $('#discount03').toggle(function(){
            discount = !discount;
        });
    });

    function save_table() {
        var titInput = $("#input-edit-table03"),
            valuetitInput = titInput.val(),
            sizetitInput  = valuetitInput.length;

        titInput.attr('size', sizetitInput);
        titInput.css('width', 'auto');

        edit.fadeIn(500).css("display","inline-block");
        save.fadeOut(500);
        switch_discount.fadeOut(500);
        vadd_row.fadeOut(500);
        vadd_column.fadeOut(500);
        delete_row.fadeOut(500);
        delete_column.fadeOut(500);
        check.fadeOut(500);
        $("#table03 .input").addClass("disabled");
        titulo.addClass("disabled");

        if (table.is(":checked")) {
            $("#table03").addClass("active");
            $("#table03").removeClass("disabled");
        } else {
            $("#table03").addClass("disabled");
            $("#table03").removeClass("active");
        }
    }    

    $("#btn-edit-table03").on("click", edit_table);
    $("#btn-add-row03").on("click", add_row);
    $("#btn-add-column03").on("click", add_column);
    $("#btn-delete-row03").on("click", erase_row);
    $("#btn-delete-column03").on("click", erase_column);
    $("#btn-save-table03").on("click", save_table);
});