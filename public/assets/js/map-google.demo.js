/*   
 Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.5
 Version: 1.9.0
 Author: Sean Ngu
 Website: http://www.seantheme.com/color-admin-v1.9/admin/
 */

var handleGoogleMapSetting = function () {
    "use strict";
    var mapDefault;

    function initialize() {
        var mapOptions = {
            zoom: 13,
            center: new google.maps.LatLng(20.6637191, -103.3944486),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true,
        };
        mapDefault = new google.maps.Map(document.getElementById('google-map-default'), mapOptions);
    }
    google.maps.event.addDomListener(window, 'load', initialize);

    $(window).resize(function () {
        google.maps.event.trigger(mapDefault, "resize");
    });

    var defaultMapStyles = [];
    var flatMapStyles = [{"stylers": [{"visibility": "off"}]}, {"featureType": "road", "stylers": [{"visibility": "on"}, {"color": "#ffffff"}]}, {"featureType": "road.arterial", "stylers": [{"visibility": "on"}, {"color": "#fee379"}]}, {"featureType": "road.highway", "stylers": [{"visibility": "on"}, {"color": "#fee379"}]}, {"featureType": "landscape", "stylers": [{"visibility": "on"}, {"color": "#f3f4f4"}]}, {"featureType": "water", "stylers": [{"visibility": "on"}, {"color": "#7fc8ed"}]}, {}, {"featureType": "road", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "poi.park", "elementType": "geometry.fill", "stylers": [{"visibility": "on"}, {"color": "#83cead"}]}, {"elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "landscape.man_made", "elementType": "geometry", "stylers": [{"weight": 0.9}, {"visibility": "off"}]}];
    var turquoiseWaterStyles = [{"featureType": "landscape.natural", "elementType": "geometry.fill", "stylers": [{"visibility": "on"}, {"color": "#e0efef"}]}, {"featureType": "poi", "elementType": "geometry.fill", "stylers": [{"visibility": "on"}, {"hue": "#1900ff"}, {"color": "#c0e8e8"}]}, {"featureType": "landscape.man_made", "elementType": "geometry.fill"}, {"featureType": "road", "elementType": "geometry", "stylers": [{"lightness": 100}, {"visibility": "simplified"}]}, {"featureType": "road", "elementType": "labels", "stylers": [{"visibility": "off"}]}, {"featureType": "water", "stylers": [{"color": "#7dcdcd"}]}, {"featureType": "transit.line", "elementType": "geometry", "stylers": [{"visibility": "on"}, {"lightness": 700}]}];
    var icyBlueStyles = [{"stylers": [{"hue": "#2c3e50"}, {"saturation": 250}]}, {"featureType": "road", "elementType": "geometry", "stylers": [{"lightness": 50}, {"visibility": "simplified"}]}, {"featureType": "road", "elementType": "labels", "stylers": [{"visibility": "off"}]}];
    var oldDryMudStyles = [{"featureType": "landscape", "stylers": [{"hue": "#FFAD00"}, {"saturation": 50.2}, {"lightness": -34.8}, {"gamma": 1}]}, {"featureType": "road.highway", "stylers": [{"hue": "#FFAD00"}, {"saturation": -19.8}, {"lightness": -1.8}, {"gamma": 1}]}, {"featureType": "road.arterial", "stylers": [{"hue": "#FFAD00"}, {"saturation": 72.4}, {"lightness": -32.6}, {"gamma": 1}]}, {"featureType": "road.local", "stylers": [{"hue": "#FFAD00"}, {"saturation": 74.4}, {"lightness": -18}, {"gamma": 1}]}, {"featureType": "water", "stylers": [{"hue": "#00FFA6"}, {"saturation": -63.2}, {"lightness": 38}, {"gamma": 1}]}, {"featureType": "poi", "stylers": [{"hue": "#FFC300"}, {"saturation": 54.2}, {"lightness": -14.4}, {"gamma": 1}]}];
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


    $("a.showTripMap").on({
        click: function (e) {
            e.preventDefault();
            var idViaje = $(this).attr("rel");
            resetMarkers();

            var formData = {
                idViaje: idViaje
            };
            $.ajax({
                url: "app/getMapDetails",
                data: formData,
                type: "GET",
                success: function (response) {
                    var viaje = response.viaje;
                    var iniMarker = new google.maps.Marker({
                        position: {lat: parseFloat(viaje.origen_lat), lng: parseFloat(viaje.origen_lng)},
                        title: 'Inicio del viaje',
                        animation: google.maps.Animation.DROP,
                        icon: 'assets/img/markerIni.png'
                    });

                    currentMarkers.push(iniMarker);

                    for (var i = 0; i < viaje.posiciones.length; i++) {
                        var position = viaje.posiciones[i];
                        var posMarker = new google.maps.Marker({
                            position: {lat: parseFloat(position.lat), lng: parseFloat(position.lng)},
                            title: 'Inicio del viaje',
                            animation: google.maps.Animation.DROP,
                            icon: 'assets/img/markerPosition.png'
                        });
                        currentMarkers.push(posMarker);
                    }
                    var endMarker = new google.maps.Marker({
                        position: {lat: parseFloat(viaje.destino_lat), lng: parseFloat(viaje.destino_lng)},
                        title: 'Destino del viaje',
                        animation: google.maps.Animation.DROP,
                        icon: 'assets/img/markerEnd.png'
                    });

                    currentMarkers.push(endMarker);

                    showMarkers();

                }
            });
        }
    });

    $('[data-map-theme]').click(function () {
        var targetTheme = $(this).attr('data-map-theme');
        var targetLi = $(this).closest('li');
        var targetText = $(this).text();
        var inverseContentMode = false;
        $('#map-theme-selection li').not(targetLi).removeClass('active');
        $('#map-theme-text').text(targetText);
        $(targetLi).addClass('active');
        switch (targetTheme) {
            case 'flat':
                mapDefault.setOptions({styles: flatMapStyles});
                break;
            case 'turquoise-water':
                mapDefault.setOptions({styles: turquoiseWaterStyles});
                break;
            case 'icy-blue':
                mapDefault.setOptions({styles: icyBlueStyles});
                break;
            case 'cobalt':
                mapDefault.setOptions({styles: cobaltStyles});
                inverseContentMode = true;
                break;
            case 'old-dry-mud':
                mapDefault.setOptions({styles: oldDryMudStyles});
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

var MapGoogle = function () {
    "use strict";
    return {
        //main function
        init: function () {
            handleGoogleMapSetting();
        }
    };
}();