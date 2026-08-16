/**
 * Minimal wp.media picker restricted to images, for settings-page fields
 * that store a single attachment ID (e.g. the welcome video poster).
 */
( function ( $ ) {
	'use strict';

	$( document ).on( 'click', '.somali-focus-image-select', function ( e ) {
		e.preventDefault();
		var $button = $( this );
		var $wrap = $button.closest( 'td' );
		var $input = $wrap.find( '.somali-focus-image-id' );
		var $preview = $wrap.find( '.somali-focus-image-preview' );
		var $img = $preview.find( 'img' );
		var $clear = $wrap.find( '.somali-focus-image-clear' );

		var frame = wp.media( {
			title: 'Select Image',
			library: { type: 'image' },
			multiple: false,
		} );

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			var thumb = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
			$input.val( attachment.id );
			$img.attr( 'src', thumb );
			$preview.show();
			$clear.show();
		} );

		frame.open();
	} );

	$( document ).on( 'click', '.somali-focus-image-clear', function ( e ) {
		e.preventDefault();
		var $button = $( this );
		var $wrap = $button.closest( 'td' );
		$wrap.find( '.somali-focus-image-id' ).val( '' );
		$wrap.find( '.somali-focus-image-preview' ).hide();
		$button.hide();
	} );
} )( jQuery );
