

var handleGoogleMapSetting = function (idCurViaje) {
    "use strict";
    var mapDefault;
    var idViaje = idCurViaje;

    function initialize() {
        var mapOptions = {
            zoom: 13,
            center: new google.maps.LatLng(20.6637191, -103.3944486),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true,
        };
        mapDefault = new google.maps.Map(document.getElementById('routeMap'), mapOptions);
        setTimeout(function () {
            showViajePoints(idViaje);
        }, 500);
    }

    google.maps.event.addDomListener(window, 'load', initialize);

    $(window).resize(function () {
        google.maps.event.trigger(mapDefault, "resize");
    });

    var defaultMapStyles = [];
    var icyBlueStyles = [{"stylers": [{"hue": "#2c3e50"}, {"saturation": 250}]}, {"featureType": "road", "elementType": "geometry", "stylers": [{"lightness": 50}, {"visibility": "simplified"}]}, {"featureType": "road", "elementType": "labels", "stylers": [{"visibility": "off"}]}];
    var cobaltStyles = [{"featureType": "all", "elementType": "all", "stylers": [{"invert_lightness": true}, {"saturation": 10}, {"lightness": 10}, {"gamma": 0.8}, {"hue": "#293036"}]}, {"featureType": "water", "stylers": [{"visibility": "on"}, {"color": "#293036"}]}];
    var darkRedStyles = [{"featureType": "all", "elementType": "all", "stylers": [{"invert_lightness": true}, {"saturation": 10}, {"lightness": 10}, {"gamma": 0.8}, {"hue": "#000000"}]}, {"featureType": "water", "stylers": [{"visibility": "on"}, {"color": "#293036"}]}];

    var currentMarkers = [];
    var showMarkers = function () {
        console.log(currentMarkers);
        for (var i = 0; i < currentMarkers.length; i++) {
            var marker = currentMarkers[i];
            marker.setMap(mapDefault);
        }
    };
    var resetMarkers = function () {
        console.log(currentMarkers);
        for (var i = 0; i < currentMarkers.length; i++) {
            var marker = currentMarkers[i];
            marker.setMap(null);
        }
        currentMarkers = [];
    };


    var showViajePoints = function (idViaje) {
        //resetMarkers();
        var formData = {
            idViaje: idViaje
        };
        $.ajax({
            url: "/app/getMapDetails",
            data: formData,
            type: "GET",
            success: function (response) {
                var viaje = response.viaje;
                var freeMarker;
                var endMarker;
                var iconIni = new google.maps.MarkerImage("/assets/img/markerIni.png", null, null, null, new google.maps.Size(32,32));
                var iconEnd = new google.maps.MarkerImage("/assets/img/markerEnd.png", null, null, null, new google.maps.Size(32,32));
                var iconFree = new google.maps.MarkerImage("/assets/img/markerFree.png", null, null, null, new google.maps.Size(32,64));
                
                
                var iniMarker = new google.maps.Marker({
                    position: {lat: parseFloat(viaje.origen_lat), lng: parseFloat(viaje.origen_lng)},
                    title: 'Inicio del viaje: ' + helper.dates.dateToHour(viaje.hora_inicio),
                    animation: google.maps.Animation.DROP,
                    icon: iconIni
                });

                currentMarkers.push(iniMarker);
                var routeCoords = [];

                for (var i = 0; i < viaje.posiciones.length; i++) {
                    var position = viaje.posiciones[i];
                    var time = helper.dates.dateToHour(position.created);
                    if (i === 0) {
                        freeMarker = new google.maps.Marker({
                            position: {lat: parseFloat(position.lat), lng: parseFloat(position.lng)},
                            title: 'Inicio de taxi libre: ' + time,
                            animation: google.maps.Animation.DROP,
                            icon: iconFree
                        });
                        currentMarkers.push(freeMarker);
                    }
                    else {
                        var posMarker = new google.maps.Marker({
                            position: {lat: parseFloat(position.lat), lng: parseFloat(position.lng)},
                            title: 'Posición a las: ' + time,
                            icon: '/assets/img/markerPosition.png'
                        });
                        currentMarkers.push(posMarker);
                        routeCoords.push({lat: parseFloat(position.lat), lng: parseFloat(position.lng)});
                        if (i === (viaje.posiciones.length - 1)) {
                            endMarker = new google.maps.Marker({
                                position: {lat: parseFloat(position.lat), lng: parseFloat(position.lng)},
                                title: 'Destino del viaje: ' + time,
                                animation: google.maps.Animation.DROP,
                                icon: iconEnd
                                
                            });
                        }
                    }
                }

                if (typeof endMarker === "undefined") {
                    var endMarker = new google.maps.Marker({
                        position: {lat: parseFloat(viaje.destino_lat), lng: parseFloat(viaje.destino_lng)},
                        title: 'Destino del viaje: ' + viaje.hora_final,
                        animation: google.maps.Animation.DROP,
                        icon: iconEnd
                    });
                }
                
                currentMarkers.push(endMarker);
                showMarkers();

                var centerLat = (iniMarker.position.lat() + endMarker.position.lat()) / 2;
                var centerLng = (iniMarker.position.lng() + endMarker.position.lng()) / 2;
                var routeCenter = new google.maps.LatLng(centerLat, centerLng);
                mapDefault.setCenter(routeCenter);

                var routePath = new google.maps.Polyline({
                    path: routeCoords,
                    geodesic: true,
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 2
                });

                routePath.setMap(mapDefault);

            }
        });
    };
    $('[data-map-theme]').click(function () {
        var targetTheme = $(this).attr('data-map-theme');
        var targetLi = $(this).closest('li');
        var targetText = $(this).text();
        var inverseContentMode = false;
        $('#map-theme-selection li').not(targetLi).removeClass('active');
        $('#map-theme-text').text(targetText);
        $(targetLi).addClass('active');
        switch (targetTheme) {
            case 'icy-blue':
                mapDefault.setOptions({styles: icyBlueStyles});
                break;
            case 'cobalt':
                mapDefault.setOptions({styles: cobaltStyles});
                inverseContentMode = true;
                break;
            case 'dark-red':
                mapDefault.setOptions({styles: darkRedStyles});
                inverseContentMode = true;
                break;
            default:
                mapDefault.setOptions({styles: defaultMapStyles});
                break;
        }

        if (inverseContentMode === true) {
            $('#content').addClass('content-inverse-mode');
        } else {
            $('#content').removeClass('content-inverse-mode');
        }
    });
};

$(document).ready(function () {
    App.init();

    if (typeof idCurViaje !== "null") {
        handleGoogleMapSetting(idCurViaje);
    }

    $("#panelCollapseToggle").on({
        click: function (e) {
            e.preventDefault();
            $(this).find(".panel-heading-btn .btn-warning").trigger("click");
        }
    });

});

