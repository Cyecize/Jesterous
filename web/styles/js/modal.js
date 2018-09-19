var modalCloseEvent = new Event('modalClosed');
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

    function isModalOpen() {
        return modal.style.display == "block";
    }

    //private
    function initEvents(thisModal) {
        $(openBtn).on('click', function () {
            showModal();
        });
        $('.close-modal').on('click', function () {
            hideModal();
            this.dispatchEvent(modalCloseEvent);
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

    return {initModal: initModal, showModal: showModal, hideModal: hideModal, isModalOpen:isModalOpen};

});