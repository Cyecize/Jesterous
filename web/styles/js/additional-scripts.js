/*
    Table of Content

    1 -> Go Top Btn
 */

$(function () {

    backToTop();

    /*
        2 BACK TO TOP
     */
    function backToTop() {

        var btn = $('<button id="back-to-top"  class="btn btn-primary back-to-top ">');

        btn.append($('<i class="fa fa-chevron-up">'));
        $('body').append(btn);

        configureBtnVisibility();


        $(window).scroll(function () {
            configureBtnVisibility();
        });
        // scroll body to 0px on click
        btn.click(function () {
            $('#back-to-top').tooltip('hide');
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });

        //btn.tooltip('show');

        function configureBtnVisibility() {
            if ($(document).scrollTop() > 50) {
                btn.fadeIn();
                btn.removeClass('d-none');
            } else {
                btn.fadeOut();
            }
        }
    }

    /*
    end BACK TO TOP
     */


});