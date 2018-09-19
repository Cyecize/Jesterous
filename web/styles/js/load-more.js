var LoadMoreManager = (function (targetUrl, loadMoreBtnId, holderId) {

    var page;
    var url;
    var button;
    var holder;

    //private methord
    function init(btnId, holderId) {
        page = 1;
        button = $(document.getElementById(btnId));
        holder = $(document.getElementById(holderId));
        initEvents();
    }

    function initEvents() {
        button.on('click', function (e) {
            loadMore();
        })
    }

    //public methods
    function setPage(p) {
        page = p;
    }

    function showButton() {
        button.show();
    }
    
    function loadMasonry() {
        holder.imagesLoaded(function () {
            holder.masonry('reloadItems');
            holder.masonry();
        });

    }

    function loadMore() {
        button.hide();
        $.ajax({
            method: "GET",
            url: url + ++page,
            success: function (data) {
                holder.append(data);
            },
            error: console.error
        });
    }

    //constructor
    init(loadMoreBtnId, holderId);
    url = targetUrl + "?page=";

    return {setPage: setPage, loadMore: loadMore, showButton: showButton, loadMasonry: loadMasonry};
});