/**
 * Renders [somca_chart] shortcode canvases: a temperature or rainfall trend
 * line chart built from SomCA climate report data.
 */
( function () {
	'use strict';

	function initChart( canvas ) {
		var endpoint = canvas.getAttribute( 'data-endpoint' );
		var metric = canvas.getAttribute( 'data-metric' ) || 'temp';
		if ( ! endpoint || typeof Chart === 'undefined' ) {
			return;
		}

		fetch( endpoint )
			.then( function ( r ) { return r.json(); } )
			.then( function ( reports ) {
				if ( ! Array.isArray( reports ) ) {
					return;
				}

				var labels = reports.map( function ( r ) { return r.period_end; } );
				var isTemp = 'temp' === metric;

				var dataset = {
					label: isTemp ? 'Avg. Temperature (°C)' : 'Rainfall (mm)',
					data: reports.map( function ( r ) { return isTemp ? r.avg_temp_c : r.rainfall_mm; } ),
					borderColor: isTemp ? '#D32F2F' : '#1a1a1a',
					backgroundColor: isTemp ? 'rgba(211,47,47,0.12)' : 'rgba(76,175,80,0.15)',
					tension: 0.35,
					fill: true,
					pointRadius: 3,
				};

				var anomalyDataset = {
					label: isTemp ? 'Temp. Anomaly (°C)' : 'Rainfall Anomaly (%)',
					data: reports.map( function ( r ) { return isTemp ? r.temp_anomaly_c : r.rainfall_anomaly_pct; } ),
					borderColor: '#4CAF50',
					backgroundColor: 'rgba(76,175,80,0.1)',
					borderDash: [ 6, 4 ],
					tension: 0.35,
					fill: false,
					pointRadius: 2,
				};

				new Chart( canvas.getContext( '2d' ), {
					type: 'line',
					data: { labels: labels, datasets: [ dataset, anomalyDataset ] },
					options: {
						responsive: true,
						maintainAspectRatio: false,
						plugins: { legend: { position: 'bottom' } },
						scales: { x: { ticks: { maxRotation: 45, minRotation: 0 } } },
					},
				} );
			} )
			.catch( function () {} );
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		document.querySelectorAll( '.somca-chart' ).forEach( initChart );
	} );
} )();
