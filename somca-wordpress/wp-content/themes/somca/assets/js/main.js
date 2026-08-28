/**
 * SomCA theme: mobile nav toggle + smooth-scroll for on-page anchors.
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var toggle = document.getElementById( 'somca-menu-toggle' );
		var nav = document.getElementById( 'site-navigation' );

		if ( toggle && nav ) {
			toggle.addEventListener( 'click', function () {
				var isOpen = nav.classList.toggle( 'is-open' );
				toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
			} );
		}

		document.querySelectorAll( 'a[href^="#"]' ).forEach( function ( link ) {
			link.addEventListener( 'click', function ( e ) {
				var id = link.getAttribute( 'href' ).slice( 1 );
				var target = id ? document.getElementById( id ) : null;
				if ( target ) {
					e.preventDefault();
					target.scrollIntoView( { behavior: 'smooth', block: 'start' } );
					if ( nav ) {
						nav.classList.remove( 'is-open' );
					}
				}
			} );
		} );
	} );
} )();
