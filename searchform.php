<?php do_action( 'bp_before_blog_search_form' ) ?>

<form method="get" id="searchform" action="<?php echo site_url(); ?>/">
	<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="<?php _e( 'Search', 'cc' ) ?>" />
	<input type="submit" id="searchsubmit" value="<?php _e( 'Search', 'cc' ) ?>" />

	<?php do_action( 'bp_blog_search_form' ) ?>
</form>

<?php do_action( 'bp_after_blog_search_form' ) ?>