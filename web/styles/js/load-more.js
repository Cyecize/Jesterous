var LoadMoreManager = (function (targetUrl, loadMoreBtnId, holderId, onDataLoadedHandle) {

    var baseUrl;
    var size;
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
        size = 6;
        button = $(document.getElementById(btnId));
        holder = $(document.getElementById(holderId));
        if (onDataLoadedHandle != null) {
            dataLoadedHandler = onDataLoadedHandle;
        }
        initEvents();
    }

    function initEvents() {
        button.on('click', function (e) {
            loadMore();
        });
    }

    function setUrl(mainUrl, pageSize) {
        url = mainUrl + "?size=" + pageSize + "&page=";
    }

    //public methods
    function setPage(p) {
        page = p;
    }

    function setSize(s) {
        size = s;
        setUrl(baseUrl, size);
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
    baseUrl = targetUrl;
    setUrl(baseUrl, size);

    return {
        setPage: setPage,
        loadMore: loadMore,
        showButton: showButton,
        loadMasonry: loadMasonry,
        holder: holder,
        setSize: setSize
    };
});