/**
 * Front-page live hotspot map and single-hotspot focused map.
 * Consumes the SomCA Core REST API (/wp-json/somca/v1/hotspots).
 */
( function () {
	'use strict';

	var RISK_COLORS = {
		low: '#4CAF50',
		moderate: '#F2B705',
		high: '#E9622B',
		critical: '#D32F2F',
	};

	function popupHtml( h ) {
		return '<div class="somca-map-popup">' +
			( h.thumbnail ? '<img src="' + h.thumbnail + '" alt="" />' : '' ) +
			'<strong>' + h.title + '</strong>' +
			'<p>' + ( h.excerpt || '' ) + '</p>' +
			'<span class="somca-risk-badge somca-risk-' + h.risk + '">' + h.risk + '</span> ' +
			'<a href="' + h.url + '">Details &rarr;</a>' +
			'</div>';
	}

	function initFrontMap() {
		var el = document.getElementById( 'somca-front-map' );
		if ( ! el || typeof L === 'undefined' ) {
			return;
		}
		var endpoint = el.getAttribute( 'data-endpoint' ) || ( window.SomcaData && SomcaData.hotspotsEndpoint );
		if ( ! endpoint ) {
			return;
		}

		var map = L.map( el ).setView( [ 5.1521, 46.1996 ], 5 );
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
					L.circleMarker( [ h.lat, h.lng ], {
						radius: 9, color: color, fillColor: color, fillOpacity: 0.85, weight: 2,
					} ).addTo( map ).bindPopup( popupHtml( h ) );
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

	function initSingleMap() {
		var el = document.getElementById( 'somca-hotspot-single-map' );
		if ( ! el || typeof L === 'undefined' ) {
			return;
		}
		var lat = parseFloat( el.getAttribute( 'data-lat' ) );
		var lng = parseFloat( el.getAttribute( 'data-lng' ) );
		var title = el.getAttribute( 'data-title' ) || '';

		if ( isNaN( lat ) || isNaN( lng ) ) {
			return;
		}

		var map = L.map( el ).setView( [ lat, lng ], 9 );
		L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: '&copy; OpenStreetMap contributors',
		} ).addTo( map );
		L.marker( [ lat, lng ] ).addTo( map ).bindPopup( '<strong>' + title + '</strong>' ).openPopup();
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		initFrontMap();
		initSingleMap();
	} );
} )();
