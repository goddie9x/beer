$(document).ready(function() {
    $(window).scroll(function(e) {
        let scrollTop = $(window).scrollTop();
        if (scrollTop > 200) {
            $('.header').addClass('fixed-top');
        } else {
            $('.header').removeClass('fixed-top');
        }
    });
});