
@section('css')
<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="/assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />	
<link href="/assets/plugins/parsley/src/parsley.css" rel="stylesheet" />
<link href="/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
<link href="/assets/plugins/password-indicator/css/password-indicator.css" rel="stylesheet" />
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
            <a href="/usuarios" class="btn btn-sm btn-default pull-left"><i class="fa fa-arrow-circle-left"></i> Regresar a la lista</a>
            <a href="#" onclick="$('#btnSubmit').trigger('click');" class="btn btn-sm btn-inverse pull-left m-l-10"><i class="fa fa-check m-r-5"></i> Guardar Usuario</a>
            <ol class="breadcrumb pull-right hidden-phone">
                <li><a href="/"> &nbsp; &nbsp; Home</a></li>
                <li><a href="/usuarios">Usuarios</a></li>
                <li class="active"><?= $usuario->username ?></li>
            </ol>
        </div>
    </div>

    <!-- begin row -->
    <form novalidate="" class="hasFileDrag form-horizontal form-bordered" data-parsley-validate="true" name="editUserForm" id="editUserForm" method="post" action="/usuarios/<?= $usuario->id ?>/<?= $usuario->username ?>">
        <div class="dragOver"><div class="borderDashed"></div><i class="fa fa-cloud-upload"></i></div>
        <div class="row m-t-5">
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <div class="panel-heading-btn">
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                        </div>
                        <h4 class="panel-title">Detalle del Usuario
                    </div>
                    <div class="panel-body">
                        <div class="dataTables_wrapper dt-bootstrap no-footer" id="data-table_wrapper">
                            <div class="row">
                                <div class="col-sm-12">

                                    <button type="submit" id="btnSubmit" class="btn btn-primary hidden">Actualizar</button>
                                    <input type="hidden" name="status" id="status" value="<?= $usuario->status ?>" />
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
                                        <label class="control-label col-md-3 col-sm-3">Nombre :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" id="nombre" name="nombre" placeholder="Nombre" value="<?= $usuario->nombre ?>"
                                                   data-type="string" data-parsley-required="true" type="text" data-parsley-range="[2,50]" />
                                            <ul class="parsley-errors-list"></ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Foto :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="file" name="file" />
                                            <div class="foto" style="background-image: url(<?= uploads_url($usuario->foto) ?>);"></div>
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
                        <h4 class="panel-title">Usuario</h4>
                    </div>
                    <div class="panel-body p-1">
                        <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="data-table_wrapper">
                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="submit" id="btnSubmit" class="btn btn-primary hidden">-</button>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Usuario :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" id="username" name="username" placeholder="Usuario" value="<?= $usuario->username ?>"
                                                   data-type="string" data-parsley-required="true" type="text" data-parsley-range="[2,50]" />
                                            <ul class="parsley-errors-list"></ul>
                                        </div>
                                    </div>
                                    <div class="form-group no-border">
                                        <label class="control-label col-md-3 col-sm-3">Rol</label>
                                        <div class="col-md-6 col-sm-6">
                                            <select name="id_rol" class="form-control selectpicker" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-default">
                                                @foreach ($roles as $rol)
                                                <option value="{{ $rol->id }}" <?= ($rol->id == $usuario->id_rol) ? "selected" : "" ?>>{{ $rol->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Email :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" id="email" name="email" placeholder="Email" value="<?= $usuario->email ?>"
                                                   data-type="string" data-parsley-required="true" type="text" data-parsley-range="[2,50]" />
                                            <ul class="parsley-errors-list"></ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Password :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input value="********" type="password" name="password" id="password" class="form-control m-b-5" style="clear:both;display: block;" />
                                            <div id="passwordStrengthDiv" class="is0 m-t-5"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
    <!-- end row -->
</div>


@stop

@section('scripts')

<!-- ================== END PAGE MODAL LEVEL JS ================== -->
<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
<script src="/assets/js/ui-modal-notification.demo.min.js"></script>
<script src="/assets/plugins/parsley/dist/parsley.js"></script>
<script src="/assets/js/apps.min.js"></script>
<script src="/assets/js/dropzone.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="/assets/plugins/password-indicator/js/password-indicator.js"></script>
<script src="/assets/js/apps.min.js"></script>
<script src="/assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
<script src="/assets/js/admin/usuario.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->



@stop