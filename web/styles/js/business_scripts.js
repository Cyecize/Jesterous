/*
* Table of content
*  1-> like a quote
*
*
 */

$(function () {

    likeAQuote();

    /**
     * like a quote
     */
    function likeAQuote() {
        $('.quote-like-btn').on('click', function () {
            var id = $(this).attr('quote-id').trim();
            if (isNaN(id)) return;

            var btn = $(this);
            var token = btn.attr('token');

            $.ajax({
                type: "POST",
                url: "/quotes/" + id + "/like",
                data: {token: token},
                success: function (data) {
                    if (data[0]['success']) {
                        var incr = 1;
                        btn.addClass('active');
                        if (data[0]['disliked']) {
                            incr = -1;
                            btn.removeClass('active');
                        }
                        var likeSection = btn.parent().find('.likes-count');
                        likeSection.text(Number(likeSection.text()) + incr);
                    }
                }
            });
        });
    }
    /* !like a quote */


});