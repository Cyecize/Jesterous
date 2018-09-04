var QuillManager = (function () {
    var quill = null;

    function initQuill(divId) {


        quill = new Quill(divId, { //format '#editor-container'
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                    ['blockquote', 'code-block'],

                    [{'header': 1}, {'header': 2}],               // custom button values
                    [{'list': 'ordered'}, {'list': 'bullet'}],
                    [{'script': 'sub'}, {'script': 'super'}],      // superscript/subscript
                    [{'indent': '-1'}, {'indent': '+1'}],          // outdent/indent
                    [{'direction': 'rtl'}],                         // text direction

                    [{'size': ['small', false, 'large', 'huge']}],  // custom dropdown
                    [{'header': [1, 2, 3, 4, 5, 6, false]}],
                    ['link', 'image', 'video', 'formula'],
                    [{'color': []}, {'background': []}],          // dropdown with defaults from theme
                    [{'font': []}],
                    [{'align': []}],

                    ['clean']
                ],
                imageResize: true,
            },
            placeholder: 'The journey of thousand miles starts with a single step...',
            theme: 'snow'
        });
    }

    function getQuillContent() {
        if (quill != null)
            return quill.root.innerHTML;
        return "";
    }

    return {initQuill: initQuill, getQuillContent: getQuillContent};
});