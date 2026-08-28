/**
 * Footer "Weekly Climate Briefing" form — AJAX submit to SomCA Core.
 * New in theme 2.0.0.
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var form = document.getElementById( 'somca-subscribe-form' );
		var message = document.getElementById( 'somca-subscribe-message' );

		if ( ! form || typeof SomcaSubscribe === 'undefined' ) {
			return;
		}

		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();

			var button = form.querySelector( 'button[type="submit"]' );
			var emailField = form.querySelector( 'input[name="email"]' );
			var honeypot = form.querySelector( 'input[name="somca_website"]' );

			if ( ! emailField || ! emailField.value ) {
				return;
			}

			button.disabled = true;
			message.textContent = '';
			message.className = 'somca-subscribe-message';

			var body = new URLSearchParams();
			body.set( 'action', 'somca_subscribe' );
			body.set( 'nonce', SomcaSubscribe.nonce );
			body.set( 'email', emailField.value );
			body.set( 'somca_website', honeypot ? honeypot.value : '' );

			fetch( SomcaSubscribe.ajaxUrl, {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: body.toString(),
			} )
				.then( function ( r ) { return r.json(); } )
				.then( function ( res ) {
					button.disabled = false;
					var msg = res && res.data && res.data.message ? res.data.message : '';
					if ( res && res.success ) {
						message.textContent = msg || 'Subscribed!';
						message.classList.add( 'is-success' );
						form.reset();
					} else {
						message.textContent = msg || 'Something went wrong. Please try again.';
						message.classList.add( 'is-error' );
					}
				} )
				.catch( function () {
					button.disabled = false;
					message.textContent = 'Something went wrong. Please try again.';
					message.classList.add( 'is-error' );
				} );
		} );
	} );
} )();
