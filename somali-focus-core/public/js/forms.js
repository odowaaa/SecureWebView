/**
 * Progressive enhancement only: disable the submit button after click to
 * discourage double-submits. Forms work fine with JS disabled.
 */
( function () {
	'use strict';

	document.addEventListener( 'submit', function ( e ) {
		if ( ! e.target || ! e.target.classList || ! e.target.classList.contains( 'sf-form' ) ) {
			return;
		}
		var button = e.target.querySelector( '.sf-btn--submit, button[type="submit"]' );
		if ( button ) {
			window.setTimeout( function () {
				button.setAttribute( 'disabled', 'disabled' );
			}, 0 );
		}
	} );
} )();
