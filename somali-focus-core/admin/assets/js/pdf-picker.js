/**
 * Minimal wp.media picker restricted to PDF files for the Research PDF field.
 */
( function ( $ ) {
	'use strict';

	$( document ).on( 'click', '.somali-focus-pdf-select', function ( e ) {
		e.preventDefault();
		var $button = $( this );
		var $wrap = $button.closest( 'td' );
		var $input = $wrap.find( '.somali-focus-pdf-id' );
		var $filename = $wrap.find( '.somali-focus-pdf-filename' );
		var $clear = $wrap.find( '.somali-focus-pdf-clear' );

		var frame = wp.media( {
			title: 'Select Research PDF',
			library: { type: 'application/pdf' },
			multiple: false,
		} );

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			$input.val( attachment.id );
			$filename.text( attachment.filename || attachment.url );
			$clear.show();
		} );

		frame.open();
	} );

	$( document ).on( 'click', '.somali-focus-pdf-clear', function ( e ) {
		e.preventDefault();
		var $button = $( this );
		var $wrap = $button.closest( 'td' );
		$wrap.find( '.somali-focus-pdf-id' ).val( '' );
		$wrap.find( '.somali-focus-pdf-filename' ).text( 'No file selected.' );
		$button.hide();
	} );
} )( jQuery );
