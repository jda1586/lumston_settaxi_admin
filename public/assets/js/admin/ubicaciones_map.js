
var theMap;
var markers = [];

var handleGoogleMapSetting = function () {
    "use strict";

    function initialize() {
        var mapOptions = {
            zoom: 13, disableDefaultUI: true,
            center: new google.maps.LatLng(20.6659219, -103.3506052),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        theMap = new google.maps.Map(document.getElementById('routeMap'), mapOptions);
        loadLocations();
    }

    google.maps.event.addDomListener(window, 'load', initialize);
    $(window).resize(function () {
        google.maps.event.trigger(theMap, "resize");
    });
};

function loadLocations() {

    var formData = {
        operation: "getLocations"
    };
    $.ajax({
        url: "/locations",
        data: formData,
        type: "GET",
        success: function (response) {
            if (response.result === "ok") {
                $.each(response.locations, function () {
                    var item = $(this)[0];
                    map.addMarker(item, item.lat, item.lng, item.tipo_ubicacion_codigo, true, function () {

                    });
                });
            }
            else {

            }
        }
    });

}

var map = {
    addMarker: function (markerInfo, lat, lng, type, draggable, onDragCallback) {
        var theMarker = false;
        var markerContent = "<div id='content'><a href='/ubicaciones/" + markerInfo.id + "/" + markerInfo.titulo + "' target='_blank'><p style='font-weight:bold;'>" + markerInfo.titulo + "</p><p>" + markerInfo.direccion + "</p></a></div>";
        var infowindow = new google.maps.InfoWindow({
            content: ''
        });

        for (var i = 0; i < markers.length; i++) {
            if (markerInfo.titulo === markers[i].title) {
                theMarker = markers[i];
            }
        }

        if (theMarker !== false) {
            theMarker.setPosition(new google.maps.LatLng(parseFloat(lat), parseFloat(lng)));
        }
        else {
            var icon = new google.maps.MarkerImage("/assets/img/markers/locations/icon_" + type + ".png", null, null, null, new google.maps.Size(32, 32));
            var theMarker = new google.maps.Marker({
                position: {lat: parseFloat(lat), lng: parseFloat(lng)},
                title: markerInfo.titulo, icon: icon, draggable: draggable
            });

            //markers.push(theMarker);
            theMarker.setMap(theMap);
            google.maps.event.addListener(theMarker, 'dragend', function (event) {
                alert(this.getTitle());
                var markerLat = this.position.lat();
                var markerLng = this.position.lng();
                if (draggable) {
                    onDragCallback(markerLat, markerLng);
                }
            });
            google.maps.event.addListener(theMarker, 'click', function (event) {
                infowindow.setContent(markerContent);
                infowindow.open(theMap, theMarker);
            });

            markers.push(theMarker);
        }
    }
};

$(document).ready(function () {
    handleGoogleMapSetting();
});