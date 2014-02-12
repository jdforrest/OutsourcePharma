<?php global $cap;
get_header(); ?>
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
                    <?php archive_post_order($query_string); ?>
                    <?php while (have_posts()) : the_post(); ?>

                        <?php do_action( 'bp_before_blog_post' ) ?>

                        <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                            <div class="author-box visible-desktop">
                                <?php echo get_avatar( get_the_author_meta( 'user_email' ), '50' ); ?>
                                <?php cc_author_link(); ?>
                            </div>

                            <div class="post-content span11">
                                <h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'cc' ) ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

                                <p class="date"><?php the_time('F j, Y') ?> <em><?php _e( 'in', 'cc' ) ?> <?php the_category(', ') ?> <?php cc_author_link(); ?></em></p>

                                <div class="entry">
                                    <?php do_action('blog_post_entry')?>
                                </div>

                                <?php $tags = get_the_tags(); if($tags)	{  ?>
                                    <p class="postmetadata"><span class="tags"><?php the_tags( __( 'Tags: ', 'cc' ), ', ', '<br />'); ?></span> <span class="comments"><?php comments_popup_link( __( 'No Comments &#187;', 'cc' ), __( '1 Comment &#187;', 'cc' ), __( '% Comments &#187;', 'cc' ) ); ?></span></p>
                                <?php } else {?>
                                    <p class="postmetadata"><span class="comments"><?php comments_popup_link( __( 'No Comments &#187;', 'cc' ), __( '1 Comment &#187;', 'cc' ), __( '% Comments &#187;', 'cc' ) ); ?></span></p>
                                <?php } ?>
                            </div>

                        </div>

                        <?php do_action( 'bp_after_blog_post' ) ?>

                    <?php endwhile; ?>
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

<?php do_action( 'bp_before_sidebar' ) ?>
<div class="v_line v_line_right visible-desktop"></div>
<div id="sidebar" class="span4 widgetarea">
	<div class="paddersidebar right-sidebar-padder">

	<?php do_action( 'bp_before_after_sidebar' ) ?>
	<?php if( ! dynamic_sidebar( 'resourcesright' )): ?>    
		
	<?php if ( is_singular() ) { ?>
		<div class="widget">
			<h3 class="widgettitle" ><?php _e('Recent Posts', 'cc'); ?></h3>
			<ul>
				<?php
				$myposts = get_posts('numberposts=5&offset=0&category=0');
				foreach($myposts as $post) : setup_postdata($post);
				?>
				<li><span><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php } else { ?>
		<div class="widget">
			<h3 class="widgettitle" ><?php _e('Random Posts', 'cc'); ?></h3>
			<ul>
				<?php
				$rand_posts = get_posts('numberposts=5&orderby=rand');
				foreach( $rand_posts as $post ) :
				?>
				<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php } ?>
	<div class="widget tags">
		<h3 class="widgettitle" ><?php _e('Search by Tags!', 'cc'); ?></h3>
		<div><?php wp_tag_cloud('smallest=9&largest=18'); ?></div>
	</div>	
	<div class="widget">
		<h3 class="widgettitle" ><?php _e('Archives', 'cc'); ?></h3>
		<ul>
			<?php wp_get_archives( 'type=monthly' ); ?>
		</ul>
	</div>
	<div class="widget">
		<h3 class="widgettitle" ><?php _e('Links', 'cc'); ?></h3>
		<ul>
			<?php wp_list_bookmarks('title_li=&categorize=0&orderby=id'); ?>
		</ul>
	</div>
	<div class="widget">
		<h3 class="widgettitle" ><?php _e('Meta', 'cc'); ?></h3>
		<ul>
			<?php wp_register(); ?>
			<li><?php wp_loginout(); ?></li>
			<?php wp_meta(); ?>
		</ul>
	</div>

	<?php endif; // end primary widget area ?>
	
	
	<?php do_action( 'bp_inside_after_sidebar' ) ?>

	</div><!-- .padder -->
</div><!-- #sidebar -->



<?php get_footer(); ?>
