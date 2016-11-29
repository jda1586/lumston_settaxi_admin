
@section('css')
<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="/assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />	
<link href="/assets/plugins/parsley/src/parsley.css" rel="stylesheet" />
<link href="/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
<!-- ================== END PAGE LEVEL STYLE ================== -->

<style>
    #mapContainter{
        width: 100%; height: 400px;
    }
</style>

@stop

@section('content')

<div id="content" class="content">
    <div class="row">
        <div class="col-md-12">
            <a href="/sitios" class="btn btn-sm btn-default pull-left"><i class="fa fa-arrow-circle-left"></i> Regresar a la lista</a>
            <a href="#" onclick="$('#btnSubmit').trigger('click');" class="btn btn-sm btn-inverse pull-left m-l-10"><i class="fa fa-check m-r-5"></i> Guardar</a>
            <ol class="breadcrumb pull-right hidden-phone">
                <li><a href="/"> &nbsp; &nbsp; Home</a></li>
                <li><a href="sitios">Sitios</a></li>
                <li class="active"><?= $sitio->nombre ?></li>
            </ol>
        </div>
    </div>

    <!-- begin row -->
    <div class="row m-t-5">
        <div class="col-md-6">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                    </div>
                    <h4 class="panel-title">Detalle del Sitio
                </div>
                <div class="panel-body">
                    <div class="dataTables_wrapper dt-bootstrap no-footer" id="data-table_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <form novalidate="" class="form-horizontal form-bordered" data-parsley-validate="true" name="editForm" id="editForm" method="post" action="/sitios/<?= $sitio->id ?>/<?= $sitio->nombre ?>">
                                    <button type="submit" id="btnSubmit" class="btn btn-primary hidden">Crear Sitio</button>
                                    <input type="hidden" name="status" id="status" value="<?= $sitio->status ?>" />
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Estatus :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="btn-group btn-group-justified statusButtons" data-target="status">
                                                <a class="btn btn-success" rel="activo"><i class="fa fa-check m-r-10"></i> Activo</a>
                                                <a class="btn btn-default" rel="inactivo"><i class="fa fa-check m-r-10"></i> Inactivo</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">N&uacute;mero :</label>
                                        <div class="col-md-3 col-sm-3">
                                            <input class="form-control" id="numero" name="numero" placeholder="N&uacute;mero del Sitio" value="<?= $sitio->numero ?>"
                                                   data-type="alphanum" data-parsley-required="true" type="text" />
                                            <ul class="parsley-errors-list"></ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Sitio :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" id="nombre" name="nombre" placeholder="Nombre del Sitio" value="<?= $sitio->nombre ?>"
                                                   data-type="alphanum" data-parsley-required="true" type="text" data-parsley-range="[2,50]" />
                                            <ul class="parsley-errors-list"></ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Direcci&oacute;n :</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input class="form-control" id="direccion" name="direccion" placeholder="Direcci&oacute;n" value="<?= $sitio->direccion ?>"
                                                   data-type="alphanum" data-parsley-required="true" type="text" data-parsley-range="[2,50]" />
                                            <ul class="parsley-errors-list"></ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Tel&eacute;fono :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" id="telefono" name="telefono" placeholder="Tel&eacute;fono" value="<?= $sitio->telefono ?>"
                                                   data-type="alphanum" data-parsley-required="false" type="text" />
                                            <ul class="parsley-errors-list"></ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Geo:</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" id="lat" name="lat" placeholder="Lat" value="<?= $sitio->lat ?>" readonly=""
                                                   data-type="alphanum" data-parsley-required="false" type="text" style="width:40%;display: inline-block;float: left;" />
                                            <input class="form-control" id="lng" name="lng" placeholder="Lng" value="<?= $sitio->lng ?>" readonly=""
                                                   data-type="alphanum" data-parsley-required="false" type="text"  style="width:40%;display: inline-block;float: left; margin-left: 5px;" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Encargado:</label>
                                        <div class="col-md-6 col-sm-6">
                                            <select name="id_encargado" id="id_encargado" class="m-b-10 form-control selectpicker" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-default">
                                                @foreach ($encargados as $encargado)
                                                <option {{ ($sitio->id_encargado == $encargado->id) ? "selected" : "" }} value="{{ $encargado->id }}" >{{ $encargado->nombre }} - {{ $encargado->username }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </form>
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
    <!-- end row -->

    <div style="display: none;" class="modal fade in" id="modalAddtaxi">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Asignar un Taxi</h4>
                </div>
                <div class="modal-body">
                    <form novalidate="" class="form-horizontal form-bordered" data-parsley-validate="true" name="new-form"
                          method="post" action="/sitios/<?= $sitio->id ?>/<?= $sitio->nombre ?>">
                        <div class="form-group no-border">
                            <label class="control-label col-md-4 col-sm-4">Taxi :</label>
                            <div class="col-md-6 col-sm-6">
                                <select name="id_taxi" class="form-control selectpicker" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-default">
                                    <option value="" selected>Seleccione...</option>
                                    @foreach ($taxistodos as $taxi)
                                    <option value="{{ $taxi->id }}">{{ $taxi->numero_economico }} - {{ $taxi->marca }} {{ $taxi->modelo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer m-t-10">
                            <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Asignar Taxi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="row m-t-5">
        <div class="col-md-12">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                    <h4 class="panel-title">
                        Taxis
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="dataTables_wrapper dt-bootstrap no-footer" id="data-table_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="#modalAddtaxi" class="btn btn-sm btn-success m-b-10" data-toggle="modal"><i class="fa fa-cab m-r-5"></i> Agregar Taxi Existente...</a>
                                <a href="/taxis#modalCreate" target="_blank" class="btn btn-sm btn-default m-b-10"><i class="fa fa-plus m-r-5"></i> Nuevo Taxi...</a>
                                <table aria-describedby="data-table_info" role="grid" id="data-table" class="table table-striped table-bordered dataTable no-footer dtr-inline">
                                    <thead>
                                        <tr role="row">
                                            <th aria-label="" style="width: 50px;" aria-controls="data-table" tabindex="0" class="sorting sorting_desc" >ID</th>
                                            <th aria-label="" style="width: 80px;" aria-controls="data-table" tabindex="0" class="sorting">No. Taxi</th>
                                            <th aria-label="" style="width: 80px;" aria-controls="data-table" tabindex="0" class="sorting">Placas</th>
                                            <th aria-label="" style="width: 150px;" aria-controls="data-table" tabindex="0" class="sorting">Marca/Modelo</th>
                                            <th aria-label="" style="width: 150px;" aria-controls="data-table" tabindex="0" class="sorting">Propietario</th>
                                            <th aria-label="" style="width: 170px;" aria-controls="data-table" tabindex="0" class="sorting">Fecha Registro</th>
                                            <th aria-sort="descending" aria-label="Status" style="width: 80px;" aria-controls="data-table" tabindex="0" class="sorting">Estatus</th>
                                            <th aria-sort="descending" aria-label="" style="width: 60px;" aria-controls="data-table" tabindex="0" class="">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($taxis as $taxi)
                                        {
                                            $label = ($taxi->status == "activo") ? "success" : "default";
                                            $link = "/taxis/" . $taxi->id . "/Taxi-No-" . $taxi->numero_economico;
                                            $linkSitio = "/sitios/" . $taxi->id_sitio . "/" . $taxi->nombre_sitio;
                                            $linkProp = "/propietarios/" . $taxi->id_propietario . "/" . $taxi->nombre_propietario;
                                            $linkDel = "/taxis/delete/" . $taxi->id;
                                            ?>
                                            <tr role="row" class="gradeX odd">
                                                <td><?= $taxi->id ?></td>
                                                <td><a href="<?= $link ?>" title="Editar informaci&oacute;n del taxi" target="_blank"><strong><i class="fa fa-external-link"></i> <?= $taxi->numero_economico ?></strong></a></td>
                                                <td class="f-bold"><a href="<?= $link ?>" title="Editar informaci&oacute;n del taxi" target="_blank"><strong><i class="fa fa-external-link"></i> <?= $taxi->placas ?></strong></a></td>
                                                <td><?= $taxi->marca ?> / <?= $taxi->modelo ?></td>
                                                <td><a href="<?= $linkProp ?>" title="Editar informaci&oacute;n del propietario" target="_blank"><i class="fa fa-external-link"></i> <?= $taxi->nombre_propietario ?></a></td>
                                                <td class="sorting_1"><?= $taxi->created_at ?></td>
                                                <td><span class="label label-<?= $label ?>"><?= $taxi->status ?></span></td>
                                                <td class="sorting_1">
                                                    <a href="<?= $link ?>" title="Editar este taxi o cambiarlo de sitio" target="_blank" class="btn btn-success btn-icon btn-circle btn-sm"><i class="fa fa-edit"></i></a>
                                                </td>
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
<script src="/assets/js/custom/map-sitios.js"></script>
<script src="/assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->


<script src="/assets/js/admin/sitio.js"></script>

@stop