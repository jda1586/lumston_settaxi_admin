
$(document).ready(function () {
    App.init();
    TableManageDefault.init();

    $('.selectpicker').selectpicker('render');

    $('#data-table2').DataTable({
        responsive: true,
        order: [[0, "desc"]],
        pageLength: 15
    });

    $("#select_id_sitio").on({
        change: function (e) {
            $("#btnSaveSitio").removeClass("disabled");
        }
    });
    $("#select_id_propietario").on({
        change: function (e) {
            $("#btnSavePropietario").removeClass("disabled");
        }
    });

});