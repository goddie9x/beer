$(document).ready(function() {
    $(window).scroll(function(e) {
        let scrollTop = $(window).scrollTop();
        if (scrollTop > 200) {
            $('.header').addClass('fixed-top');
        }
        if (scrollTop < 150) {
            $('.header').removeClass('fixed-top');
        }
    });
    $('.nav-link').removeClass('active');
    const url = window.location.href;
    const urlWithoutLastSlash = url.substring(0, url.length - 1);
    const nav_itemsWithoutSlash = $('a[href="' + urlWithoutLastSlash + '"]');
    const nav_items = $('a[href="' + url + '"]');
    nav_itemsWithoutSlash.addClass('active');
    nav_itemsWithoutSlash.parent('.nav-link').addClass('active');
    nav_items.addClass('active');
    nav_items.parent('.nav-link').addClass('active');
});