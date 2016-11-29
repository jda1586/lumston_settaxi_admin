var theMap;
var markers = [];

var handleGoogleMapSetting = function () {
    "use strict";

    function initialize() {
        var mapOptions = {
            zoom: 16, disableDefaultUI: true,
            center: new google.maps.LatLng(20.630999, -103.4340112),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        theMap = new google.maps.Map(document.getElementById('routeMap'), mapOptions);
        realTimeFunctions();
    }

    google.maps.event.addDomListener(window, 'load', initialize);
    $(window).resize(function () {
        google.maps.event.trigger(theMap, "resize");
    });
};

var map = {
    addMarker: function (markerId, lat, lng, type, cab) {
        var theMarker = false;
        for (var i = 0; i < markers.length; i++) {
            if (markerId === markers[i].title) {
                theMarker = markers[i];
            }
        }
        if (cab.activo === 0 || cab.activo === "0") {
            type = "taxiPinInactivo";
        }
        else if (cab.ocupado === 1 || cab.ocupado === "1") {
            type = "taxiPinDisponible";
        }
        var icon = new google.maps.MarkerImage("/assets/img/markers/" + type + ".png", null, null, null, new google.maps.Size(32, 32));

        if (theMarker !== false) {
            theMarker.setPosition(new google.maps.LatLng(parseFloat(lat), parseFloat(lng)));
            theMarker.setIcon(icon);
        }
        else {
            var theMarker = new MarkerWithLabel({
                position: {lat: parseFloat(lat), lng: parseFloat(lng)},
                title: markerId, icon: icon,
                labelContent: "<div class='arrow'></div><div class='inner'>" + cab.numero_economico + "</div>",
                labelAnchor: new google.maps.Point(20, 70),
                labelClass: "labels", // the CSS class for the label
                labelStyle: {opacity: 0.75},
            });

            theMarker.setMap(theMap);
            markers.push(theMarker);
        }
    }
};

function taxiSync() {
    var formData = {
    };
    $.ajax({
        url: "/realtime",
        data: formData,
        type: "POST",
        success: function (response) {
            if (response.result === "ok") {
                $.each(response.cabs, function () {
                    var cab = $(this)[0];
                    map.addMarker("cab_" + cab.id, cab.posicion_lat, cab.posicion_lng, "taxiPin", cab);
                });
            }
            else {

            }
            setTimeout(function () {
                taxiSync();
            }, 10000);
        }
    });

}
function realTimeFunctions() {
    taxiSync();
}

$(document).ready(function () {
    App.init();
    handleGoogleMapSetting();
});