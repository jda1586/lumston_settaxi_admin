
$(document).ready(function () {
    App.init();
    iniUploadFile();
    Notification.init();
    TableManageDefault.init();
    
});

Dropzone.autoDiscover = false;
function iniUploadFile() {
    var fileDropzone = new Dropzone("#editForm");
    var theForm = $("#editForm");
    var photoPreview = theForm.find(".foto");

    photoPreview.on({
        click: function (e) {
            e.preventDefault();
            theForm.trigger("click");
        }
    });

    fileDropzone.url = "/propietarios";
    fileDropzone.autoQueue = false;
    fileDropzone.method = "post";

    fileDropzone.on("addedfile", function (file) {
        fileDropzone.processFile(file);
    });
    fileDropzone.on("dragover", function (file) {
        theForm.addClass("fileOver");
    });
    fileDropzone.on("dragleave", function (file) {
        theForm.removeClass("fileOver");
    });
    fileDropzone.on("dragend", function (file) {
        theForm.removeClass("fileOver");
    });
    fileDropzone.on("drop", function (file) {
        theForm.removeClass("fileOver");
    });

    fileDropzone.on("uploadprogress", function (progress) {

    });
    fileDropzone.on("success", function (progress, resp) {
        if (resp.result === 'ok') {
            photoPreview.css("background-image", "url(" + resp.file + ")");
        }
    });
}