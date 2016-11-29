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
</div>

@stop

@section('scripts')

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script src="/assets/js/map-google.demo.js"></script>
<script src="/assets/js/apps.min.js"></script>
<!-- ================== END PAGE LEVEL JS ================== -->

<script src="/assets/js/admin/mapkers.js"></script>
<script src="/assets/js/admin/realtime.js"></script>

@stop