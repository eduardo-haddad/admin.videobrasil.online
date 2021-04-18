/*
|--------------------------------------------------------------------------
| Add and remove tabs
|--------------------------------------------------------------------------
*/
var cloneElement;

$(document).ready(() => {
    cloneElement = $('.tab-pane > div').first().clone();
})

$(".nav-tabs").on("click", "a", function (e) {
    e.preventDefault();
    if (!$(this).hasClass('add-tab')) {
        $(this).tab('show');
    }
})
.on("click", "span", function () {
    var anchor = $($(this).parent('a'));
    $(anchor.attr('href')).remove();
    $(this).parent().remove();
    $(".nav-tabs li").children('a').first().click();
});

$('.add-tab').click(function (e) {
    e.preventDefault();
    var id = $(".nav-tabs").children().length;
    var tabId = 'tab_' + id;
    $(this).parent('li').before('<li><a href="#tab_' + id + '">Adset '+id+' <span><i class="fa fa-times-circle"></i></span></a></li>');
    $('.tab-content').append('<div class="tab-pane" id="' + tabId + '"></div>');
    $('.nav-tabs li:nth-child(' + id + ') a').click();

    //elements to remove
    $(cloneElement).find('.image_picker_selector').remove()
    $('iframe').remove()

    $.each(cloneElement.find('input, select, textarea'), (i, element) => {
        // element.value = null
        element.name = element.name.replace("[0]", "["+(id-1)+"]");

        if($(element).hasClass('mask-double')) {
            new Cleave(element, {
                numeral: true,
                numeralDecimalMark: ',',
                delimiter: '.',
                prefix: 'R$ '
              });
              
              // Add decimals on blur event
              $(element).on('blur', function(e){
                if(this.value != 'R$ ' && this.value.indexOf(',') === -1){
                  this.value = this.value + ',00';
                }
              });
        }
    })

    $.each(cloneElement.find('.collapse'), (i, element) => {
        $(element).attr('id', 'collapse'+($('div.collapse').length+i))
    })

    $.each(cloneElement.find('[data-toggle=collapse]'), (i, element) => {
        $(element).attr('href', '#collapse'+($('div.collapse').length+i))
    })

    cloneElement.appendTo('#'+tabId)
});