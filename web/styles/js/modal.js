var ModalManager = (function (id, openBtnId) {

    var modal;

    var openBtn;

    function initModal(id, openBtnId) {
        modal = document.getElementById(id);
        openBtn = document.getElementById(openBtnId);
        initEvents(modal);
    }

    function showModal() {
        modal.style.display = "block";
    }

    function hideModal() {
        modal.style.display = "none";
    }

    //private
    function initEvents(thisModal) {
        $(openBtn).on('click', function () {
            showModal();
        });
        $('.close-modal').on('click', function () {
            hideModal();
        });

        window.addEventListener('click', function (event) {
            if (event.target == thisModal) {
                hideModal();
            }
        });

        window.onkeypress = function (event) {
            if(event.keyCode == 27)
                hideModal();
        }
    }

    //constructor
    initModal(id, openBtnId);

    return {initModal: initModal, showModal: showModal, hideModal: hideModal};

});