
@section('css')
<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="/assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />	
<link href="/assets/plugins/parsley/src/parsley.css" rel="stylesheet" />
<link href="/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
<link href="/assets/plugins/jquery-tag-it/css/jquery.tagit.css" rel="stylesheet" />
<!-- ================== END PAGE LEVEL STYLE ================== -->

<style>
    #mapContainter{
        width: 100%; height: 500px;
    }
</style>

@stop

@section('content')

<div id="content" class="content">
    <div class="row">
        <div class="col-md-12">
            <a href="/ubicaciones" class="btn btn-sm btn-default pull-left"><i class="fa fa-arrow-circle-left"></i> Regresar a la lista</a>
            <a href="#" onclick="$('#btnSubmit').trigger('click');" class="btn btn-sm btn-inverse pull-left m-l-10"><i class="fa fa-check m-r-5"></i> Guardar</a>
            <ol class="breadcrumb pull-right hidden-phone">
                <li><a href="/"> &nbsp; &nbsp; Home</a></li>
                <li><a href="ubicaciones">Ubicaciones</a></li>
                <li class="active"><?= $location->titulo ?></li>
            </ol>
        </div>
    </div>

    <!-- begin row -->
    <div class="row m-t-5">
        <form novalidate="" class="form-horizontal form-bordered" data-parsley-validate="true" name="editForm" id="editForm" method="post" action="/ubicaciones/<?= $location->id ?>/<?= $location->titulo ?>">
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        </div>
                        <h4 class="panel-title">Ubicación</h4>
                    </div>
                    <div class="panel-body">
                        <div class="dataTables_wrapper dt-bootstrap no-footer" id="data-table_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="submit" id="btnSubmit" class="btn btn-primary hidden"></button>
                                    <input type="hidden" name="status" id="status" value="<?= $location->status ?>" />
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Estatus:</label>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="btn-group btn-group-justified statusButtons" data-target="status">
                                                <a class="btn btn-success" rel="activo"><i class="fa fa-check m-r-10"></i> Activo</a>
                                                <a class="btn btn-default" rel="inactivo"><i class="fa fa-check m-r-10"></i> Inactivo</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Título:</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input class="form-control" id="titulo" name="titulo" placeholder="Nombre de la ubicación" value="<?= $location->titulo ?>"
                                                   data-type="alphanum" data-parsley-required="true" type="text" data-parsley-range="[2,50]" />
                                            <ul class="parsley-errors-list"></ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Dirección:</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input class="form-control" id="direccion" name="direccion" placeholder="Dirección" value="<?= $location->direccion ?>"
                                                   data-type="alphanum" data-parsley-required="true" type="text" data-parsley-range="[2,50]" />
                                            <ul class="parsley-errors-list"></ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Etiquetas:</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input type="hidden" id="etiquetas" name="etiquetas" placeholder="" value="<?= $location->etiquetas ?>" />
                                            <small>Escriba una etiqueta y presione <strong>Enter</strong></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        </div>
                        <h4 class="panel-title">Detalles</h4>
                    </div>
                    <div class="panel-body">
                        <div class="dataTables_wrapper dt-bootstrap no-footer" id="data-table_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Geo:</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input class="form-control" id="lat" name="lat" placeholder="Lat" value="<?= $location->lat ?>" readonly=""
                                                   data-type="alphanum" data-parsley-required="false" type="text" style="width:40%;display: inline-block;float: left;" />
                                            <input class="form-control" id="lng" name="lng" placeholder="Lng" value="<?= $location->lng ?>" readonly=""
                                                   data-type="alphanum" data-parsley-required="false" type="text"  style="width:40%;display: inline-block;float: left; margin-left: 5px;" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Ciudad:</label>
                                        <div class="col-md-9 col-sm-9">
                                            <select name="id_ciudad" id="id_ciudad" class="m-b-10 form-control selectpicker" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-default">
                                                @foreach ($cities as $city)
                                                <option {{ ($location->id_ciudad == $city->id) ? "selected" : "" }} value="{{ $city->id }}" >{{ $city->codigo }} - {{ $city->titulo }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Tipo:</label>
                                        <div class="col-md-9 col-sm-9">
                                            <select name="id_tipo_ubicacion" id="id_tipo_ubicacion" class="m-b-10 form-control selectpicker" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-default">
                                                @foreach ($types as $type)
                                                <option {{ ($location->id_tipo_ubicacion == $type->id) ? "selected" : "" }} value="{{ $type->id }}" >{{ $type->titulo }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- end row -->

    <div class="row m-t-5">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                    </div>
                    <h4 class="panel-title">Geolocalizaci&oacute;n
                        <div class="btn-group map-btn pull-right" style="z-index: 1000;">
                            <button type="button" class="btn btn-sm btn-default" id="map-theme-text">Default Theme</button>
                            <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" id="map-theme-selection">
                                <li class="active"><a href="javascript:;" data-map-theme="default">Default</a></li>
                                <li><a href="javascript:;" data-map-theme="icy-blue">Icy Blue</a></li>
                                <li><a href="javascript:;" data-map-theme="cobalt">Cobalt</a></li>
                                <li><a href="javascript:;" data-map-theme="dark-red">Dark Red</a></li>
                            </ul>
                        </div>
                    </h4>
                </div>
                <div class="panel-body p-1">
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="data-table_wrapper" style="min-height:100%;">
                        <div class="row" style="padding: 0;margin: 0;">
                            <div class="col-sm-12" style="min-height:480px;position: relative;margin:0;">
                                <div class="map" id="mapContainter" style="width:100%; height: 100%;position: absolute;top: 0;left:0;right:0;bottom:0;">

                                    <div id="google-map-default" class="height-full width-full"></div>
                                </div>
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

<!-- ================== END PAGE MODAL LEVEL JS ================== -->
<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
<script src="/assets/js/ui-modal-notification.demo.min.js"></script>
<script src="/assets/plugins/parsley/dist/parsley.js"></script>
<script src="/assets/js/apps.min.js"></script>


<script src="/assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
<script src="/assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="/assets/js/table-manage-default.demo.js"></script>
<script src="/assets/js/apps.min.js"></script>

<!-- ================== END PAGE LEVEL JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
<script src="/assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->
<script src="/assets/plugins/jquery-tag-it/js/tag-it.min.js"></script>

<script src="/assets/js/map-google.demo.js"></script>

<script src="/assets/js/admin/ubicacion.js"></script>
<script src="/assets/js/custom/map-ubicaciones.js"></script>

@stop