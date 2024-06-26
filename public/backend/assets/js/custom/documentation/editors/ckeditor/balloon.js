"use strict";

// Class definition
var KTFormsCKEditorBalloon = function () {
    // Private functions
    var exampleBalloon = function () {
        BalloonEditor
            .create(document.querySelector('#kt_docs_ckeditor_balloon'))
            .then(editor => {
            })
            .catch(error => {
            });
    }

    return {
        // Public Functions
        init: function () {
            exampleBalloon();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTFormsCKEditorBalloon.init();
});
