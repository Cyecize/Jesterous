var notificationIcon = document.getElementById('notificationsBtn');
var notificationIconMoile = document.getElementById('mobileNotificationsBtn');

var NotificationModalManager = (function () {
    var notificationBar = document.getElementById('hidden-noti-bar');

    //private methods
    function hasClickedOnParentChild(children, event) {
        var isCl = false;
        for (var i = 0; i < children.length; i++) {
            if (children[i] == event.target)
                isCl = true;
        }
        return isCl;
    }

    function fillNotificationBarWithData(htmlData) {
        var a = $(notificationBar).text().trim();
        var b = $(htmlData).text().trim();
        if (a.localeCompare(b) !== 0)
            $(notificationBar).html(htmlData);
        //attachDynamicEvents();
    }

    //public methods
    function removeNotification(id) {
        $.ajax({
            type: "POST",
            url: "/notifications/remove/" + id,
            data: {token: CSRF_TOKEN},
            success: fillNotificationBarWithData,
            error: console.error
        });
    }

    function viewNotification(id) {
        $.ajax({
            type: "POST",
            url: "/notifications/view/" + id,
            data: {token: CSRF_TOKEN},
            success: fillNotificationBarWithData,
            error: console.error
        });
    }

    function showOrHideForm() {
        if (window.innerWidth < 991) {
            location.href = "/notifications/mobile";
            return;
        }
        if ($(notificationBar).css('display') == "none")
            $(notificationBar).show(100);
        else
            $(notificationBar).hide(100);
    }

    function onDocumentClick(event) {
        if (notificationBar == null) return;
        if (event.target !== notificationBar && event.target !== notificationIcon && !hasClickedOnParentChild(notificationBar.getElementsByTagName('*'), event))
            $(notificationBar).hide(100);
    }

    function update(interval) {
        $.ajax({
            type: "POST",
            url: "/notifications/request",
            data: {token: CSRF_TOKEN},
            success: fillNotificationBarWithData,
            error: function () {
                if (interval !== undefined)
                    clearInterval(interval);
            }
        });
    }

    function removeAllNotifications() {
        $.ajax({
            type: "POST",
            url: "/notifications/remove-all",
            data: {token: CSRF_TOKEN},
            success: function (data) {
                fillNotificationBarWithData(data);
            },
            error: console.error
        });
    }

    return {
        showOrHideForm: showOrHideForm,
        onDocumentClick: onDocumentClick,
        update: update,
        removeAllNotifications: removeAllNotifications,
        removeNotification: removeNotification,
        viewNotification: viewNotification,
    };
})();

$(notificationIcon).on('click', NotificationModalManager.showOrHideForm);
$(notificationIconMoile).on('click', function (e) {
    notificationIcon.click();
});
document.addEventListener('click', NotificationModalManager.onDocumentClick);
NotificationModalManager.update();
var clock = setInterval(function (args) {
    NotificationModalManager.update(clock);
}, 4000);
