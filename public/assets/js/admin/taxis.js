
$(document).ready(function () {
    
    $(".btnDelete").on({
        click: function (e) {
            e.preventDefault();
            var link = $(this).attr("href");
            msg.confirm("Atención", "Confirma eliminar este taxi?", "Si, Eliminar", function(){
                window.location = link;
            });
        }
    });
});