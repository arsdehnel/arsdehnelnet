/**
 * Theme functions file
 *
 * Contains handlers for navigation, accessibility, header sizing
 * footer widgets and Featured Content slider
 *
 */
( function( $ ) {
	var body    = $( 'body' ),
		_window = $( window );

	/*
	 * Makes "skip to content" link work correctly in IE9 and Chrome for better
	 * accessibility.
	 *
	 * @link http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
	 */
	_window.on( 'hashchange.twentyfourteen', function() {
		var element = document.getElementById( location.hash.substring( 1 ) );

		if ( element ) {
			if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) {
				element.tabIndex = -1;
			}

			element.focus();

			// Repositions the window on jump-to-anchor to account for header height.
			window.scrollBy( 0, -80 );
		}
	} );

	$( function() {
		// Search toggle.
		$( '.search-toggle' ).on( 'click.twentyfourteen', function( event ) {
			var that    = $( this ),
				wrapper = $( '.search-box-wrapper' );
				
			if( that.is( '.active' ) ){
				if( ( $('.search-field').text() ).length > 0 ){
					$('.search-form').submit();
				}else{
					that.removeClass('active');
					wrapper.addClass('hide');					
				}
			}else{
				that.addClass('active');
				wrapper.removeClass('hide').find('.search-field').focus();
			}
			
			return false;
				
		} );
		$( '.search-cancel' ).on( 'click.twetyfourteen', function( event ){
			var that    = $( '.search-toggle' ),
				wrapper = $( '.search-box-wrapper' );
			that.removeClass('active');
			wrapper.addClass('hide');
			return false;
		} );
	} );

} )( jQuery );
