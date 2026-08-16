/**
 * Somali Focus — front-end interactions. No dependencies.
 */
( function () {
	'use strict';

	/* Sticky header shrink on scroll. */
	var header = document.querySelector( '[data-sf-header]' );
	if ( header ) {
		var onScroll = function () {
			if ( window.scrollY > 12 ) {
				header.classList.add( 'is-scrolled' );
			} else {
				header.classList.remove( 'is-scrolled' );
			}
		};
		onScroll();
		window.addEventListener( 'scroll', onScroll, { passive: true } );
	}

	/* Desktop dropdown submenus: click/tap/keyboard toggle, since :hover
	   alone is unreachable on touch devices and via keyboard beyond the
	   parent link itself. */
	var submenuToggles = document.querySelectorAll( '.primary-menu .submenu-toggle' );
	submenuToggles.forEach( function ( toggle ) {
		toggle.addEventListener( 'click', function () {
			var isOpen = toggle.getAttribute( 'aria-expanded' ) === 'true';

			submenuToggles.forEach( function ( other ) {
				if ( other !== toggle ) {
					other.setAttribute( 'aria-expanded', 'false' );
				}
			} );

			toggle.setAttribute( 'aria-expanded', isOpen ? 'false' : 'true' );
		} );
	} );

	document.addEventListener( 'click', function ( e ) {
		if ( ! e.target.closest( '.primary-menu .menu-item-has-children' ) ) {
			submenuToggles.forEach( function ( toggle ) {
				toggle.setAttribute( 'aria-expanded', 'false' );
			} );
		}
	} );

	document.addEventListener( 'keydown', function ( e ) {
		if ( 'Escape' === e.key ) {
			submenuToggles.forEach( function ( toggle ) {
				toggle.setAttribute( 'aria-expanded', 'false' );
			} );
		}
	} );

	/* Mobile navigation toggle. */
	var menuToggle = document.querySelector( '[data-sf-menu-toggle]' );
	var mobileNav = document.querySelector( '[data-sf-mobile-nav]' );
	if ( menuToggle && mobileNav ) {
		var closeMenu = function () {
			menuToggle.setAttribute( 'aria-expanded', 'false' );
			mobileNav.removeAttribute( 'data-open' );
			mobileNav.hidden = true;
			document.body.style.overflow = '';
		};
		var openMenu = function () {
			menuToggle.setAttribute( 'aria-expanded', 'true' );
			mobileNav.hidden = false;
			// Force layout so the transform transition runs on open.
			void mobileNav.offsetWidth;
			mobileNav.setAttribute( 'data-open', '' );
			document.body.style.overflow = 'hidden';
		};

		menuToggle.addEventListener( 'click', function () {
			var isOpen = menuToggle.getAttribute( 'aria-expanded' ) === 'true';
			if ( isOpen ) {
				closeMenu();
			} else {
				openMenu();
			}
		} );

		mobileNav.addEventListener( 'click', function ( e ) {
			if ( e.target.closest( 'a' ) ) {
				closeMenu();
			}
		} );

		document.addEventListener( 'keydown', function ( e ) {
			if ( 'Escape' === e.key ) {
				closeMenu();
			}
		} );
	}

	/* Scroll-reveal + counters via a single IntersectionObserver. */
	var revealEls = document.querySelectorAll( '.sf-reveal' );
	var counterEls = document.querySelectorAll( '[data-sf-counter]' );

	var animateCounter = function ( el ) {
		var target = parseFloat( el.getAttribute( 'data-sf-counter' ) );
		var suffix = el.getAttribute( 'data-sf-suffix' ) || '';
		if ( isNaN( target ) ) {
			return;
		}
		var isInt = target % 1 === 0;
		var duration = 1400;
		var start = null;

		var step = function ( timestamp ) {
			if ( ! start ) {
				start = timestamp;
			}
			var progress = Math.min( ( timestamp - start ) / duration, 1 );
			var eased = 1 - Math.pow( 1 - progress, 3 );
			var current = target * eased;
			el.textContent = ( isInt ? Math.round( current ) : current.toFixed( 1 ) ) + suffix;
			if ( progress < 1 ) {
				window.requestAnimationFrame( step );
			}
		};
		window.requestAnimationFrame( step );
	};

	if ( 'IntersectionObserver' in window ) {
		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( ! entry.isIntersecting ) {
						return;
					}
					entry.target.classList.add( 'is-visible' );
					if ( entry.target.hasAttribute( 'data-sf-counter' ) ) {
						animateCounter( entry.target );
					}
					observer.unobserve( entry.target );
				} );
			},
			{ threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
		);

		revealEls.forEach( function ( el ) { observer.observe( el ); } );
		counterEls.forEach( function ( el ) { observer.observe( el ); } );
	} else {
		revealEls.forEach( function ( el ) { el.classList.add( 'is-visible' ); } );
		counterEls.forEach( function ( el ) { animateCounter( el ); } );
	}

	/* Pre-select the "Service Required" dropdown on landing pages that embed
	   the general service request form for a specific service. */
	document.querySelectorAll( '[data-sf-preselect-service]' ).forEach( function ( wrap ) {
		var value = wrap.getAttribute( 'data-sf-preselect-service' );
		var select = wrap.querySelector( 'select[name="service"]' );
		if ( select && value ) {
			select.value = value;
		}
	} );

	/* Welcome-video facade: no video-platform iframe/JS loads until the
	   visitor actually presses play. */
	document.querySelectorAll( '[data-sf-video-facade]' ).forEach( function ( facade ) {
		var play = function () {
			facade.innerHTML = facade.getAttribute( 'data-embed' );
			facade.classList.add( 'is-playing' );
			facade.removeAttribute( 'role' );
			facade.removeAttribute( 'tabindex' );
			facade.removeAttribute( 'aria-label' );
		};
		facade.addEventListener( 'click', play );
		facade.addEventListener( 'keydown', function ( e ) {
			if ( 'Enter' === e.key || ' ' === e.key ) {
				e.preventDefault();
				play();
			}
		} );
	} );
} )();
