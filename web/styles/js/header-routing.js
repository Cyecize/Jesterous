$(function () {
    var links = $('.main_nav').find('a');

    links.each(function (number, link) {
        link = $(link);

        if (link.attr('href') === CURRENT_URL) {
            link.parent().addClass('active');
        }
    })
});