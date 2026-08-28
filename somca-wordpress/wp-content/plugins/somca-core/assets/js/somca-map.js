/**
 * Renders the [somca_map] shortcode: a Leaflet map plotting every published
 * Hotspot, colour-coded by risk level, pulled from the SomCA REST API.
 */
( function () {
	'use strict';

	var RISK_COLORS = {
		low: '#4CAF50',
		moderate: '#F2B705',
		high: '#E9622B',
		critical: '#D32F2F',
	};

	function initMap( el ) {
		var endpoint = el.getAttribute( 'data-endpoint' );
		if ( ! endpoint || typeof L === 'undefined' ) {
			return;
		}

		var map = L.map( el ).setView( [ 5.1521, 46.1996 ], 5 ); // Somalia centroid.

		L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: '&copy; OpenStreetMap contributors',
		} ).addTo( map );

		fetch( endpoint )
			.then( function ( r ) { return r.json(); } )
			.then( function ( hotspots ) {
				if ( ! Array.isArray( hotspots ) || ! hotspots.length ) {
					return;
				}
				var bounds = [];
				hotspots.forEach( function ( h ) {
					var color = RISK_COLORS[ h.risk ] || RISK_COLORS.moderate;
					var marker = L.circleMarker( [ h.lat, h.lng ], {
						radius: 9,
						color: color,
						fillColor: color,
						fillOpacity: 0.85,
						weight: 2,
					} ).addTo( map );

					var popup = '<div class="somca-map-popup">' +
						( h.thumbnail ? '<img src="' + h.thumbnail + '" alt="" />' : '' ) +
						'<strong>' + h.title + '</strong>' +
						'<p>' + ( h.excerpt || '' ) + '</p>' +
						'<span class="somca-risk-badge somca-risk-' + h.risk + '">' + h.risk + '</span> ' +
						'<a href="' + h.url + '">Details &rarr;</a>' +
						'</div>';

					marker.bindPopup( popup );
					bounds.push( [ h.lat, h.lng ] );
				} );

				if ( bounds.length > 1 ) {
					map.fitBounds( bounds, { padding: [ 30, 30 ] } );
				} else if ( bounds.length === 1 ) {
					map.setView( bounds[ 0 ], 8 );
				}
			} )
			.catch( function () {} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		document.querySelectorAll( '.somca-hotspot-map' ).forEach( initMap );
	} );
} )();
