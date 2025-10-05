const { Dropzone } = require("dropzone");
Dropzone.autoDiscover = false;
require('jquery-repeater-form');


$(document).ready(function () {
    const taskController = new TaskControls();
});

class TaskControls {
    constructor() {
        this._initSingleImageUpload();
        this._initGalleries();
        this._initLocation();
    }

    /**
     *
     * @private
     */
    _initLocation() {
        let _this = this;
        $('.js-repeater').repeater({
            // (Optional)
            // Removes the delete button from the first list item,
            // defaults to false.
            isFirstItemUndeletable: true,
            show: function () {
                let $this = $(this);
                $this.attr('data-repeater-item', '');
                let btnDelete = $this.find('.js-location-delete');
                if (btnDelete.length) {
                    btnDelete.attr('data-repeater-delete', '');
                    btnDelete.attr('data-location-id', '');
                    btnDelete.removeClass('js-location-delete');//always set bottom of condition
                }
                $this.slideDown();
            },
        });

        $('.js-location-delete').on('click', function () {
            _this._deleteLocationItem($(this));
        });
    }

    /**
     *
     * @param $this jquery element
     * @private
     */
    _deleteLocationItem($this) {
        let locationId = $this.attr('data-location-id');
        let eFrom = $this.closest('form');
        $this.closest('div[data-repeater-item=' + locationId + ']').remove();

        eFrom.append('<input type="hidden" name="location_delete[]" value="'+ locationId +'">');
        return true;
    }

    // Single Image Upload initialization
    _initSingleImageUpload() {
        this._singleImageUploadExample = document.getElementById('taskImgCover');
        const singleImageUpload = new SingleImageUpload(
            this._singleImageUploadExample);
    }

    _initGalleries() {
        new Dropzone('#taskGallery', {
            autoProcessQueue: false,
            dictDefaultMessage: "Drop your files here!",
            url: 'https://httpbin.org/pos',
            init: function () {
                let myDropzone = this;
                document.querySelector("button[type=submit]").addEventListener("click", function(e) {
                    // Make sure that the form isn't actually being sent.
                   /* e.preventDefault();
                    e.stopPropagation();*/

                    //document.querySelector('input[name="gallery"]').files = myDropzone.getQueuedFiles();

                   /* let pendingFiles = myDropzone.getQueuedFiles();
                    pendingFiles.forEach($file => {
                        const reader  = new FileReader();
                        reader.readAsDataURL($('input[name="gallery"]').prop("files"));
                       console.log($file);
                    });*/

                    //console.log(myDropzone.getQueuedFiles());
                    //console.log(myDropzone.getAcceptedFiles());
                    //myDropzone.processQueue();
                });
                /*this.on('success', function (file, responseText) {
                    console.log(responseText);
                });*/
            },
            acceptedFiles: 'image/*',
            thumbnailWidth: 160,
            previewTemplate: DropzoneTemplates.previewTemplate,
        });
    }
}
