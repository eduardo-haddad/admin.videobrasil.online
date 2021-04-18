var txt_desafio = $('#txt-desafio'),
    hiddenDiv_desafio = $(document.createElement('div')),
    content_desafio = null;

txt_desafio.addClass('txtstuff-desafio');
hiddenDiv_desafio.addClass('hiddendiv-desafio common-desafio');

$('body').append(hiddenDiv_desafio);

txt_desafio.on('keyup', function () {

    content_desafio = $(this).val();

    content_desafio = content_desafio.replace(/\n/g, '<br>');
    hiddenDiv_desafio.html(content_desafio + '<br class="lbr">');

    $(this).css('height', hiddenDiv_desafio.height());

});

var txt_contato = $('#txt-contato'),
    hiddenDiv_contato = $(document.createElement('div')),
    content_contato = null;

txt_contato.addClass('txtstuff-contato');
hiddenDiv_contato.addClass('hiddendiv-contato common-contato');

$('body').append(hiddenDiv_contato);

txt_contato.on('keyup', function () {

    content_contato = $(this).val();

    content_contato = content_contato.replace(/\n/g, '<br>');
    hiddenDiv_contato.html(content_contato + '<br class="lbr">');

    $(this).css('height', hiddenDiv_contato.height());

});

