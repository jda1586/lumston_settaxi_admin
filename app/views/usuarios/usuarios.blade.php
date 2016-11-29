
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
                <h4 class="modal-title">Crear un Usuario</h4>
            </div>
            <div class="modal-body">
                <form novalidate="" class="form-horizontal form-bordered" data-parsley-validate="true" name="new-form" method="post" action="usuarios/nuevo">
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4">Usuario</label>
                        <div class="col-md-6 col-sm-6">
                            <input class="form-control" id="username" name="username" placeholder="Nombre de Usuario"
                                   data-type="alphanum" data-parsley-required="true" type="text" />
                            <ul class="parsley-errors-list"></ul>
                        </div>
                    </div>
                    <div class="form-group no-border">
                        <label class="control-label col-md-4 col-sm-4">Rol</label>
                        <div class="col-md-6 col-sm-6">
                            <select name="id_rol" class="form-control selectpicker" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-default">
                                <option value="" selected>Seleccione...</option>
                                @foreach ($roles as $rol)
                                <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer m-t-10">
                        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Crear Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="content" class="content">
    <div class="row">
        <div class="col-md-12">
            <a href="#modalCreate" class="btn btn-sm btn-inverse pull-left" data-toggle="modal"><i class="fa fa-plus m-r-5"></i> Nuevo Usuario</a>
            <ol class="breadcrumb pull-right hidden-phone">
                <li><a href="/"> &nbsp; &nbsp; Home</a></li>
                <li><a href="/usuarios">Usuarios</a></li>
                <li class="active">Lista</li>
            </ol>
        </div>
    </div>

    <!-- begin row -->
    <div class="row m-t-10">
        <!-- begin col-12 -->
        <div class="col-md-12 ui-sortable">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                    <h4 class="panel-title">Lista de Usuarios
                </div>
                <div class="panel-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="data-table_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <table aria-describedby="data-table_info" role="grid" id="data-table" class="table table-striped table-bordered dataTable no-footer dtr-inline">
                                    <thead>
                                        <tr role="row">
                                            <th aria-label="" style="width: 50px;" aria-controls="data-table" tabindex="0" class="sorting sorting_desc" >ID</th>
                                            <th aria-label="" style="width: 80px;" aria-controls="data-table" tabindex="0" class="sorting">Foto</th>
                                            <th aria-label="" style="width: 200px;" aria-controls="data-table" tabindex="0" class="sorting">Usuario</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Rol</th>
                                            <th aria-label="" style="width: 150px;" aria-controls="data-table" tabindex="0" class="sorting">Email</th>
                                            <th aria-label="" style="width: 150px;" aria-controls="data-table" tabindex="0" class="sorting">Nombre</th>
                                            <th aria-label="" style="width: 150px;" aria-controls="data-table" tabindex="0" class="sorting">Fecha Registro</th>
                                            <th aria-sort="descending" aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Estatus</th>
                                            <th aria-sort="descending" aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($usuarios as $usuario)
                                        {
                                            $label = ($usuario->status == "activo") ? "success" : "default";
                                            $link = "/usuarios/" . $usuario->id . "/" . $usuario->username;
                                            $linkDelete = "/usuarios/delete/" . $usuario->id;
                                            ?>
                                            <tr role="row" class="gradeX odd">
                                                <td><?= $usuario->id ?></td>
                                                <td>
                                                    <a href="<?= $link ?>"><div class="foto small rounded" style="background-image: url(<?= uploads_url($usuario->foto) ?>);"></div></a>
                                                </td>
                                                <td class="f-bold"><a href="<?= $link ?>"><strong><?= $usuario->username ?></strong></a></td>
                                                <td><?= $usuario->rol ?></td>
                                                <td><?= $usuario->email ?></td>
                                                <td><?= $usuario->nombre ?></td>
                                                <td class="sorting_1"><?= $usuario->created_at ?></td>
                                                <td><span class="label label-<?= $label ?>"><?= $usuario->status ?></span></td>
                                                <td class="sorting_1">
                                                    <a href="<?= $link ?>" class="btn btn-success btn-icon btn-circle btn-sm"><i class="fa fa-edit"></i></a>
                                                    <a href="<?= $linkDelete ?>" rel="<?= $usuario->id_rol ?>" class="btn btn-danger btn-icon btn-circle btn-sm btnDelete"><i class="fa fa-times"></i></a>
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
<script src="/assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script>
$(document).ready(function () {
    App.init();
    Notification.init();
    TableManageDefault.init();
    $('.selectpicker').selectpicker('render');
    $(".btnDelete").on({
        click: function (e) {
            e.preventDefault();
            var link = $(this).attr("href");
            var rol = $(this).attr("rel");
            if(rol === "5" || rol === "4"){
                msg.error("Este usuario no puede eliminarse porque está asociado a un Taxista o a un Propietario. Elimine al Taxista o Propietario y se dará de baja su usuario también.")
            }
            else{
                msg.confirm("Atención", "Confirma eliminar este usuario?", "Si, Eliminar", function () {
                    window.location = link;
                });
            }
        }
    });
});
</script>

@stop