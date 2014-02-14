<?php
/*
Plugin Name: Extend Comment
Version: 1.0
Plugin URI: http://jdforrest.net
Description: A plugin to add fields to the comment form. From Smashing Magazine article: http://wp.smashingmagazine.com/2012/05/08/adding-custom-fields-in-wordpress-comment-form/
Author: Specky Geek, John Forrest
Author URI: http://jdforrest.net
*/


// Add custom meta (ratings) fields to the default comment form
// Default comment form includes name, email address and website URL
// Default comment form elements are hidden when user is logged in

add_filter('comment_form_default_fields', 'custom_fields');
function custom_fields($fields) {

		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

		$fields[ 'author' ] = '<p class="comment-form-author">'.
			'<label for="author">' . __( 'Name' ) . '</label>'.
			( $req ? '<span class="required">*</span>' : '' ).
			'<input id="author" name="author" type="text" value="'. esc_attr( $commenter['comment_author'] ) .
			'" size="30" tabindex="1"' . $aria_req . ' /></p>';

		$fields[ 'email' ] = '<p class="comment-form-email">'.
			'<label for="email">' . __( 'E-mail' ) . '</label>'.
			( $req ? '<span class="required">*</span>' : '' ).
			'<input id="email" name="email" type="text" value="'. esc_attr( $commenter['comment_author_email'] ) .
			'" size="30"  tabindex="2"' . $aria_req . ' /></p>';
		
		// have to set this to blank since it is a default
		$fields[ 'url' ] = '';
		 
		return $fields;
}


// Add fields after default fields above the comment box, always visible

add_action( 'comment_form_logged_in_after', 'additional_fields' ); //this only shows if they are logged in
add_action( 'comment_form_after_fields', 'additional_fields' );

function additional_fields() {
	
	//comment form title not currently required, should it be?
	echo '<p class="comment-form-title">'.
	'<label for="title">' . __( 'Title' ) . '</label>'.
	'<input id="title" name="title" type="text" size="30"  tabindex="5" /></p>';
	
	//note that the rating field is required
	echo '<p class="comment-form-rating">'.
	'<label for="rating">'. __('Rating') . '<span class="required">*</span></label>
	<span class="commentratingbox">';

	//Current rating scale is 1 to 5. If you want the scale to be 1 to 10, then set the value of $i to 10.
	//dont need to use this loop, can instead write our own HTML, though we should still be able to get the star images to load
	for( $i=1; $i <= 5; $i++ )
	echo '<span class="commentrating"><input type="radio" name="rating" id="rating" value="'. $i .'"/>'. $i .'</span>';

	echo'</span></p>';

}


// Save the comment meta data along with comment. wp_filter_nohtml_kses sanitizes inputted data
// Also had to add an action to the comment_post hook to save data when not blank
// This is the default usuage: add_comment_meta($comment_id, $meta_key, $meta_value, $unique = false)

add_action( 'comment_post', 'save_comment_meta_data' );
function save_comment_meta_data( $comment_id ) {

	if ( ( isset( $_POST['title'] ) ) && ( $_POST['title'] != '') )
	$title = wp_filter_nohtml_kses($_POST['title']);
	add_comment_meta( $comment_id, 'title', $title );

	if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != '') )
	$rating = wp_filter_nohtml_kses($_POST['rating']);
	add_comment_meta( $comment_id, 'rating', $rating );
}


// Add the filter to check whether the comment meta data has been filled, requires rating field

add_filter( 'preprocess_comment', 'verify_comment_meta_data' );
function verify_comment_meta_data( $commentdata ) {
	if ( ! isset( $_POST['rating'] ) )
	wp_die( __( 'Error: You did not add a rating. Hit the Back button on your Web browser and resubmit your comment with a rating.' ) );
	return $commentdata;
}


// Add an edit option to comment editing screen (in the WP dashboard)
// using wp_nonce_field for security

add_action( 'add_meta_boxes_comment', 'extend_comment_add_meta_box' );
function extend_comment_add_meta_box() {
    add_meta_box( 'title', __( 'Comment Metadata - Extend Comment' ), 'extend_comment_meta_box', 'comment', 'normal', 'high' );
}

function extend_comment_meta_box( $comment ) {
    $title = get_comment_meta( $comment->comment_ID, 'title', true );
    $rating = get_comment_meta( $comment->comment_ID, 'rating', true );
    wp_nonce_field( 'extend_comment_update', 'extend_comment_update', false );
    ?>
    <p>
        <label for="title"><?php _e( 'Comment Title' ); ?></label>
        <input type="text" name="title" value="<?php echo esc_attr( $title ); ?>" class="widefat" />
    </p>
    <p>
        <label for="rating"><?php _e( 'Rating: ' ); ?></label>
			<span class="commentratingbox">
			<?php for( $i=1; $i <= 5; $i++ ) {
				echo '<span class="commentrating"><input type="radio" name="rating" id="rating" value="'. $i .'"';
				if ( $rating == $i ) echo ' checked="checked"';
				echo ' />'. $i .' </span>';
				}
			?>
			</span>
    </p>
    <?php
}


// Update comment meta data from comment editing screen 

add_action( 'edit_comment', 'extend_comment_edit_metafields' );
function extend_comment_edit_metafields( $comment_id ) {
    if( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) return;

	if ( ( isset( $_POST['title'] ) ) && ( $_POST['title'] != '') ):
	$title = wp_filter_nohtml_kses($_POST['title']);
	update_comment_meta( $comment_id, 'title', $title );
	else :
	delete_comment_meta( $comment_id, 'title');
	endif;

	if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != '') ):
	$rating = wp_filter_nohtml_kses($_POST['rating']);
	update_comment_meta( $comment_id, 'rating', $rating );
	else :
	delete_comment_meta( $comment_id, 'rating');
	endif;

}

// Add the comment meta (saved earlier) to the comment text
// You can also output the comment meta values directly to the comments template  

add_filter( 'comment_text', 'modify_comment');
function modify_comment( $text ){

	$plugin_url_path = WP_PLUGIN_URL; //required since we're using images saved in that folder
									  //if we are going to put this into a theme, this will be the URL of the theme directory
	
	//if comment has a title, bolds the text and puts it in front of the main comment box
	if( $commenttitle = get_comment_meta( get_comment_ID(), 'title', true ) ) {
		$commenttitle = '<strong>' . esc_attr( $commenttitle ) . '</strong><br/>';
		$text = $commenttitle . $text;
	} 
	
	//if has a rating, adds to the end of the comment
	// REMOVED: <br/>Rating: <strong>'. $commentrating .' / 5</strong>
	if( $commentrating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
		$commentrating = '<p class="comment-rating">	<img src="'. $plugin_url_path .
		'/ExtendComment/images/'. $commentrating . 'star.gif"/></p>';
		$text = $text . $commentrating;
		return $text;
	} else {
		return $text;
	}
}

?>