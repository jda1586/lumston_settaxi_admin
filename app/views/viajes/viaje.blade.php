@section('css')

<link href="/assets/css/custom/app.css" rel="stylesheet" />

@section('content')

<div id="content" class="content content-full-width">
    <div class="btn-group map-btn pull-right" id="mapBtnTheme">
        <button type="button" class="btn btn-sm btn-inverse" id="map-theme-text">Default Theme</button>
        <button type="button" class="btn btn-sm btn-inverse dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" id="map-theme-selection">
            <li class="active"><a href="javascript:;" data-map-theme="default">Default</a></li>
            <li><a href="javascript:;" data-map-theme="icy-blue">Icy Blue</a></li>
            <li><a href="javascript:;" data-map-theme="cobalt">Cobalt</a></li>
            <li><a href="javascript:;" data-map-theme="dark-red">Dark Red</a></li>
        </ul>
    </div>
    <h1 class="page-header">Detalles de la ruta <small></small></h1>

    <div class="map">
        <div id="routeMap" class="height-full width-full"></div>
    </div>
    <div class="panel panel-inverse" id="mapInfo">
        <div class="panel-heading" id="panelCollapseToggle">
            <div class="panel-heading-btn">
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            </div>
            <h4 class="panel-title">
                <span class="label label-success m-r-10 pull-left f-s-14">Viaje #000{{ $viaje->id }}</span>
                <?php
                if ($viaje->taxi_libre)
                {
                    ?>
                    <span class="label label-warning m-r-10 pull-left f-s-14">Taxi Libre</span>
                    <?php
                }
                ?>
                    Detalle del Viaje - <span class="badge badge-error f-s-13">${{ $viaje->costo_real }}</span> <span class="badge badge-<?= ($tarifa['tarifa'] == "Noche") ? "danger" : "info" ?>">{{ $tarifa['tarifa'] }}</span></strong>
            </h4>
        </div>
        <div class="panel-body panel-body-info">
            <div class="row">
                <div class="col-sm-3 col-md-3">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-md-3">
                                <div class="foto small rounded" style="background-image: url(<?= uploads_url($taxista->foto) ?>);"></div>
                            </label>
                            <div class="col-md-9">
                                <strong class="p-t-10">{{ $taxista->nombre }} {{ $taxista->apellidos }}</strong><br />
                                {{ $taxista->telefono }}
                                <hr class="m-t-5 m-b-5" />
                                <strong>{{ $taxi->numero_economico }}</strong>
                                {{ $taxi->marca }} {{ $taxi->modelo }}, {{ $taxi->anio }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Fecha/Hora:</label>
                            <div class="col-md-9">
                                <?= substr($viaje->created_at, 0, 10) ?> - <?= Functions::getDatetimeToAmPm($viaje->created_at) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tiempo Real:</label>
                            <div class="col-md-9">
                                <?= Functions::getSecondsToMinH($viaje->tiempo_real) ?> <sup><?= Functions::getDatetimeToAmPm($viaje->hora_final) ?></sup>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Distancia Real:</label>
                            <div class="col-md-9">
                                <?= Functions::getMetersToKm($viaje->distancia_real) ?> Km
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Email Cliente:</label>
                            <div class="col-md-9">
                                <?php
                                if ($viaje->email_cliente)
                                {
                                    ?>
                                    <a class="btn btn-success btn-xs" href="mailto: {{ $viaje->email_cliente }}?subject=SET App - Viaje #000{{ $viaje->id }}">
                                        <i class="fa fa-mail-reply"></i> {{ $viaje->email_cliente }}
                                    </a>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <span class="label label-default m-r-10 pull-left f-s-12">No Capturado</span>
                                    <?php
                                }
                                ?>
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

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script src="/assets/js/map-google.demo.js"></script>
<script src="/assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script src="/assets/js/admin/viajes_map.js"></script>

<script>
var idCurViaje = <?= $viaje->id ?>;
</script>

@stop