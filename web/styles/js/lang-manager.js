$(function () {

    var COOKIE_LANG_NAME = "lang";

    var currentLang = getCookie(COOKIE_LANG_NAME);
    var inputs = $('input[name="' + COOKIE_LANG_NAME + '"]');

    inputs.each(function (ind, el) {
        if (el.value === currentLang)
            el.checked = true;
    });

    inputs.on('click', function (e) {
        var lng = e.target.value;
        document.cookie = "lang=" + lng + ';path=/;';
        location.reload();
    });


    /**
     * Simple function that gets a cookie by name
     * @param cname
     * @returns {string}
     */
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

});

