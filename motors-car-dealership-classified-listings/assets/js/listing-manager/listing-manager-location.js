let map, marker, autocomplete, lastLatLng, lastTimeout, geocoder;

function initMap() {
    const ny = { lat: 40.712776, lng: -74.005974 };
    let initialLocation = ny;
    let locationInput = document.getElementById("stm_car_location");
    if (locationInput && locationInput.value) {

        geocoder = new google.maps.Geocoder();
        geocoder.geocode({ address: locationInput.value }, function (results, status) {
            if (status === "OK" && results[0]) {
                initialLocation = {
                    lat: results[0].geometry.location.lat(),
                    lng: results[0].geometry.location.lng()
                };

                initMapWithLocation(initialLocation);
            } else {

                initMapWithLocation(ny);
            }
        });
    } else {

        initMapWithLocation(ny);
    }
}

function initMapWithLocation(location) {
    map = new google.maps.Map(document.getElementById("mvl-listing-manager-map"), {
        center: location,
        zoom: 10,
        fullscreenControl: false,
        streetViewControl: false,
        panControl: false,
        mapTypeControl: false,
        cameraControl: false,
        zoomControl: false,
        styles: [
            {
                "featureType": "all",
                "elementType": "geometry",
                "stylers": [
                    { "color": "#eaeaea" }
                ]
            },
            {
                "featureType": "all",
                "elementType": "labels.text.fill",
                "stylers": [
                    { "color": "#444444" }
                ]
            },
            {
                "featureType": "all",
                "elementType": "labels.text.stroke",
                "stylers": [
                    { "color": "#ffffff" },
                    { "weight": 2 }
                ]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry",
                "stylers": [
                    { "color": "#cccccc" }
                ]
            },
            {
                "featureType": "administrative.country",
                "elementType": "labels.text.fill",
                "stylers": [
                    { "color": "#444444" }
                ]
            },
            {
                "featureType": "administrative.locality",
                "elementType": "labels.text.fill",
                "stylers": [
                    { "color": "#444444" }
                ]
            },
            {
                "featureType": "landscape",
                "elementType": "geometry",
                "stylers": [
                    { "color": "#eaeaea" }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [
                    { "color": "#eaeaea" }
                ]
            },

            {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [
                    { "color": "#ffffff" }
                ]
            },

            {
                "featureType": "road.arterial",
                "elementType": "geometry",
                "stylers": [
                    { "color": "#dddddd" }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [
                    { "color": "#cccccc" }
                ]
            },
            {
                "featureType": "transit",
                "elementType": "geometry",
                "stylers": [
                    { "color": "#eaeaea" }
                ]
            },
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [
                    { "color": "#b7e0ef" }
                ]
            }
        ]
    });

    geocoder = new google.maps.Geocoder();

    map.addListener('mousedown', function (e) {
        const latlng = { lat: parseFloat(e.latLng.lat()), lng: parseFloat(e.latLng.lng()) };
        if (!lastLatLng || lastLatLng.lat !== latlng.lat || lastLatLng.lng !== latlng.lng) {
            lastLatLng = latlng;
            updateLocation(latlng);
        }
    });

    marker = new google.maps.Marker({
        position: location,
        map: map,
        icon: {
            url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
            scaledSize: new google.maps.Size(40, 40)
        }
    });

    const input = document.getElementById("stm_car_location");
    autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo("bounds", map);

    autocomplete.addListener("place_changed", function () {
        const place = autocomplete.getPlace();

        if (!place.geometry) return;

        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(14);
        }
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);
    });

    document.querySelector(".mvl-listing-manager-map-zoom-in").onclick = function () {
        map.setZoom(map.getZoom() + 1);
    };
    document.querySelector(".mvl-listing-manager-map-zoom-out").onclick = function () {
        map.setZoom(map.getZoom() - 1);
    };

    function updateLocation(latlng) {
        geocoder.geocode({ location: latlng }, function (results, status) {
            if (status === "OK" && results[0]) {
                marker.setPosition(latlng);
                document.getElementById("stm_car_location").value = results[0].formatted_address;
            } else {
                document.getElementById("stm_car_location").value = "";
            }
        });
    }
}