
$(document).ready(function () {
    App.init();
    $('#password').passwordStrength();
    iniUploadFile();

    $("#editUserForm").on({
        submit: function (e) {
            var password = $("#editUserForm").find("input[name=password]");
            if (password.val() !== "********") {
                if (!confirm("Confirma cambiar el password?")) {
                    e.preventDefault();
                }
            }
        }
    });
    
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

    fileDropzone.url = "/taxistas";
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