$(function(){

    var edit = $("#btn-edit-table01"),
        save = $("#btn-save-table01"),
        vadd_row = $("#btn-add-row"),
        vadd_column = $("#btn-add-column"),
        delete_row = $("#btn-delete-row"),
        delete_column = $("#btn-delete-column"),
        titulo = $("#input-edit-table01"),
        check = $(".holder-table01 .switch"),
        table = $("#hs-table01");

    function edit_table(){
        edit.fadeOut(500);
        save.fadeIn(500).css("display","inline-block");
        vadd_row.fadeIn(500).css("display","inline-block");
        vadd_column.fadeIn(500).css("display","inline-block");
        delete_row.fadeIn(500).css("display","inline-block");
        delete_column.fadeIn(500).css("display","inline-block");
        check.fadeIn(500).css("display","inline-block");
        $("#table01 .input").removeClass("disabled");
        titulo.removeClass("disabled");
        $("#table01").removeClass("disabled");

        var ghostdiv = $("#ghostdiv");
        $("#table01 td .input").keypress( function(){
            var ME = $(this);
            var px = 14;
            var txtlength = ME.val().length;
            $(this).css({width: txtlength * px });
        });

        var ghostdiv = $("#ghostdiv_th");
        $("#table01 tr.titulo .input").keypress( function(){
            var ME = $(this);
            var px = 16;
            var txtlength = ME.val().length;
            $(this).css({width: txtlength * px });
        });
    };

    function add_row() {
        $("#table01 tr:last").after("<tr></tr>");

        let count = 0;
        let input_class = '';

        $("#table01 tr:nth-child(1) td").each (function (){
            if(count === 0){
                input_class = 'input new-item';
            } else {
                input_class = input_class + ' new-column';
            }
            $("#table01 tr:last").append(`<td><input type='text' value='Coluna 1' placeholder='Coluna 1' class='${input_class}' size='8'></td>`)
            count++;
        });
    }

    function add_column() {
        $("#table01 tr").append("<td><input type='text' value='Coluna 1' placeholder='Coluna 1' class='input new-item new-column' size='8'></td>");
    }

    function erase_row() {
        if($("#table01 tr").length > 1){
            $("#table01 tr:last-child").remove();
        }
    }

    function erase_column() {
        $("#table01").find("tr").each(function(){
            if($(this).find("td").length > 1){
                $(this).find("td:last-child").remove();
            }
        });
    }

    function save_table() {
        var titInput = $("#input-edit-table01"),
            valuetitInput = titInput.val(),
            sizetitInput  = valuetitInput.length;

        titInput.attr('size', sizetitInput);
        titInput.css('width', 'auto');

        edit.fadeIn(500).css("display","inline-block");
        save.fadeOut(500);
        vadd_row.fadeOut(500);
        vadd_column.fadeOut(500);
        delete_row.fadeOut(500);
        delete_column.fadeOut(500);
        check.fadeOut(500);
        $("#table01 .input").addClass("disabled");
        titulo.addClass("disabled");

        if (table.is(":checked")) {
            $("#table01").addClass("active");
            $("#table01").removeClass("disabled");
        } else {
            $("#table01").addClass("disabled");
            $("#table01").removeClass("active");
        }
    }    

    $("#btn-edit-table01").on("click", edit_table);
    $("#btn-add-row").on("click", add_row);
    $("#btn-add-column").on("click", add_column);
    $("#btn-delete-row").on("click", erase_row);
    $("#btn-delete-column").on("click", erase_column);
    $("#btn-save-table01").on("click", save_table);
});