<?php global $cap; get_header(); ?>

<script type='text/javascript' src='/wp-content/plugins/jquery-collapse-o-matic/js/collapse.js'></script>

	<div id="content" class="span8">
		<div class="padder">
		<?php do_action( 'bp_before_archive' ) ?>

		<div class="page" id="blog-archives">
            <?php
            $args = array();
            if($cap->posts_lists_style_taxonomy == 'magazine' ||
               $cap->posts_lists_style_dates    == 'magazine' ||
               $cap->posts_lists_style_author   == 'magazine') {
                    $args = array();
                    $magazine_style = '';
                    if($cap->posts_lists_style_taxonomy == 'magazine' && is_category()){
                        $args['category_name'] = get_query_var('category_name');
                        $magazine_style        = $cap->magazine_style_taxonomy;
                    } else if($cap->posts_lists_style_dates == 'magazine' && is_date()){
                        $args['year']     = get_query_var('year');
                        $args['monthnum'] = get_query_var('monthnum');
                        $magazine_style   = $cap->magazine_style_dates;
                    } else if($cap->posts_lists_style_author == 'magazine' && is_author()){
                        $args['author'] = get_query_var('author');
                        $magazine_style   = $cap->magazine_style_author;
                    }

                    if($magazine_style){
                        $args['img_position'] = cc_get_magazine_style($magazine_style);
                    }
            }
            if(is_tag()){
                $args['tag'] = get_query_var('tag');
            }
            if(is_author()){
                $args['author'] = get_query_var('author');
            }
             ?>

            <header class="page-header">
                <h3 class="page-title">
                    <?php if ( is_day() ) : ?>
                        <?php printf( __( 'Daily Archives: %s', 'cc' ), '<span>' . get_the_date() . '</span>' ); ?>
                    <?php elseif ( is_month() ) : ?>
                        <?php printf( __( 'Monthly Archives: %s', 'cc' ), '<span>' . get_the_date( 'F Y' ) . '</span>' ); ?>
                    <?php elseif ( is_year() ) : ?>
                        <?php printf( __( 'Yearly Archives: %s', 'cc' ), '<span>' . get_the_date( 'Y' ) . '</span>' ); ?>
                    <?php else : ?>
                        <?php printf( __( '%1$s', 'cc' ), wp_title( false, false ) ); ?>
                    <?php endif; ?>
                </h3>
            </header>

			
            <?php
            if(!empty($args)):
                $args['amount'] = get_option('posts_per_page', 9);?>

                <?php echo '<div class="archive-last-posts">'.cc_list_posts($args).'</div>';?>

                <div class="navigation">
                    <div class="alignleft"><?php next_posts_link( __( '&larr; Previous Entries', 'cc' ) ) ?></div>
                    <div class="alignright"><?php previous_posts_link( __( 'Next Entries &rarr;', 'cc' ) ) ?></div>
                </div>
            <?php else: ?>
                <?php if ( have_posts() ) : ?>

                    <div class="navigation">
                        <div class="alignleft"><?php next_posts_link( __( '&larr; Previous Entries', 'cc' ) ) ?></div>
                        <div class="alignright"><?php previous_posts_link( __( 'Next Entries &rarr;', 'cc' ) ) ?></div>
                    </div>
                    <?php /* archive_post_order($query_string); */
					?>
									
			<ul class="studies">
				<li class="studies-header"><div class="posttitle-header">Name</div><div class="status-header">Status</div><div class="date-header">Open Date</div><div class="date-header">Close Date</div></li>
	
                    <?php while ( have_posts()) : the_post(); ?>
                        <?php do_action( 'bp_before_blog_post' );
						$studymeta=get_post_meta($post->ID);
						$timeuntilclose = $studymeta['wpcf-end-date'][0] + 86400 - time();
						if ($timeuntilclose > 0) {
								$studystatus = "open";
									} else {
								$studystatus = "closed";
									}
						$row++;
						if ($row%2==0) {
							$rowclass='even';
							} else {
							$rowclass='odd';
							}
						?>
                        <li id="post-<?php the_ID(); ?>"<?php post_class( "$rowclass $studystatus" ); ?> >
							<div id="study-details-row-<?php the_ID(); ?>" class="studies-row study-details-row">
								<div class="posttitle">
									<?php if ($studystatus == "open"): ?>
										<span class="collapseomatic" id="d<?php the_ID(); ?>">
											<a href="#<?php the_ID(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
										</span>
									<?php else: ?>
										<?php the_title(); ?>								
									<?php endif; ?>
								</div>
								<div class="study-status <?php echo "study-status-".$studystatus; ?>"><?php echo ucwords($studystatus); ?></div>
								<div class="date"><?php the_time('F j, Y') ?></div>
								<div class="date"><? echo gmdate("l, F j, Y", $studymeta['wpcf-end-date'][0]); ?></div>
							</div>
							<div id="study-areas-<?php the_ID(); ?>" class="studies-row study-areas-row">
								<div colspan="4" class="study-areas <?php echo $rowclass; ?>">
									<?php $terms = get_the_terms( $post->ID, 'therapeutic-area');
										if ( $terms && ! is_wp_error( $terms ) ) : 
											$area_terms = array();
											foreach ( $terms as $term ) {
												$area_terms[] = $term->name;
											}
											$areas = join( ", ", $area_terms );
										echo $areas;
										endif;
									?>
								</div>
							</div>
							<?php if ($studystatus == "open"): ?>
								<div class="collapseomatic_content" id="target-d<?php the_ID(); ?>">
									<div class="study-description">
										<?php the_content( __( 'Read the rest of this entry &rarr;', 'cc' ) ); ?>
									</div>
									<div class="study-details-collapsible">
									<?php $terms = get_the_terms( $post->ID, 'development-stage');
										if ( $terms && ! is_wp_error( $terms ) ) : 
											$area_terms = array();
											foreach ( $terms as $term ) {
												$area_terms[] = $term->name;
											}
											$areas = join( ", ", $area_terms );
											
											if (count($terms)==1) :
												echo "<span class='study-field-label'>Development Stage:</span> ";
											else :
												echo "<span class='study-field-label'>Development Stages:</span> ";
											endif;
											echo $areas."<br />";
										endif;
									
										if ($studymeta['wpcf-format'][0]!='') :
											echo "<span class='study-field-label'>Format:</span> ".$studymeta['wpcf-format'][0]."<br />";
										endif;
										
										if ($studymeta['wpcf-length'][0]!='') :
											echo "<span class='study-field-label'>Length:</span> ".$studymeta['wpcf-length'][0]." minutes<br />";
										endif;
										
										if ($studymeta['wpcf-incentive-type'][0]=='Monetary') :
											echo "<span class='study-field-label'>Incentive:</span> $".$studymeta['wpcf-incentive-amount'][0]."<br />";
										elseif ($studymeta['wpcf-incentive-type'][0]=='Non-monetary') :
											echo "<span class='study-field-label'>Incentive:</span> ".$studymeta['wpcf-incentive-description'][0]."<br />";
										endif;
									?>
									<div class="study-link"><a href="<?php echo $studymeta['wpcf-study-url'][0] ?>" class="study-link-button button">Proceed to the Study</a></div>
									</div>
									
								</div>
							<?php endif; ?>
						</li>

                        <?php do_action( 'bp_after_blog_post' ) ?>

                    <?php endwhile; ?>	
			</ul>
                    <?php if(function_exists('wp_pagenavi')):
                        wp_pagenavi();
                    else: ?>
                        <div class="navigation">
                            <div class="alignleft"><?php next_posts_link( __( '&larr; Previous Entries', 'cc' ) ) ?></div>
                            <div class="alignright"><?php previous_posts_link( __( 'Next Entries &rarr;', 'cc' ) ) ?></div>
                        </div>
                    <?php endif;?>
                <?php else : ?>

                    <h2 class="center"><?php _e( 'Not Found', 'cc' ) ?></h2>
                    <?php locate_template( array( 'searchform.php' ), true ) ?>

                <?php endif; ?>
            <?php endif; ?>
		</div>

		<?php do_action( 'bp_after_archive' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->
<?php get_footer(); ?>