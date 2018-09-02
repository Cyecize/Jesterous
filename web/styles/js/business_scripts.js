/*
* Table of content
*  1-> like a quote
*
*  2-> removing comment
*
 */

$(function () {

    likeAQuote();
    initRemoveComment();

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

    /* removing comment */
    function initRemoveComment() {
        $('.remove-comment-btn').on('click', function (e) {
            e.preventDefault();
            var token = $(this).attr('token');
            var commentId = $(this).attr('target_comment_id');
            var articleId = $(this).attr('target_article_id');

            $.ajax({
                type: "POST",
                url: "/comments/remove/" + commentId,
                data: {token:token, articleId:articleId},
                success:function (data) {
                    console.log(data);
                    location.reload();
                },
                error:console.error
            });
        });
    }
    /* !removing comment */


});