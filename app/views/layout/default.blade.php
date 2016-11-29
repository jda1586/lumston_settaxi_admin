<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8" />
        <title>SET App Admin | <?= $section ?></title>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />

        <!-- ================== BEGIN BASE CSS STYLE ================== -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <link href="/assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
        <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
        <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
        <link href="/assets/css/animate.min.css" rel="stylesheet" />
        <link href="/assets/css/style.css" rel="stylesheet" />
        <link href="/assets/css/style-responsive.min.css" rel="stylesheet" />
        <link href="/assets/css/theme/orange.css" rel="stylesheet" id="theme" />
        <link href="/assets/lib/sweet-alert.css" rel="stylesheet" />
        <link href="/assets/css/custom/app.css" rel="stylesheet" id="theme" />
        <link href="/assets/plugins/simple-line-icons/simple-line-icons.css" rel="stylesheet" />
        <!-- ================== END BASE CSS STYLE ================== -->

        @yield('css')

        <!-- ================== BEGIN BASE JS ================== -->
        <script src="/assets/plugins/pace/pace.min.js"></script>
        <!-- ================== END BASE JS ================== -->
    </head>
    <body>
        <!-- begin #page-loader -->
        <div id="page-loader" class="fade in"><span class="spinner"></span></div>
        <!-- end #page-loader -->

        <!-- begin #page-container -->
        <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">
            <!-- begin #header -->
            <div id="header" class="header navbar navbar-default navbar-fixed-top navbar-inverse">
                <!-- begin container-fluid -->
                <div class="container-fluid">
                    <!-- begin mobile sidebar expand / collapse button -->
                    <div class="navbar-header">
                        <a href="/" class="navbar-brand"><span class="navbar-logo"></span> SET App Admin</a>
                        <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <!-- end mobile sidebar expand / collapse button -->

                    <!-- begin header navigation right -->
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <form class="navbar-form full-width">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Enter keyword" />
                                    <button type="submit" class="btn btn-search"><i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        </li>
                        <li class="dropdown navbar-user">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?= uploads_url($user->foto) ?>" alt="" /> 
                                <span class="hidden-xs">{{ $user->username }}</span> <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu animated fadeInLeft">
                                <li class="arrow"></li>
                                <li><a href="javascript:;">Edit Profile</a></li>
                                <li><a href="javascript:;"><span class="badge badge-danger pull-right">2</span> Inbox</a></li>
                                <li><a href="javascript:;">Calendar</a></li>
                                <li><a href="javascript:;">Setting</a></li>
                                <li class="divider"></li>
                                <li><a href="/login?logout=1">Log Out</a></li>
                            </ul>
                        </li>
                    </ul>
                    <!-- end header navigation right -->
                </div>
                <!-- end container-fluid -->
            </div>
            <!-- end #header -->

            <!-- begin #sidebar -->
            <div id="sidebar" class="sidebar">
                <!-- begin sidebar scrollbar -->
                <div data-scrollbar="true" data-height="100%">
                    <!-- begin sidebar user -->
                    <ul class="nav">
                        <li class="nav-profile">
                            <div class="image">
                                <a href="javascript:;"><img src="<?= uploads_url($user->foto) ?>" alt="" /></a>
                            </div>
                            <div class="info">
                                {{ $user->username }}
                                <small>SET {{ $user->rol->nombre }}</small>
                            </div>
                        </li>
                    </ul>
                    <!-- end sidebar user -->
                    <!-- begin sidebar nav -->
                    <ul class="nav">
                        <li class="nav-header">Navigation</li>
                        <li class="<?= ($section == "Home") ? "active" : "" ?>">
                            <a href="/viajes">
                                <i class="fa fa-area-chart"></i>
                                <span>Reporte de Viajes</span>
                            </a>
                        </li>
                        @if($user->rol->nombre == "Administrador")
                        <li class="<?= ($section == "Realtime") ? "active" : "" ?>"><a href="/realtime"><i class="fa fa-location-arrow"></i> <span>Tiempo Real</span></a></li>
                        <li class="<?= ($section == "Sitios") ? "active" : "" ?>"><a href="/sitios"><i class="icon-flag"></i> <span>Sitios de taxis</span></a></li>
                        <li class="<?= ($section == "Propietarios") ? "active" : "" ?>"><a href="/propietarios"><i class="icon-user"></i> <span>Propietarios</span></a></li>
                        <li class="<?= ($section == "Taxis") ? "active" : "" ?>"><a href="/taxis"><i class="fa fa-cab"></i> <span>Taxis</span></a></li>
                        <li class="<?= ($section == "Taxistas") ? "active" : "" ?>"><a href="/taxistas"><i class="icon-speedometer"></i> <span>Taxistas</span></a></li>
                        
                        <li class="nav-header">Configuration</li>
                        <li class="<?= ($section == "Usuarios") ? "active" : "" ?>">
                            <a href="/usuarios">
                                <i class="icon-users"></i>
                                <span>Usuarios</span>
                            </a>
                        </li>
                        <li class="<?= ($section == "Ubicaciones") ? "active" : "" ?>">
                            <a href="/ubicaciones">
                                <i class="fa fa-map-marker"></i>
                                <span>Ubicaciones</span>
                            </a>
                        </li>
                        <li class="<?= ($section == "UbicacionesMapa") ? "active" : "" ?>">
                            <a href="/ubicaciones?mapa=1">
                                <i class="fa icon-map"></i>
                                <span>Ubicaciones Mapa</span>
                            </a>
                        </li>
                        @endif
                        <!-- begin sidebar minify button -->
                        <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
                        <!-- end sidebar minify button -->
                    </ul>
                    <!-- end sidebar nav -->
                </div>
                <!-- end sidebar scrollbar -->
            </div>
            <div class="sidebar-bg"></div>
            <!-- end #sidebar -->

            <!-- begin #content -->
            @yield('content')
            <!-- end #content -->

            <!-- begin theme-panel -->
            <div class="theme-panel">
                <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn"><i class="fa fa-cog"></i></a>
                <div class="theme-panel-content">
                    <h5 class="m-t-0">Color Theme</h5>
                    <ul class="theme-list clearfix">
                        <li class="active"><a href="javascript:;" class="bg-green" data-theme="default" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Default">&nbsp;</a></li>
                        <li><a href="javascript:;" class="bg-red" data-theme="red" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Red">&nbsp;</a></li>
                        <li><a href="javascript:;" class="bg-blue" data-theme="blue" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Blue">&nbsp;</a></li>
                        <li><a href="javascript:;" class="bg-purple" data-theme="purple" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Purple">&nbsp;</a></li>
                        <li><a href="javascript:;" class="bg-orange" data-theme="orange" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Orange">&nbsp;</a></li>
                        <li><a href="javascript:;" class="bg-black" data-theme="black" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Black">&nbsp;</a></li>
                    </ul>
                    <div class="divider"></div>
                    <div class="row m-t-10">
                        <div class="col-md-5 control-label double-line">Header Styling</div>
                        <div class="col-md-7">
                            <select name="header-styling" class="form-control input-sm">
                                <option value="1">default</option>
                                <option value="2">inverse</option>
                            </select>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-5 control-label">Header</div>
                        <div class="col-md-7">
                            <select name="header-fixed" class="form-control input-sm">
                                <option value="1">fixed</option>
                                <option value="2">default</option>
                            </select>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-5 control-label double-line">Sidebar Styling</div>
                        <div class="col-md-7">
                            <select name="sidebar-styling" class="form-control input-sm">
                                <option value="1">default</option>
                                <option value="2">grid</option>
                            </select>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-5 control-label">Sidebar</div>
                        <div class="col-md-7">
                            <select name="sidebar-fixed" class="form-control input-sm">
                                <option value="1">fixed</option>
                                <option value="2">default</option>
                            </select>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-5 control-label double-line">Sidebar Gradient</div>
                        <div class="col-md-7">
                            <select name="content-gradient" class="form-control input-sm">
                                <option value="1">disabled</option>
                                <option value="2">enabled</option>
                            </select>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-5 control-label double-line">Content Styling</div>
                        <div class="col-md-7">
                            <select name="content-styling" class="form-control input-sm">
                                <option value="1">default</option>
                                <option value="2">black</option>
                            </select>
                        </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-12">
                            <a href="#" class="btn btn-inverse btn-block btn-sm" data-click="reset-local-storage"><i class="fa fa-refresh m-r-3"></i> Reset Local Storage</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end theme-panel -->

            <!-- begin scroll to top btn -->
            <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
            <!-- end scroll to top btn -->
        </div>
        <!-- end page container --> 


        <!-- ================== BEGIN BASE JS ================== -->
        <script src="/assets/plugins/jquery/jquery-1.9.1.min.js"></script>
        <script src="/assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
        <script src="/assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
        <script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="/assets/lib/sweet-alert.js"></script>
        <!--[if lt IE 9]>
            <script src="assets/crossbrowserjs/html5shiv.js"></script>
            <script src="assets/crossbrowserjs/respond.min.js"></script>
            <script src="assets/crossbrowserjs/excanvas.min.js"></script>
        <![endif]-->
        <script src="/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
        <script src="/assets/plugins/jquery-cookie/jquery.cookie.js"></script>
        <script src="/assets/js/custom/app.js"></script>
        <!-- ================== END BASE JS ================== -->

        @yield('scripts')

    </body>
</html>
</body>
</html>




