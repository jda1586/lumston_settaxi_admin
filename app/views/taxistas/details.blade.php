
@section('css')
<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="/assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />	
<link href="/assets/plugins/parsley/src/parsley.css" rel="stylesheet" />
<link href="/assets/plugins/password-indicator/css/password-indicator.css" rel="stylesheet" />
<!-- ================== END PAGE LEVEL STYLE ================== -->

<link href="/assets/css/custom/app.css" rel="stylesheet" />


@stop

@section('content')

<div id="content" class="content">
    <div class="row">
        <div class="col-md-12">
            <a href="/taxistas" class="btn btn-sm btn-default pull-left"><i class="fa fa-arrow-circle-left"></i> Regresar a la lista</a>
            <a href="#" onclick="$('#btnSubmit').trigger('click');" class="btn btn-sm btn-inverse pull-left m-l-10"><i class="fa fa-check m-r-5"></i> Guardar Taxista</a>
            <ol class="breadcrumb pull-right hidden-phone">
                <li><a href="/"> &nbsp; &nbsp; Home</a></li>
                <li><a href="/taxistas">Taxistas</a></li>
                <li class="active"><?= $taxista->nombre . " " . $taxista->apellidos ?></li>
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
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                    </div>
                    <h4 class="panel-title">Detalle del Taxista
                </div>
                <div class="panel-body">
                    <div class="dataTables_wrapper dt-bootstrap no-footer" id="data-table_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <form novalidate="" class="hasFileDrag form-horizontal form-bordered" data-parsley-validate="true"
                                      name="editForm" id="editForm" method="post" action="/taxistas/<?= $taxista->id ?>/<?= $taxista->nombre . " " . $taxista->apellidos ?>">
                                    <div class="dragOver"><div class="borderDashed"></div><i class="fa fa-cloud-upload"></i></div>
                                    <input type="hidden" name="id" id="id" value="<?= $taxista->id ?>" />
                                    <input type="hidden" name="status" id="status" value="<?= $taxista->status ?>" />
                                    <button type="submit" id="btnSubmit" class="btn btn-primary hidden">Guardar</button>
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
                                            <input class="form-control" id="nombre" name="nombre" placeholder="Nombre del Taxista" value="<?= $taxista->nombre ?>"
                                                   data-type="string" data-parsley-required="true" type="text" data-parsley-range="[2,50]" />
                                            <ul class="parsley-errors-list"></ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Apellidos :</label>
                                        <div class="col-md-9 col-sm-9">
                                            <input class="form-control" id="apellidos" name="apellidos" placeholder="Nombre del Taxista" value="<?= $taxista->apellidos ?>"
                                                   data-type="string" data-parsley-required="true" type="text" data-parsley-range="[2,50]" />
                                            <ul class="parsley-errors-list"></ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Foto :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="file" name="file" />
                                            <div class="foto" style="background-image: url(<?= uploads_url($taxista->foto) ?>);"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Tel&eacute;fono :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" id="telefono" name="telefono" placeholder="Tel&eacute;fono" value="<?= $taxista->telefono ?>" type="text" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Licencia :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" id="licencia" name="licencia" placeholder="Licencia" value="<?= $taxista->licencia ?>"
                                                   data-type="string" data-parsley-required="false" type="text" />
                                            <ul class="parsley-errors-list"></ul>
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
                    </div>
                    <h4 class="panel-title">Usuario</h4>
                </div>
                <div class="panel-body p-1">
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="data-table_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <form novalidate="" class="form-horizontal form-bordered" data-parsley-validate="true"
                                      name="editUserForm" id="editUserForm" method="post" action="/taxistas/<?= $taxista->id ?>/<?= $taxista->nombre . " " . $taxista->apellidos ?>">
                                    <input type="hidden" name="id" id="id" value="<?= $usuario->id ?>" />
                                    <button type="submit" id="btnSubmit" class="btn btn-primary hidden">-</button>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Usuario :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" id="username" name="username" placeholder="Usuario" value="<?= $usuario->username ?>"
                                                   data-type="string" data-parsley-required="true" type="text" data-parsley-range="[2,50]" />
                                            <ul class="parsley-errors-list"></ul>
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
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3"></label>
                                        <div class="col-md-6 col-sm-6">
                                            <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
<!-- ================== END PAGE LEVEL JS ================== -->


<script src="/assets/js/admin/taxista.js"></script>

@stop