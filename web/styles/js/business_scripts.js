/*
* Table of content
*  1-> like a quote
*
*  2-> removing comment
*
*  3 -> image preview
*
*  4 -> follow and unfollow
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
            var token = $(this).attr('token').trim();

            var btn = $(this);
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
                data: {token: token, articleId: articleId},
                success: function (data) {
                    console.log(data);
                    location.reload();
                },
                error: console.error
            });
        });
    }

    /* !removing comment */

});

/* 3 image preview*/
var ImagePreviewManager = (function () {

    var imgPrev = null;

    function attachEvent(inputId, imgSrcId) {
        imgPrev = $(document.getElementById(imgSrcId));
        document.getElementById(inputId).onchange = function (event) {
            console.log("image selected!")
            readUrl(this);
        };
    }

    function readUrl(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                imgPrev.attr('src', e.target['result']);
            };
            reader.readAsDataURL(input.files[0]);
            imgPrev.show();
        }
    }

    return {attachEvent: attachEvent};
})();
/* !3 image preview*/


/*Follow unfollow */
$(function () {

    $('.btn-follow').on('click', function (e) {
        followOrUnfollow(true, this);
    });

    $('.btn-unfollow').on('click', function (e) {
        followOrUnfollow(false, this);
    });

    function followOrUnfollow(isFollow, btn) {
        var celebId = $(btn).attr('celebId');
        var url = isFollow ? "/unfollow/" + celebId : "/follow/" + celebId;
        $.ajax({
            type: "POST",
            url: url,
            success: function (data) {
              location.reload();
            },
            error: function (data) {
                alert(data['responseJSON']['message']);
            }
        });
    }
});
/*!Follow unfollow */