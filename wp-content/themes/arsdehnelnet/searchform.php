<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text">Search</span>
		<input type="search" class="search-field" placeholder="Search &hellip;" value="<?php echo get_search_query(); ?>" name="s" title="Search for" />
	</label>
	<?php
		if( is_search() ):
			?>
			<button type="button" class="search-submit">search</button>
			<?php
		else:
			?>
			<button type="button" class="search-cancel">cancel</button>
			<?php
		endif;
	?>
</form>
