(function( $ ) {
	'use strict';

	var coordenadas = [42.75, -7.883333];
	var leafletMap = L.map('laulo-map').setView(coordenadas, 13);
	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png?{foo}', {foo: 'bar', attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'}).addTo(leafletMap);
	var marker = L.marker(coordenadas, {draggable:'true'}).addTo(leafletMap);

	// Get coordinates from form
	var latitude = $( '.pw-map-latitude' ).val();
	var longitude = $( '.pw-map-longitude' ).val();

	if ( latitude.length > 0 && longitude.length > 0 ) {
		coordenadas = [latitude, longitude];
		marker.setLatLng(new L.LatLng(latitude, longitude),{draggable:'true'});
		leafletMap.panTo(coordenadas);
	}

	marker.on('dragend', function(event){
	    var marker = event.target;
	    var position = marker.getLatLng();
	    marker.setLatLng(new L.LatLng(position.lat, position.lng),{draggable:'true'});
	    leafletMap.panTo(new L.LatLng(position.lat, position.lng))
		$( '.pw-map-latitude' ).val( position.lat );
		$( '.pw-map-longitude' ).val( position.lng );
	  });


})( jQuery );
