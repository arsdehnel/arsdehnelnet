

( function( $ ) {
	var body    = $( 'body' ),
		_window = $( window );

	$('#category-all li').each(function(){
		var $that = $(this);
		
		if( $that.text().trim() == 'Uncategorized' ){
			$that.remove();
		}else{
			$that.find(':checkbox').attr('type','radio');
		}
		
	})
	
	

} )( jQuery );
