
@section('css')
<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
<link href="/assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
<link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />	
<link href="/assets/plugins/parsley/src/parsley.css" rel="stylesheet" />
<link href="/assets/plugins/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
<!-- ================== END PAGE LEVEL STYLE ================== -->

<link href="/assets/css/custom/app.css" rel="stylesheet" />


@stop

@section('content')

<div id="content" class="content">
    <div class="row">
        <div class="col-md-12">
            <a href="/taxis" class="btn btn-sm btn-default pull-left"><i class="fa fa-arrow-circle-left"></i> Regresar a la lista</a>
            <a href="#" onclick="$('#btnSubmit').trigger('click');" class="btn btn-sm btn-inverse pull-left m-l-10"><i class="fa fa-check m-r-5"></i> Guardar Taxi</a>
            <ol class="breadcrumb pull-right hidden-phone">
                <li><a href="/"> &nbsp; &nbsp; Home</a></li>
                <li><a href="/taxis">Taxis</a></li>
                <li class="active">Taxi-No-<?= $taxi->numero_economico ?></li>
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
                    </div>
                    <h4 class="panel-title">Detalle del Taxi
                </div>
                <div class="panel-body">
                    <div class="dataTables_wrapper dt-bootstrap no-footer" id="data-table_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <form novalidate="" class="form-horizontal form-bordered" data-parsley-validate="true"
                                      name="editForm" id="editForm" method="post" action="/taxis/<?= $taxi->id ?>/Taxi-No-<?= $taxi->numero_economico ?>">
                                    <div class="dragOver"><div class="borderDashed"></div><i class="fa fa-cloud-upload"></i></div>
                                    <input type="hidden" name="id" id="id" value="<?= $taxi->id ?>" />
                                    <input type="hidden" name="status" id="status" value="<?= $taxi->status ?>" />
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
                                        <label class="control-label col-md-3 col-sm-3">N&uacute;mero Econ&oacute;mico :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" id="numero_economico" name="numero_economico" placeholder="N&uacute;mero Econ&oacute;mico" value="<?= $taxi->numero_economico ?>"
                                                   data-type="string" data-parsley-required="true" type="text" />
                                            <ul class="parsley-errors-list"></ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Placas :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" id="placas" name="placas" placeholder="Placas" value="<?= $taxi->placas ?>"
                                                   data-type="string" data-parsley-required="true" type="text" data-parsley-range="[2,50]" />
                                            <ul class="parsley-errors-list"></ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Marca :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" id="marca" name="marca" placeholder="Marca" value="<?= $taxi->marca ?>"
                                                   data-type="string" data-parsley-required="true" type="text" data-parsley-range="[2,50]" />
                                            <ul class="parsley-errors-list"></ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Modelo :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input class="form-control" id="modelo" name="modelo" placeholder="Modelo" value="<?= $taxi->modelo ?>"
                                                   data-type="string" data-parsley-required="true" type="text" data-parsley-range="[2,50]" />
                                            <ul class="parsley-errors-list"></ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">A&ntilde;o :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <select name="anio" class="form-control selectpicker" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-default">
                                                @for ($i = 2016; $i >= 2000; $i--)
                                                <option {{ ($taxi->anio == $i) ? "selected" : "" }} value="{{ $i }}" >{{ $i }}</option>
                                                @endfor
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
                    </div>
                    <h4 class="panel-title">Sitio</h4>
                </div>
                <div class="panel-body p-1">
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="data-table_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <form novalidate="" class="form-horizontal form-bordered" data-parsley-validate="true"
                                      name="editTaxiSitio" id="editTaxiSitio" method="post" action="/taxis/<?= $taxi->id ?>/Taxi-No-<?= $taxi->numero_economico ?>">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Sitio :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <select name="id_sitio" id="select_id_sitio" class="m-b-10 form-control selectpicker" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-default">
                                                @foreach ($sitios as $sitio)
                                                <option {{ ($sitio->id == $taxi->id_sitio) ? "selected" : "" }} value="{{ $sitio->id }}" >{{ $sitio->numero }} - {{ $sitio->nombre }}</option>
                                                @endforeach
                                            </select>
                                            <a href="/sitios/<?= $taxi->id_sitio ?>/<?= $sitio->nombre ?>" target="_blank">
                                                <i class="fa fa-external-link"></i> Editar sitio...
                                            </a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3"></label>
                                        <div class="col-md-6 col-sm-6">
                                            <button type="submit" id="btnSaveSitio" class="btn btn-primary disabled">Actualizar Sitio</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <div class="panel-heading-btn">
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    </div>
                    <h4 class="panel-title">Propietario</h4>
                </div>
                <div class="panel-body p-1">
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="data-table_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <form novalidate="" class="form-horizontal form-bordered" data-parsley-validate="true"
                                      name="editTaxiPropietario" id="editTaxiPropietario" method="post" action="/taxis/<?= $taxi->id ?>/Taxi-No-<?= $taxi->numero_economico ?>">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3">Propietario :</label>
                                        <div class="col-md-6 col-sm-6">
                                            <select name="id_propietario" id="select_id_propietario" class="form-control selectpicker m-b-10" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-default">
                                                @foreach ($propietarios as $prop)
                                                <option {{ ($prop->id == $taxi->id_propietario) ? "selected" : "" }} value="{{ $prop->id }}" >{{ $prop->nombre }} {{ $prop->apellidos }}</option>
                                                @endforeach
                                            </select>
                                            <a href="/propietarios/<?= $taxi->id_propietario ?>/<?= $propietario->nombre . " " . $propietario->apellidos ?>" target="_blank">
                                                <i class="fa fa-external-link"></i> Editar propietario...
                                            </a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3"></label>
                                        <div class="col-md-6 col-sm-6">
                                            <button type="submit" id="btnSavePropietario" class="btn btn-primary disabled">Actualizar Propietario</button>
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
    
    <div style="display: none;" class="modal fade in" id="modalAddtaxista">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Asignar un Taxista</h4>
                </div>
                <div class="modal-body">
                    <form novalidate="" class="form-horizontal form-bordered" data-parsley-validate="true" name="new-form"
                          method="post" action="/taxis/<?= $taxi->id ?>/Taxi-No-<?= $taxi->numero_economico ?>">
                        <div class="form-group no-border">
                            <label class="control-label col-md-4 col-sm-4">Taxi :</label>
                            <div class="col-md-6 col-sm-6">
                                <select name="id_taxista" class="form-control selectpicker" data-size="10" data-parsley-required="true" data-live-search="true" data-style="btn-default">
                                    <option value="" selected>Seleccione...</option>
                                    @foreach ($taxistastodos as $taxista)
                                    <option value="{{ $taxista->id }}">{{ $taxista->licencia }} - {{ $taxista->nombre }} {{ $taxista->apellidos }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer m-t-10">
                            <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Asignar Taxista</button>
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
                        Taxistas
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="data-table_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="#modalAddtaxista" class="btn btn-sm btn-success m-b-10" data-toggle="modal"><i class="fa fa-user m-r-5"></i> Agregar Taxista Existente...</a>
                                <a href="/taxistas#modalCreate" target="_blank" class="btn btn-sm btn-default m-b-10"><i class="fa fa-plus m-r-5"></i> Nuevo Taxista...</a>
                                <table aria-describedby="data-table_info" role="grid" id="data-table" class="table table-striped table-bordered dataTable no-footer dtr-inline">
                                    <thead>
                                        <tr role="row">
                                            <th aria-label="" style="width: 50px;" aria-controls="data-table" tabindex="0" class="sorting sorting_desc" >ID</th>
                                            <th aria-label="" style="width: 200px;" aria-controls="data-table" tabindex="0" class="sorting">Nombre</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Licencia</th>
                                            <th aria-label="" style="width: 150px;" aria-controls="data-table" tabindex="0" class="sorting">Tel&eacute;fono</th>
                                            <th aria-label="" style="width: 150px;" aria-controls="data-table" tabindex="0" class="sorting">Fecha Registro</th>
                                            <th aria-sort="descending" aria-label="Status" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Estatus</th>
                                            <th aria-sort="descending" aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($taxistas as $taxista)
                                        {
                                            $label = ($taxista->status == "activo") ? "success" : "default";
                                            $link = "/taxistas/" . $taxista->id . "/" . $taxista->nombre . " " . $taxista->apellidos;
                                            $linkDel = "/taxistas/delete/" . $taxista->id;
                                            ?>
                                            <tr role="row" class="gradeX odd">
                                                <td><?= $taxista->id ?></td>
                                                <td><a href="<?= $link ?>" target="_blank"><i class="fa fa-external-link"></i> <?= $taxista->nombre ?> <?= $taxista->apellidos ?></a></td>
                                                <td class="f-bold"><a href="<?= $link ?>" target="_blank"><i class="fa fa-external-link"></i> <?= $taxista->licencia ?></a></td>
                                                <td><?= $taxista->telefono ?></td>
                                                <td class="sorting_1"><?= $taxista->created_at ?></td>
                                                <td><span class="label label-<?= $label ?>"><?= $taxista->status ?></span></td>
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
                        Viajes
                    </h4>
                </div>
                <div class="panel-body">
                    <div class="dataTables_wrapper form-inline dt-bootstrap no-footer" id="data-table_wrapper">
                        <div class="row">
                            <div class="col-sm-12">
                                <table aria-describedby="data-table_info" role="grid" id="data-table2" class="table table-striped table-bordered dataTable no-footer dtr-inline">
                                    <thead>
                                        <tr role="row">
                                            <th aria-label="" style="width: 50px;" aria-controls="data-table" tabindex="0" class="sorting sorting_desc" >ID</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Taxista</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Tiempo</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Distancia</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Costo</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Taxi Libre</th>
                                            <th aria-label="" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Email</th>
                                            <th aria-label="" style="width: 120px;" aria-controls="data-table" tabindex="0" class="sorting">Fecha Registro</th>
                                            <th aria-sort="descending" aria-label="Status" style="width: 100px;" aria-controls="data-table" tabindex="0" class="sorting">Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($viajes as $viaje)
                                        {
                                            $label = "default";
                                            switch($viaje->status)
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
                                                <td class="f-bold"><a href="<?= $link ?>" ><?= Functions::getSecondsToMinH($viaje->tiempo_real) ?></a></td>
                                                <td class="f-bold"><a href="<?= $link ?>" ><?= Functions::getMetersToKm($viaje->distancia_real) ?> Km.</a></td>
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

<!-- ================== END PAGE MODAL LEVEL JS ================== -->
<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>
<script src="/assets/js/ui-modal-notification.demo.min.js"></script>
<script src="/assets/plugins/parsley/dist/parsley.js"></script>
<script src="/assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>

<script src="/assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
<script src="/assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="/assets/js/table-manage-default.demo.js"></script>
<script src="/assets/js/apps.min.js"></script>

<!-- ================== END PAGE LEVEL JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="/assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->


<script src="/assets/js/admin/taxi.js"></script>

@stop