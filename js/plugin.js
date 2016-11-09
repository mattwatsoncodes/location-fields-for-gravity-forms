jQuery( document ).ready( function( $ ) {

	if ( $('.lffgf-map-container').length > 0 ) {

		/**
		 * Setup the map lookup
		 */
		var map    = null;
		var marker = null;

		initialize_map( $('.lffgf-map-container') );

		function initialize_map( map_instance ) {
			var search_input = map_instance.find( '.lffgf-search' );
			var map_canvas   = map_instance.find( '.lffgf-map' );
			var latitude     = map_instance.find( '.lffgf-latitude' );
			var longitude    = map_instance.find( '.lffgf-longitude' );
			var latLng       = new google.maps.LatLng( 54.800685, -4.130859 );
			var zoom         = 5;

			// If we have saved values, let's set the position and zoom level
			if ( latitude.val().length > 0 && longitude.val().length > 0 ) {
				latLng = new google.maps.LatLng( latitude.val(), longitude.val() );
				zoom = 17;
			}

			// Map
			var map_options = {
				center: latLng,
				zoom: zoom
			};
			map = new google.maps.Map( map_canvas[0], map_options );

			// Marker
			var marker_options = {
				map: map,
				draggable: true,
				title: 'Drag pin to set the exact location'
			};
			marker = new google.maps.Marker( marker_options );

			if ( latitude.val().length > 0 && longitude.val().length > 0 ) {
				marker.setPosition( latLng );
			}

			// Search
			var autocomplete = new google.maps.places.Autocomplete( search_input[0] );
			autocomplete.bindTo( 'bounds', map );

			google.maps.event.addListener( autocomplete, 'place_changed', function() {
				var place = autocomplete.getPlace();
				if ( ! place.geometry ) {
					return;
				}

				if ( place.geometry.viewport ) {
					map.fitBounds( place.geometry.viewport );
				} else {
					map.setCenter( place.geometry.location );
					map.setZoom( 17 );
				}

				marker.setPosition( place.geometry.location );

				latitude.val( place.geometry.location.lat() );
				longitude.val( place.geometry.location.lng() );
			});

			$( search_input ).keypress( function( event ) {
				if ( 13 === event.keyCode ) {
					event.preventDefault();
				}
			});

			// Allow marker to be repositioned
			google.maps.event.addListener( marker, 'drag', function() {
				latitude.val( marker.getPosition().lat() );
				longitude.val( marker.getPosition().lng() );
			});
		}

		/**
		 * Populate map with Postcode search
		 */

		if ( $('.lffgf-alternate-input').length > 0 ) {
			var alternate_input_selector = $('.lffgf-alternate-input').val();

			if ( '' != alternate_input_selector ) {
				$( alternate_input_selector ).blur( function() {
					var address_lookup = 'https://maps.googleapis.com/maps/api/geocode/json?address=';
					var key            = google_api_key;
					var url            = address_lookup + $( alternate_input_selector ).val() + '&key=' + key;

					$.getJSON( url, function( data ) {
						if ( 'OK' === data.status ) {
							var latitude  = data.results[0].geometry.location.lat;
							var longitude = data.results[0].geometry.location.lng;
							var latitude_input  = $( '.lffgf-latitude' );
							var longitude_input = $( '.lffgf-longitude' );
							latitude_input.val( latitude );
							longitude_input.val( longitude );
							var latLng = new google.maps.LatLng( latitude, longitude );
							marker.setPosition( latLng );
							map.setCenter( marker.getPosition() );
							map.setZoom( 17 );
						}
					});
				});
			}
		}

		/**
		 * Check for postcode update if it is populated dynamically
		 */
		if ( $('.lffgf-alternate-input').length > 0 ) {
			var alternate_input_selector = $('.lffgf-alternate-input').val();
			var alternate_input_value    = $( alternate_input_selector ).val();

			function alternate_input_watch(){
		        if ( $( alternate_input_selector ).val() !== alternate_input_value ) {
					alternate_input_value = $( alternate_input_selector ).val();
					$( alternate_input_selector ).trigger('blur');
				}
		        setTimeout( alternate_input_watch, 1000 );
		    };
		    alternate_input_watch();
		}
	}

});
