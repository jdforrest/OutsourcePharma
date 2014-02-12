<?php get_header() ?>

    <div id="content" class="span8">
		<div class="padder">

			<div class="page new-study-page" id="blog-page">
				<div class="entry">
			
			    <?php get_posts_titles(get_the_title(), get_the_ID());?>
				
				<?php if (current_user_can( publish_study)) : ?> 
					
					<!-- New Study Form -->
						<div id="postbox">
						<form id="new-study" name="new-study" method="post" action="">
						<p><label for="title">Title</label><br />
						<input type="text" id="title" value="" tabindex="1" size="20" name="title" />
						</p>
						<p><label for="description">Description</label><br />
						<textarea id="description" tabindex="2" name="description" cols="50" rows="6"></textarea>
						</p>

						<p align="right"><input type="submit" value="Save" tabindex="6" id="submit" name="submit" /></p>
						<input type="hidden" name="action" value="new_study" />
						<?php wp_nonce_field( 'new-post' ); ?>
						</form>
						</div>
					<!--// New Study Form -->
					
					<!-- Form Processing -->
					
					<?php if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "new_study") {

    // Do some minor form validation to make sure there is content
    if (isset ($_POST['title'])) {
        $title =  $_POST['title'];
    } else {
        echo 'Please enter a study title.';
    }
    if (isset ($_POST['description'])) {
        $description = $_POST['description'];
    } else {
        echo 'Please enter a description.';
    }

    // Add the content of the form to $post as an array
    $new_post = array(
        'post_title'    => $title,
        'post_content'  => $description,
        'post_status'   => 'publish',
        'post_type' 	=> 'study'
    );
    //save the new post and return its ID
    $pid = wp_insert_post($new_post); 
	echo "New study ".get_the_title($pid)." posted.";
	}
?>
					<!--// Form Processing -->

						
					<?php else : ?>
						We're sorry. You do not have authorization to post a new study. Please contact the site administrator.
					<?php endif; ?>
				</div>
			</div>

			<?php do_action( 'bp_after_blog_single_post' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_footer() ?>
