            
            <?php do_action('sidebar_right') ?>

            </div> <!-- .row-fluid -->
		
        </div> <!-- #container -->		

		<?php do_action( 'bp_after_container' ) ?>

		<?php do_action( 'bp_before_footer' ) ?>
		
		<div id="footer">
                <div id="footer-container">
                <a href="http://lifesciencestrategy.com/"><img id="footer-logo" src="<?php echo get_template_directory_uri() ?>/images/lssg-footer-logo.png"></a>
		<?php wp_nav_menu( array(
                        'theme_location' => 'footer-menu',
                        'menu' => '5',
                        'before' => '<span class="menu-separator">&nbsp;/&nbsp; </span>'
                        ) ); ?>
		<?php do_action( 'bp_footer' ) ?>
                </div>
                </div>
                <!-- #footer -->

		<?php do_action( 'bp_after_footer' ) ?>

	</div><!-- #outerrim -->

	<?php wp_footer(); ?>

	</body>

</html>