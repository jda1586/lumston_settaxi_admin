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
                            <label class="control-label col-md-4 col-sm-4">Taxi:</label>
                            <div class="col-md-4 col-sm-4">
                                <select name="id_taxi" class="form-control selectpicker" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-default">
                                    <option value="0" selected>Todos</option>
                                    @if($type == "propietario")
                                    @foreach ($taxis as $taxi)
                                    <option value="{{ $taxi->id }}" <?= ($id_taxi == $taxi->id) ? "selected" : "" ?>>{{ $taxi->numero_economico }} - {{ $taxi->marca }} {{ $taxi->modelo }} ({{ $taxi->anio }})</option>
                                    @endforeach
                                    @endif
                                    @if($type == "taxista")
                                    @foreach ($taxis as $taxi)
                                    <option value="{{ $taxi->taxi->id }}" <?= ($id_taxi == $taxi->taxi->id) ? "selected" : "" ?>>{{ $taxi->taxi->numero_economico }} - {{ $taxi->taxi->marca }} {{ $taxi->taxi->modelo }} ({{ $taxi->taxi->anio }})</option>
                                    @endforeach
                                    @endif
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
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Propietario</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Distancia / Tiempo</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Costo</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Taxi Libre</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Email</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Fecha Registro</th>
                                            <th aria-sort="descending" aria-label="Status" style="width: 70px;" aria-controls="data-table" tabindex="0" class="sorting">Estatus</th>
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
                                            ?>
                                            <tr role="row" class="gradeX odd">
                                                <td><?= $viaje->id ?></td>
                                                <td><a href="<?= $link ?>" target="_blank"><?= $viaje->taxista_nombre ?> <?= $viaje->taxista_apellidos ?></a></td>
                                                <td class="f-bold"><a href="<?= $link ?>" ><?= $viaje->taxi_numero_economico . " - " . $viaje->taxi_marca . " (" . $viaje->taxi_anio . ")" ?></a></td>
                                                <td><a href="<?= $link ?>" target="_blank"><?= $viaje->propietario_nombre ?> <?= $viaje->propietario_apellidos ?></a></td>
                                                <td class="f-bold"><a href="<?= $link ?>" ><?= Functions::getMetersToKm($viaje->distancia_real) ?> Km. / <?= Functions::getSecondsToMinH($viaje->tiempo_real) ?></a></td>
                                                <td class="f-bold"><a href="<?= $link ?>" >$<?= $viaje->costo_real ?></a></td>
                                                <td><?= ($viaje->taxi_libre) ? "<span class='label label-warning'>Taxi Libre</span>" : "" ?></td>
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
                    TableManageDefault.init();
                    FormPlugins.init();
                    $('.selectpicker').selectpicker('render');
                });
</script>
@stop