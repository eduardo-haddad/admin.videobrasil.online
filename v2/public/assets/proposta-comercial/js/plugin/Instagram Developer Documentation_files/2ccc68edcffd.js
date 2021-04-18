//  Image Fallback

function imageFallback(el) {
    var fallbackURL = "//instagram-static.s3.amazonaws.com/bluebar/images/default-avatar.png";

    if(el.parentNode.className.indexOf("img-") > -1 && el.parentNode.tagName.toLowerCase() == 'span')
    {
        el.parentNode.setAttribute("style", el.parentNode.getAttribute("style").split(el.src).join(fallbackURL));
        el.src = fallbackURL;
    }
}

function openDropdown(e) {
    $(e.target)
        .parents('.has-dropdown')
        .toggleClass("dropdown-open")
        .children('a').toggleClass("link-active");
}

$(document).ready(function() {
    $(".has-dropdown > a").live('click', openDropdown);
    var $html = $('html');
    if ($.browser.msie) {
        $html.addClass('msie');
    } else if ($.browser.opera) {
        $html.addClass('opera');
    }
});
