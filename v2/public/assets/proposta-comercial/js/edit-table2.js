$(function(){

    var edit = $("#btn-edit-table02"),
        save = $("#btn-save-table02"),
        vadd_row = $("#btn-add-row02"),
        vadd_column = $("#btn-add-column02"),
        delete_row = $("#btn-delete-row02"),
        delete_column = $("#btn-delete-column02"),
        switch_discount = $("#btn-switch-discount02"),
        discount = true,
        titulo = $("#input-edit-table02"),
        check = $(".holder-table02 .switch"),
        table = $("#hs-table02"),
        colunas = $("#table02").find('tr')[0].cells.length;

    function edit_table(){
        edit.fadeOut(500);
        save.fadeIn(500).css("display","inline-block");
        vadd_row.fadeIn(500).css("display","inline-block");
        switch_discount.fadeIn(500).css("display","inline-block");
        vadd_column.fadeIn(500).css("display","inline-block");
        delete_row.fadeIn(500).css("display","inline-block");
        //delete_column.fadeIn(500).css("display","inline-block");
        check.fadeIn(500).css("display","inline-block");
        $("#table02 .input").removeClass("disabled");
        titulo.removeClass("disabled");
        $("#table02").removeClass("disabled");

        var ghostdiv = $("#ghostdiv2");
        $("#table02 td .input").keypress( function(){
            var ME = $(this);
            var px = 14;
            var txtlength = ME.val().length;
            $(this).css({width: txtlength * px });
        });

        var ghostdiv = $("#ghostdiv2_th");
        $("#table02 tr.titulo .input").keypress( function(){
            var ME = $(this);
            var px = 16;
            var txtlength = ME.val().length;
            $(this).css({width: txtlength * px });
        });
    };

    function add_row() {
        $("#table02 tr.total:first").before("<tr class='tabline'></tr>");

        for(var i = 0; i < colunas; i++){
            $("#table02 tr.tabline:last")
                .append("<td><input type='text' value='Coluna 1' placeholder='Coluna 1' class='input new-item new-column new-row' size='8'></td>");

            if(i === 0){
                $("#table02 tr.tabline:last td input.new-row").css({'text-align':'left'});
            }

        }

    }
    
    function erase_row() {
        if($("#table02 tr.tabline").length > 0){
            $("#table02 tr.tabline:last").remove();
        }
    }

    function add_column() {

        colunas++;
        $("#table02 tr.tabline, #table02 tr.titulo").append("<td><input type='text' value='Coluna 1' placeholder='Coluna 1' class='input new-item new-column' size='8'></td>");
        var value = $("#table02 tr.total td:first-child").prop("colspan");
        value = isNaN(value) ? 0 : value;
        value++;
        $("#table02 tr.total td:first-child").attr("colspan", value);
        delete_column.fadeIn(50).css("display","inline-block");
        // console.log(colunas);

    }
    
    function erase_column() {
        colunas--;
        $("#table02").find("tr.tabline, tr.titulo").each(function(){
            $(this).find("td:last-child").remove();
        });
        var value = $("#table02 tr.total td:first-child").prop("colspan");
        value = isNaN(value) ? 0 : value;
        value < 1 ? value = 1 : '';
        value--;
        $("#table02 tr.total td:first-child").attr("colspan", value);

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
        $('#discount02').toggle(function(){
            discount = !discount;
        });
    });

    function save_table() {
        var titInput = $("#input-edit-table02"),
            valuetitInput = titInput.val(),
            sizetitInput  = valuetitInput.length;

        titInput.attr('size', sizetitInput);
        titInput.css('width', 'auto');

        edit.fadeIn(500).css("display","inline-block");
        save.fadeOut(500);
        vadd_row.fadeOut(500);
        vadd_column.fadeOut(500);
        switch_discount.fadeOut(500);
        delete_row.fadeOut(500);
        delete_column.fadeOut(500);
        check.fadeOut(500);
        $("#table02 .input").addClass("disabled");
        titulo.addClass("disabled");

        if (table.is(":checked")) {
            $("#table02").addClass("active");
            $("#table02").removeClass("disabled");
        } else {
            $("#table02").addClass("disabled");
            $("#table02").removeClass("active");
        }
    }    

    $("#btn-edit-table02").on("click", edit_table);
    $("#btn-add-row02").on("click", add_row);
    $("#btn-add-column02").on("click", add_column);
    $("#btn-delete-row02").on("click", erase_row);
    $("#btn-delete-column02").on("click", erase_column);
    $("#btn-save-table02").on("click", save_table);
});