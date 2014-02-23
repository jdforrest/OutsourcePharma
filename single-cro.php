<?php get_header() ?>

    <div id="content" class="span8">
		<div class="padder">
			<?php do_action( 'bp_before_blog_single_post' ) ?>

			<div class="page" id="blog-single">

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<?php
							global $cc_post_options;
							$cc_post_options=cc_get_post_meta();
							$single_class = false;
						    if(isset($cc_post_options) && $cc_post_options['cc_post_template_on'] == 1){
	
								switch ($cc_post_options['cc_post_template_type'])
						        {
						        case 'img-left-content-right':
									$single_class = 'single-img-left-content-right';
						        break;
						        case 'img-right-content-left':
									$single_class = 'single-img-right-content-left';
						        break;
						        case 'img-over-content':
									$single_class = 'single-img-over-content';
						        break;
						        case 'img-under-content':
									$single_class = 'single-img-under-content';
						        break;
						        default:
						        	$single_class = false;
						        break;
						        }
							}
						?>
						
						<!-- This div displays the content for the individual CRO as defined in the dashboard using the Types plugin -->
						<div class="post-content span11" style="<?php if($cc_post_options['cc_post_template_avatar'] == '1') { echo 'margin-left:0;'; } ?>">
							<?php if ($single_class != false){ ?>
								<div class="<?php echo $single_class ?>">
							<?php } ?>

                            <?php get_posts_titles(get_the_title(), get_the_ID()); ?>

							<div class="entry">
								<div class="cro-average-rating">Average rating will go here!!</div>
							    <div class="cro-info">
							        <div class="address-block">
								        <?php $crometa=get_post_meta($post->ID);
									        if (!($crometa['wpcf-address'][0]=='')) { echo $crometa['wpcf-address'][0]."<br />"; }
											if (!($crometa['wpcf-city'][0]=='')) { echo $crometa['wpcf-city'][0]; }
											if (!($crometa['wpcf-city'][0]=='') && !($crometa['wpcf-state'][0]=='')) { echo ', '; }
											if (!($crometa['wpcf-state'][0]=='')) { echo $crometa['wpcf-state'][0]; }
											if ((!($crometa['wpcf-city'][0]=='') || !($crometa['wpcf-state'][0]=='')) && !($crometa['wpcf-zip'][0]=='')) { echo ' '; }
											if (!($crometa['wpcf-zip'][0]=='')) { echo $crometa['wpcf-zip'][0]; }
											if (!($crometa['wpcf-city'][0]=='') || !($crometa['wpcf-state'][0]=='') || !($crometa['wpcf-zip'][0]=='')) { echo '<br />'; }
											if (!($crometa['wpcf-phone'][0]=='')) { echo $crometa['wpcf-phone'][0].'<br/>'; }								
											if (!($crometa['wpcf-website'][0]=='')) { echo $crometa['wpcf-website'][0].'<br/>'; }
										?>
							        </div> <!-- .address-block -->
							    </div> <!-- .cro-info -->
								<?php if ($single_class == 'single-img-left-content-right' || $single_class == 'single-img-right-content-left' || $single_class == 'single-img-over-content'){ ?>
									<?php the_post_thumbnail()?>
								<?php } ?>
								<?php the_content( __( 'Read the rest of this entry &rarr;', 'cc' ) ); ?>
								<?php if ($single_class == 'single-img-under-content'){ ?>
									<?php the_post_thumbnail()?>
								<?php } ?>
								<div class="clear"></div>
								<?php wp_link_pages(array('before' => __( '<p class="cc_pagecount"><strong>Pages:</strong> ', 'cc' ), 'after' => '</p>', 'next_or_number' => 'number')); ?>
							</div><!-- .entry -->

							<div class="clear"></div>

							<?php if($cc_post_options['cc_post_template_tags'] != '1') {?>
								<?php $tags = get_the_tags(); if($tags)	{  ?>
									<p class="postmetadata"><span class="tags"><?php the_tags( __( 'Tags: ', 'cc' ), ', ', '<br />'); ?></span></p>
								<?php } ?>
							<?php } ?>

							<?php if ($single_class != false){ ?>
								</div>
							<?php } ?>

						</div><!-- .post-content -->
					</div><!-- #post -->

					<!--
					Now to display the comments, but we are using the custom field "Reviews" as defined with the Types plugin. 
					As a result, comments_template() will not work 
					-->
					<?php 
						$croID = $post->ID;
				     	$crochildren =& types_child_posts('review');
				        /* print $croID.'</br>'; 
				         print_r( $crochildren ); */
				        if ( $crochildren ) {
				       		foreach ( $crochildren as $crochild ) {
				            	$childrating = get_post_meta($crochild->ID,'wpcf-rating',TRUE);
				                $childurl = get_permalink ( $crochild ); ?>
				                <div class="review" id="review-<?php echo $crochild->ID; ?>">
				                	<div class="author-box visible-desktop">
				                    	<?php echo get_avatar( get_the_author_meta( 'user_email' ), '50' ); ?>
				                        <?php cc_author_link(); ?>
				                   	</div>
				                    <h4 class="review-title">
				                    	<a href="<?php echo $childurl; ?>"><?php echo apply_filters( 'the_title' , $crochild->post_title );?></a>
				                    </h4>
				                    <div class="opab-review-rating opab-review-rating-<?php echo $childrating; ?>" title="Rating: <?php echo $childrating; ?>">
                                    	<?php echo "Rating: ".$childrating; ?>
                                    </div>
                                    <div class="review-body">
                                    	<?php echo $crochild->post_content.'</div></div> <br />';
				                  }
				            }
					?>

					<div class="alignleft"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'cc' ) . '</span> %title' ); ?></div>
					<div class="alignright"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'cc' ) . '</span>' ); ?></div>
                  
					<!-- don't think this call is needed since the comments are regulated using the Types plugin and the custom field "Reviews". Leaving it in for now. -->
					<?php comments_template(); ?>

					<?php endwhile; else: ?>
						<p><?php _e( 'Sorry, no posts matched your criteria.', 'cc' ) ?></p>
					<?php endif; ?>
			</div>

			<?php do_action( 'bp_after_blog_single_post' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_footer() ?>