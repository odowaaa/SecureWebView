/**
 * Front-page regional trend chart (temperature + anomaly over time).
 * The per-report/-hotspot charts on single templates use the SomCA Core
 * [somca_chart] shortcode's own script instead of this file.
 */
( function () {
	'use strict';

	function initFrontChart() {
		var canvas = document.getElementById( 'somca-front-chart' );
		if ( ! canvas || typeof Chart === 'undefined' ) {
			return;
		}
		var endpoint = canvas.getAttribute( 'data-endpoint' ) || ( window.SomcaChartData && SomcaChartData.reportsEndpoint );
		if ( ! endpoint ) {
			return;
		}

		fetch( endpoint )
			.then( function ( r ) { return r.json(); } )
			.then( function ( reports ) {
				if ( ! Array.isArray( reports ) || ! reports.length ) {
					canvas.closest( '.somca-chart-wrap' ).innerHTML = '<p style="text-align:center;color:var(--somca-text-muted);padding-top:2em;">No report data yet — check back after the first weekly collection cycle.</p>';
					return;
				}

				new Chart( canvas.getContext( '2d' ), {
					type: 'line',
					data: {
						labels: reports.map( function ( r ) { return r.period_end; } ),
						datasets: [
							{
								label: 'Avg. Temperature (°C)',
								data: reports.map( function ( r ) { return r.avg_temp_c; } ),
								borderColor: '#D32F2F',
								backgroundColor: 'rgba(211,47,47,0.12)',
								tension: 0.35,
								fill: true,
								pointRadius: 3,
							},
							{
								label: 'Rainfall (mm)',
								data: reports.map( function ( r ) { return r.rainfall_mm; } ),
								borderColor: '#4CAF50',
								backgroundColor: 'rgba(76,175,80,0.12)',
								tension: 0.35,
								fill: true,
								pointRadius: 3,
								yAxisID: 'y1',
							},
						],
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						plugins: { legend: { position: 'bottom' } },
						scales: {
							y: { title: { display: true, text: '°C' } },
							y1: { position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'mm' } },
						},
					},
				} );
			} )
			.catch( function () {} );
	}

	document.addEventListener( 'DOMContentLoaded', initFrontChart );
} )();
