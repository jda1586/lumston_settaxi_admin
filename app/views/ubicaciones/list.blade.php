
@section('css')
<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="/assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />	
<link href="/assets/plugins/parsley/src/parsley.css" rel="stylesheet" />
<link href="/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
<!-- ================== END PAGE LEVEL STYLE ================== -->
@stop

@section('content')

<div style="display: none;" class="modal fade in" id="modalCreate">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Crear una Ubicación</h4>
            </div>
            <div class="modal-body">
                <form novalidate="" class="form-horizontal form-bordered" data-parsley-validate="true" name="new-form" method="post" action="ubicaciones/nuevo">
                    <div class="form-group no-border">
                        <label class="control-label col-md-4 col-sm-4">Sitio :</label>
                        <div class="col-md-6 col-sm-6">
                            <input class="form-control" id="titulo" name="titulo" placeholder="Nombre del Sitio"
                                   data-type="alphanum" data-parsley-required="true" type="text" data-parsley-range="[2,50]" />
                            <ul class="parsley-errors-list"></ul>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Ciudad:</label>
                        <div class="col-md-6 col-sm-6">
                            <select name="id_ciudad" class="form-control selectpicker" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-default">
                                <option value="" selected>Seleccione...</option>
                                @foreach ($cities as $city)
                                <option selected="" value="{{ $city->id }}">{{ $city->codigo }} - {{ $city->titulo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Tipo de ubicación:</label>
                        <div class="col-md-6 col-sm-6">
                            <select name="id_tipo_ubicacion" class="form-control selectpicker" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-default">
                                <option value="" selected>Seleccione...</option>
                                @foreach ($types as $type)
                                <option value="{{ $type->id }}">{{ $type->titulo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer m-t-10">
                        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Crear Ubicación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
if ($showMap)
{
    ?>
    <div class="map">
        <div id="routeMap" class="height-full width-full"></div>
    </div> 
    <?php
}
?>

<div id="content" class="content">
    <div class="row">
        <div class="col-md-12">
            <a href="#modalCreate" class="btn btn-sm btn-inverse pull-left" data-toggle="modal"><i class="fa fa-plus m-r-5"></i> Nueva Ubicación</a>
            <ol class="breadcrumb pull-right hidden-phone">
                <li><a href="/"> &nbsp; &nbsp; Home</a></li>
                <li><a href="ubicaciones">Ubicaciones</a></li>
                <li class="active">Lista</li>
            </ol>
        </div>
    </div>

    <?php
    if (!$showMap)
    {
        ?>
        <!-- begin row -->
        <div class="row m-t-5" id="tableUbicaciones">
            <!-- begin col-12 -->
            <div class="col-md-12 ui-sortable">
                <!-- begin panel -->
                <div class="email-btn-row hidden-xs">

                </div>
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        </div>
                        <h4 class="panel-title">Lista de Ubicaciones
                    </div>
                    <div class="panel-body">
                        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="data-table_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table aria-describedby="data-table_info" role="grid" id="data-table" class="table table-striped table-bordered dataTable no-footer dtr-inline">
                                        <thead>
                                            <tr role="row">
                                                <th aria-label="" style="width: 50px;" aria-controls="data-table" tabindex="0" class="sorting sorting_desc" >ID</th>
                                                <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Ciudad</th>
                                                <th aria-label="" style="width: 200px;" aria-controls="data-table" tabindex="0" class="sorting">Ubicación</th>
                                                <th aria-label="" style="width: 150px;" aria-controls="data-table" tabindex="0" class="sorting">Tipo</th>
                                                <th aria-label="" style="width: 150px;" aria-controls="data-table" tabindex="0" class="sorting">Fecha Registro</th>
                                                <th aria-sort="descending" aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Estatus</th>
                                                <th aria-sort="descending" aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($locations as $location)
                                            {
                                                $label = ($location->status == "activo") ? "success" : "default";
                                                $link = "/ubicaciones/" . $location->id . "/" . $location->titulo;
                                                $linkDel = "/ubicaciones/delete/" . $location->id;
                                                ?>
                                                <tr role="row" class="gradeX odd">
                                                    <td><?= $location->id ?></td>
                                                    <td><?= $location->ciudad_codigo ?></td>
                                                    <td class="f-bold"><a href="<?= $link ?>"><?= $location->titulo ?></a></td>
                                                    <td><?= $location->tipo_ubicacion_titulo ?></td>
                                                    <td class="sorting_1"><?= $location->created_at ?></td>
                                                    <td><span class="label label-<?= $label ?>"><?= $location->status ?></span></td>
                                                    <td class="sorting_1">
                                                        <a href="<?= $link ?>" class="btn btn-success btn-icon btn-circle btn-sm"><i class="fa fa-edit"></i></a>
                                                        <a href="<?= $linkDel ?>" class="btn btnDelete btn-danger btn-icon btn-circle btn-sm"><i class="fa fa-times"></i></a>
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
                <!-- end panel -->
            </div>
            <!-- end col-12 -->
        </div>
        <!-- end row -->
        <?php
    }
    ?>
</div>


@stop

@section('scripts')
<!-- ================== BEGIN PAGE TABLES LEVEL JS ================== -->
<script src="/assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
<script src="/assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="/assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="/assets/js/table-manage-default.demo.js"></script>
<script src="/assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<!-- ================== END PAGE MODAL LEVEL JS ================== -->
<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
<script src="/assets/js/ui-modal-notification.demo.min.js"></script>
<script src="/assets/plugins/parsley/dist/parsley.js"></script>
<script src="/assets/js/apps.min.js"></script>
<script src="/assets/js/admin/ubicaciones.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<?php
if ($showMap)
{
    ?>
    <script src="/assets/js/admin/ubicaciones_map.js"></script>
    <?php
}
?>

<script>
$(document).ready(function () {
    App.init();
    Notification.init();
    $('.selectpicker').selectpicker('render');

    var theTable = $("#tableUbicaciones");
    if (theTable.length === 1) {
        TableManageDefault.init();
    }
});
</script>

@stop