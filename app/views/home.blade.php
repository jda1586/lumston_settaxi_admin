@section('css')
<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="/assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />	
<link href="/assets/plugins/parsley/src/parsley.css" rel="stylesheet" />
<link href="/assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" />
<link href="/assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
<link href="/assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
<link href="/assets/plugins/bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<link href="/assets/plugins/parsley/src/parsley.css" rel="stylesheet" />
<link href="/assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />	
<link href="/assets/plugins/parsley/src/parsley.css" rel="stylesheet" />


<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
<link href="assets/plugins/ionRangeSlider/css/ion.rangeSlider.css" rel="stylesheet" />
<link href="assets/plugins/ionRangeSlider/css/ion.rangeSlider.skinNice.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" />
<link href="assets/plugins/password-indicator/css/password-indicator.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-combobox/css/bootstrap-combobox.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" />
<link href="assets/plugins/jquery-tag-it/css/jquery.tagit.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" rel="stylesheet" />
<link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
<link href="assets/plugins/bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
<link href="/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
<!-- ================== END PAGE LEVEL STYLE ================== -->
<!-- ================== END PAGE LEVEL STYLE ================== -->
@stop

@section('content')

<div id="content" class="content">
    <div class="row">
        <div class="col-md-12">
            <a href="#" onclick="$('#btnSubmit').trigger('click');" class="btn btn-sm btn-inverse pull-left m-l-0"><i class="fa fa-check m-r-5"></i> Generar reporte</a>
        </div>
    </div>

    <!-- begin row -->
    <div class="row m-t-5">
        <div class="col-md-12">
            <div class="panel panel-inverse" data-sortable-id="form-plugins-4">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                    </div>
                    <h4 class="panel-title">Datepicker</h4>
                </div>
                <div class="panel-body panel-form">
                    <form novalidate="" class="form-horizontal form-bordered" method="post" action="/viajes" data-parsley-validate="true">
                        <button type="submit" id="btnSubmit" class="btn btn-primary hidden">-</button>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Rango de fechas</label>
                            <div class="col-md-8">
                                <div class="input-group input-daterange">
                                    <input value="{{ $start }}" type="text" class="form-control" name="start" placeholder="Fecha desde" data-type="string" data-parsley-required="true" type="text"  data-parsley-range="[2,50]" />
                                    <span class="input-group-addon"> hasta </span>
                                    <input value="{{ $end }}" type="text" class="form-control" name="end" placeholder="Fecha Final"  data-type="string" data-parsley-required="true" type="text"  data-parsley-range="[2,50]" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group no-border">
                            <label class="control-label col-md-4 col-sm-4">Sitio:</label>
                            <div class="col-md-4 col-sm-4">
                                <select name="id_sitio" class="form-control selectpicker" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-default">
                                    <option value="0" selected>Todos</option>
                                    @foreach ($sitios as $sitio)
                                    <option value="{{ $sitio->id }}" <?= ($id_sitio == $sitio->id) ? "selected" : "" ?>>{{ $sitio->numero }} - {{ $sitio->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <div class="row m-t-5">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                    <h4 class="panel-title">
                        Viajes
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="data-table_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <table aria-describedby="data-table_info" role="grid" id="data-table" class="table table-striped table-bordered dataTable no-footer dtr-inline">
                                    <thead>
                                        <tr role="row">
                                            <th aria-label="" style="width: 50px;" aria-controls="data-table" tabindex="0" class="sorting sorting_desc" >ID</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Taxista</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Taxi</th>
                                            <th aria-label="" style="width: 80px;" aria-controls="data-table" tabindex="0" class="sorting">Propiet.</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Distancia / Tiempo</th>
                                            <th aria-label="" style="width: 70px;" aria-controls="data-table" tabindex="0" class="sorting">Costo</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Taxi Libre</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Error?</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Email</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Fecha Registro</th>
                                            <th aria-sort="descending" aria-label="Status" style="width: 90px;" aria-controls="data-table" tabindex="0" class="sorting">Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($viajes as $viaje)
                                        {
                                            $label = "default";
                                            switch ($viaje->status)
                                            {
                                                case "iniciado":
                                                    $label = "info";
                                                    break;
                                                case "finalizado":
                                                    $label = "success";
                                                    break;
                                                case "cancelado":
                                                    $label = "danger";
                                                    break;
                                            }
                                            $link = "/viajes/" . $viaje->id;
                                            $labelError = "";
                                            
                                            $costo = ($viaje->status == "cotizado" || $viaje->status == "iniciado") ? $viaje->costo_estimado : ceil($viaje->costo_real);
                                            
                                            if($viaje->status == "iniciado")
                                            {
                                                //$costo
                                            }

                                            if ($viaje->status == "finalizado")
                                            {
                                                $curRate = Rates::getRates($viaje->created_at);
                                                $dayRate = ($curRate["tarifa"] == "Día") ? true : false;
                                                $tiempoReal = ($viaje->taxi_libre) ? $viaje->tiempo_real : $viaje->tiempo_estimado;
                                                $costoReal = Rates::getRateEstimation($tiempoReal, $viaje->distancia_real, $dayRate);

                                                if (abs($viaje->costo_real - $costoReal) > 1)
                                                {
                                                    $labelError = "<span class='label label-danger'"
                                                        . " title='Tarifa: " . $curRate["tarifa"] . "\n--- Base: " . $curRate['base'] . " Min: " . $curRate["minuto"] . " Km: " . $curRate["km"]
                                                        . " ---\nTiempo (s): " . $tiempoReal . "\nDistancia (m): " . $viaje->distancia_real . "'"
                                                        . ">C&aacute;lculo: " . round($costoReal, 2) . "</span>"
                                                        . " <small><a class='fixCost m-t-5' href='#' rel='" . $viaje->id . "'>Corregir</a></small>";
                                                }
                                            }
                                            $fixed = "<span class='badge badge-success hidden'><i class='fa fa-check'></i> Corregido</span>";
                                            if ($viaje->corregido == 1)
                                            {
                                                $fixed = "<span class='badge badge-success'><i class='fa fa-check'></i> Corregido</span>";
                                            }
                                            ?>
                                            <tr role="row" class="gradeX odd">
                                                <td><?= $viaje->id ?></td>
                                                <td><a href="<?= $link ?>" target="_blank"><?= $viaje->taxista_nombre ?> <?= $viaje->taxista_apellidos ?></a></td>
                                                <td class="f-bold"><a href="<?= $link ?>" ><?= $viaje->taxi_numero_economico . " - " . $viaje->taxi_marca . " (" . $viaje->taxi_anio . ")" ?></a></td>
                                                <td><a href="<?= $link ?>" target="_blank"><?= $viaje->propietario_nombre ?> <?= $viaje->propietario_apellidos ?></a></td>
                                                <td class="f-bold"><a href="<?= $link ?>" ><?= Functions::getMetersToKm($viaje->distancia_real) ?> Km. / <?= Functions::getSecondsToMinH($viaje->tiempo_real) ?></a></td>
                                                <td class="f-bold"><a href="<?= $link ?>" >$<span class="costo_real"><?= $costo ?></span></a></td>
                                                <td><?= ($viaje->taxi_libre) ? "<span class='label label-warning'>Taxi Libre</span>" : "" ?></td>
                                                <td>
                                                    <?= $fixed . $labelError ?>
                                                </td>
                                                <td>{{ $viaje->email_cliente }}</td>
                                                <td class="sorting_1"><?= substr($viaje->created_at, 0, 10) ?> - <?= Functions::getDatetimeToAmPm($viaje->created_at) ?></td>
                                                <td><span class="label label-<?= $label ?>"><?= $viaje->status ?></span></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@stop

@section('scripts')
<!-- ================== BEGIN PAGE TABLES LEVEL JS ================== -->
<script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/js/table-manage-default.demo.js"></script>
<script src="assets/js/apps.min.js"></script>
<script src="/assets/plugins/parsley/dist/parsley.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<!-- ================== END PAGE MODAL LEVEL JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="assets/plugins/ionRangeSlider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>
<script src="assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<script src="assets/plugins/masked-input/masked-input.min.js"></script>
<script src="assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script src="assets/plugins/password-indicator/js/password-indicator.js"></script>
<script src="assets/plugins/bootstrap-combobox/js/bootstrap-combobox.js"></script>
<script src="assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
<script src="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.js"></script>
<script src="assets/plugins/jquery-tag-it/js/tag-it.min.js"></script>
<script src="assets/plugins/bootstrap-daterangepicker/moment.js"></script>
<script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="assets/plugins/select2/dist/js/select2.min.js"></script>
<script src="assets/plugins/bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/js/form-plugins.demo.js"></script>
<script src="/assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="assets/js/apps.min.js"></script>
<script>
                $(document).ready(function () {
                    App.init();
                    //TableManageDefault.init();
                    FormPlugins.init();
                    $('.selectpicker').selectpicker('render');

                    $('#data-table').DataTable({
                        responsive: true,
                        order: [[0, "desc"]],
                        initComplete: function () {
                            loadEv();
                        },
                        drawCallback: loadEv()
                    });


                });
                function loadEv()
                {
                    $("a.fixCost").off("click");
                    $("a.fixCost").on({
                        click: function (e) {
                            e.preventDefault();

                            var btn = $(this);
                            var td = btn.closest("td");
                            var tr = td.closest("tr");

                            var formData = {
                                id: btn.attr("rel")
                            };
                            $.ajax({
                                url: "/viajes/fixCost",
                                data: formData,
                                type: "POST",
                                success: function (response) {
                                    if (response.result === "ok") {
                                        td.find("span.badge").removeClass("hidden");
                                        td.find("span.label").addClass("hidden");
                                        btn.remove();
                                        tr.find(".costo_real").html(response.costo_real);
                                    } else {
                                        alert("Hubo un error, intente de nuevo");
                                    }
                                }
                            });
                        }
                    });
                }
</script>
@stop