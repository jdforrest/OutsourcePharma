<?php 
/*
 * Template Name: Home Page With Two Sidebars
 */
?>

<?php get_header(); ?>

<?php do_action( 'bp_before_sidebar' ) ?>

<div id="leftsidebar" class="widgetarea">
  <div class="paddersidebar left-sidebar-padder">
  <?php if(defined('BP_VERSION')){ ?>
      <?php if( ! dynamic_sidebar( 'leftsidebar' )) : ?>
      <?php widget_community_nav( 'leftsidebar' ); ?>
      <?php endif; // end primary widget area ?>
  <?php } else {?>
      <?php if( ! dynamic_sidebar( 'leftsidebar' )) : ?>
      <?php endif ?>  
  <?php } ?>
  </div><!-- #paddersidebar -->	
</div><!-- #leftsidebar -->
<div class="v_line v_line_left visible-desktop"></div>

<?php do_action( 'bp_after_sidebar' ) ?>

	<div id="content" class="span8">
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<div class="page" id="blog-page">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                <?php get_posts_titles(get_the_title(), get_the_ID());?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="entry">

						<?php the_content( __( '<p class="serif">Read the rest of this page &rarr;</p>', 'cc' ) ); ?>
						<div class="clear"></div>
						<?php wp_link_pages( array( 'before' => __( '<p class="cc_pagecount"><strong>Pages:</strong> ', 'cc' ), 'after' => '</p>', 'next_or_number' => 'number')); ?>

					</div>
					<div class="clear"></div>
				</div>

			<?php endwhile; endif; ?>

		</div><!-- .page -->

		<?php cc_list_posts_on_page(); ?>

		<div class="clear"></div>

		<?php do_action( 'bp_after_blog_page' ) ?>
		
	<div class="padder">
	<div id="bottom-pane" class="pane widgetarea">
		<?php if ( dynamic_sidebar('frontpagebottomcenter') ) : else : endif; ?>
	</div>
	</div>

		</div><!-- .padder -->
	</div><!-- #content -->
<?php do_action( 'bp_after_sidebar' ) ?>

<?php do_action( 'bp_before_sidebar' ) ?>
<div class="v_line v_line_right visible-desktop"></div>
<div id="sidebar" class="span4 widgetarea">
	<div class="paddersidebar right-sidebar-padder">

	<?php do_action( 'bp_before_after_sidebar' ) ?>
	<?php if( ! dynamic_sidebar( 'frontpageright' )): ?>    
		
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


<?php do_action( 'bp_after_sidebar' ) ?>

<?php get_footer(); ?>