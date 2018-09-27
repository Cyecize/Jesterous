var LoadMoreManager = (function (targetUrl, loadMoreBtnId, holderId, onDataLoadedHandle) {

    var page;
    var url;
    var button;
    var holder;
    var dataLoadedHandler = function (data) {
        holder.append(data);
    };

    //private methord
    function init(btnId, holderId, onDataLoadedHandle) {
        page = 1;
        button = $(document.getElementById(btnId));
        holder = $(document.getElementById(holderId));
        if(onDataLoadedHandle != null) {
            dataLoadedHandler = onDataLoadedHandle;
        }
        initEvents();
    }

    function initEvents() {
        button.on('click', function (e) {
            loadMore();
        });
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
            success: dataLoadedHandler,
            error: console.error
        });
    }

    //constructor
    init(loadMoreBtnId, holderId, onDataLoadedHandle);
    url = targetUrl + "?page=";

    return {setPage: setPage, loadMore: loadMore, showButton: showButton, loadMasonry: loadMasonry, holder:holder};
});