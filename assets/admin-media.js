/* global jQuery, wp */
( function ( $ ) {
	// Works for the project meta box (inline display styles) and the options page (hidden attribute).
	const setImage = ( $field, url ) => {
		const $img = $field.find( '.vincent-media-preview' ).attr( 'src', url );
		$img.prop( 'hidden', ! url ).toggle( !! url );
		$field.find( '.vincent-media-empty' ).prop( 'hidden', !! url );
	};

	$( document ).on( 'click', '.vincent-media-select', function ( e ) {
		e.preventDefault();
		const $field = $( this ).closest( '.vincent-media-field' );
		const frame = wp.media( {
			title: 'Select image',
			library: { type: 'image' },
			multiple: false,
		} );

		frame.on( 'select', function () {
			const attachment = frame.state().get( 'selection' ).first().toJSON();
			const url = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
			$field.find( 'input[type=hidden]' ).val( attachment.id );
			setImage( $field, url );
		} );

		frame.open();
	} );

	$( document ).on( 'click', '.vincent-media-remove', function ( e ) {
		e.preventDefault();
		const $field = $( this ).closest( '.vincent-media-field' );
		$field.find( 'input[type=hidden]' ).val( '' );
		setImage( $field, '' );
	} );
} )( jQuery );
