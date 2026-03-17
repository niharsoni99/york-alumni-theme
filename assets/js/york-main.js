/**
 * York Alumni Theme — Main JavaScript
 *
 * Responsibilities:
 *  1. Mobile nav toggle     — vanilla JS, no jQuery dependency
 *  2. Dropdown aria-expanded update on hover/focus
 *  3. Testimonials slider   — Slick (responsive)
 *  4. Video modal handler   — play on thumb click, stop on close
 *
 * No jQuery required for nav toggle (requirement: vanilla JS only).
 * jQuery used only for Slick slider initialisation.
 *
 * @package YorkAlumni
 */

/* ─────────────────────────────────────────────────────────────
   1. MOBILE NAV TOGGLE (vanilla JS only — no jQuery)
───────────────────────────────────────────────────────────── */
( function () {
	'use strict';

	var toggle = document.querySelector( '.nav-toggle' );
	var nav    = document.querySelector( '.main-navigation' );

	if ( ! toggle || ! nav ) {
		return;
	}

	/**
	 * Open or close the mobile navigation.
	 */
	function toggleNav() {
		var isOpen = nav.classList.contains( 'is-open' );

		nav.classList.toggle( 'is-open', ! isOpen );
		toggle.classList.toggle( 'is-active', ! isOpen );
		toggle.setAttribute( 'aria-expanded', String( ! isOpen ) );
	}

	toggle.addEventListener( 'click', toggleNav );

	// Close nav when pressing Escape key.
	document.addEventListener( 'keydown', function ( e ) {
		if ( 'Escape' === e.key && nav.classList.contains( 'is-open' ) ) {
			toggleNav();
			toggle.focus();
		}
	} );

	// Close nav when clicking outside of it.
	document.addEventListener( 'click', function ( e ) {
		if (
			nav.classList.contains( 'is-open' ) &&
			! nav.contains( e.target ) &&
			! toggle.contains( e.target )
		) {
			toggleNav();
		}
	} );

} () );

/* ─────────────────────────────────────────────────────────────
   2. DROPDOWN ARIA-EXPANDED (vanilla JS)
   Update aria-expanded when hovering/focusing dropdown items.
───────────────────────────────────────────────────────────── */
( function () {
	'use strict';

	var dropdowns = document.querySelectorAll( '.menu-item-has-children > a' );

	dropdowns.forEach( function ( link ) {
		var parent = link.parentElement;

		function open()  { link.setAttribute( 'aria-expanded', 'true' ); }
		function close() { link.setAttribute( 'aria-expanded', 'false' ); }

		parent.addEventListener( 'mouseenter', open );
		parent.addEventListener( 'mouseleave', close );
		parent.addEventListener( 'focusin',    open );
		parent.addEventListener( 'focusout',   function ( e ) {
			if ( ! parent.contains( e.relatedTarget ) ) {
				close();
			}
		} );
	} );

} () );

/* ─────────────────────────────────────────────────────────────
   3. TESTIMONIALS SLIDER (Slick — responsive)
   Desktop (>767px): slider with dots.
   Mobile (<=767px): destroy slider, show stacked.
───────────────────────────────────────────────────────────── */
( function ( $ ) {
	'use strict';

	var $slider = $( '.js-testimonials-slider' );

	if ( ! $slider.length ) {
		return;
	}

	/**
	 * Initialise or destroy Slick based on viewport width.
	 */
	function slickResponsive() {
		if ( $( window ).width() > 767 ) {
			if ( ! $slider.hasClass( 'slick-initialized' ) ) {
				$slider.slick( {
					slidesToShow:   1,
					slidesToScroll: 1,
					autoplay:       true,
					autoplaySpeed:  4000,
					dots:           true,
					arrows:         false,
					adaptiveHeight: true,
				} );
			}
		} else {
			if ( $slider.hasClass( 'slick-initialized' ) ) {
				$slider.slick( 'unslick' );
			}
		}
	}

	$( document ).ready( slickResponsive );
	$( window ).on( 'resize', slickResponsive );

} ( jQuery ) );

/* ─────────────────────────────────────────────────────────────
   4. VIDEO MODAL HANDLER
   - Click thumbnail → set iframe src from data-src → show iframe
   - Bootstrap modal hidden event → clear src → restore thumbnail
───────────────────────────────────────────────────────────── */
( function () {
	'use strict';

	// Handle thumbnail click to start video.
	document.querySelectorAll( '.js-video-thumb' ).forEach( function ( thumb ) {
		thumb.addEventListener( 'click', function () {
			var modalId = thumb.getAttribute( 'data-modal' );
			var modal   = document.getElementById( modalId );

			if ( ! modal ) {
				return;
			}

			var iframe = modal.querySelector( '.js-video-iframe' );

			if ( ! iframe ) {
				return;
			}

			// Set src from data-src to start autoplay.
			iframe.src = ( iframe.getAttribute( 'data-src' ) || '' ) + '?autoplay=1&mute=0';
			iframe.style.display = 'block';
			thumb.style.display  = 'none';
		} );
	} );

	// Stop video and reset thumbnail when modal closes.
	document.querySelectorAll( '.alumni-popup' ).forEach( function ( modal ) {
		modal.addEventListener( 'hidden.bs.modal', function () {
			var iframe = modal.querySelector( '.js-video-iframe' );
			var thumb  = modal.querySelector( '.js-video-thumb' );

			if ( iframe ) {
				iframe.src           = '';
				iframe.style.display = 'none';
			}

			if ( thumb ) {
				thumb.style.display = 'block';
			}
		} );
	} );

} () );
