
@section('css')
<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="/assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />	
<link href="/assets/plugins/parsley/src/parsley.css" rel="stylesheet" />
<!-- ================== END PAGE LEVEL STYLE ================== -->
@stop

@section('content')

<div style="display: none;" class="modal fade in" id="modalCreate">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Crear un Sitio</h4>
            </div>
            <div class="modal-body">
                <form novalidate="" class="form-horizontal form-bordered" data-parsley-validate="true" name="new-form" method="post" action="sitios/nuevo">
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">N&uacute;mero :</label>
                        <div class="col-md-6 col-sm-6">
                            <input class="form-control" id="numero" name="numero" placeholder="N&uacute;mero del Sitio"
                                   data-type="alphanum" data-parsley-required="true" type="text" />
                            <ul class="parsley-errors-list"></ul>
                        </div>
                    </div>
                    <div class="form-group no-border">
                        <label class="control-label col-md-4 col-sm-4">Sitio :</label>
                        <div class="col-md-6 col-sm-6">
                            <input class="form-control" id="nombre" name="nombre" placeholder="Nombre del Sitio"
                                   data-type="alphanum" data-parsley-required="true" type="text" data-parsley-range="[2,50]" />
                            <ul class="parsley-errors-list"></ul>
                        </div>
                    </div>
                    <div class="modal-footer m-t-10">
                        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Crear Sitio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="content" class="content">
    <div class="row">
        <div class="col-md-12">
            <a href="#modalCreate" class="btn btn-sm btn-inverse pull-left" data-toggle="modal"><i class="fa fa-plus m-r-5"></i> Nuevo Sitio</a>
            <ol class="breadcrumb pull-right hidden-phone">
                <li><a href="/"> &nbsp; &nbsp; Home</a></li>
                <li><a href="sitios">Sitios</a></li>
                <li class="active">Lista</li>
            </ol>
        </div>
    </div>

    <!-- begin row -->
    <div class="row m-t-5">
        <!-- begin col-12 -->
        <div class="col-md-12 ui-sortable">
            <!-- begin panel -->
            <div class="email-btn-row hidden-xs">

            </div>
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                    </div>
                    <h4 class="panel-title">Lista de sitios
                </div>
                <div class="panel-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="data-table_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <table aria-describedby="data-table_info" role="grid" id="data-table" class="table table-striped table-bordered dataTable no-footer dtr-inline">
                                    <thead>
                                        <tr role="row">
                                            <th aria-label="" style="width: 50px;" aria-controls="data-table" tabindex="0" class="sorting sorting_desc" >ID</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">N&uacute;mero</th>
                                            <th aria-label="" style="width: 200px;" aria-controls="data-table" tabindex="0" class="sorting">Sitio</th>
                                            <th aria-label="" style="width: 150px;" aria-controls="data-table" tabindex="0" class="sorting">Tel&eacute;fono</th>
                                            <th aria-label="" style="width: 150px;" aria-controls="data-table" tabindex="0" class="sorting">Fecha Registro</th>
                                            <th aria-sort="descending" aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Estatus</th>
                                            <th aria-sort="descending" aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($sitios as $sitio)
                                        {
                                            $label = ($sitio->status == "activo") ? "success" : "default";
                                            $link = "/sitios/" . $sitio->id . "/" . $sitio->nombre;
                                            $linkDel = "/sitios/delete/" . $sitio->id;
                                            ?>
                                            <tr role="row" class="gradeX odd">
                                                <td><?= $sitio->id ?></td>
                                                <td><?= $sitio->numero ?></td>
                                                <td class="f-bold"><a href="<?= $link ?>"><?= $sitio->nombre ?></a></td>
                                                <td><?= $sitio->telefono ?></td>
                                                <td class="sorting_1"><?= $sitio->created_at ?></td>
                                                <td><span class="label label-<?= $label ?>"><?= $sitio->status ?></span></td>
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
</div>


@stop

@section('scripts')
<!-- ================== BEGIN PAGE TABLES LEVEL JS ================== -->
<script src="assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
<script src="assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="assets/js/table-manage-default.demo.js"></script>
<script src="assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<!-- ================== END PAGE MODAL LEVEL JS ================== -->
<script src="assets/plugins/gritter/js/jquery.gritter.js"></script>
<script src="assets/js/ui-modal-notification.demo.min.js"></script>
<script src="assets/plugins/parsley/dist/parsley.js"></script>
<script src="assets/js/apps.min.js"></script>
<script src="assets/js/admin/sitios.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>
    $(document).ready(function () {
        App.init();
        Notification.init();
        TableManageDefault.init();
    });
</script>

@stop