<?php get_header(); ?>

    <div id="content" class="span8">
		<div class="padder">

		<?php do_action( 'bp_before_activation_page' ) ?>

		<div class="page" id="activate-page">

			<?php if ( bp_account_was_activated() ) : ?>

				<h2 class="widgettitle"><?php _e( 'Email Address Confirmed', 'cc' ) ?></h2>

				<?php do_action( 'bp_before_activate_content' ) ?>

				<?php if ( isset( $_GET['e'] ) ) : ?>
					<p><?php _e( 'Your email address was confirmed. Please allow 24-48 hours for your account to be verified and activated.', 'cc' ) ?></p>
				<?php else : ?>
					<p><?php _e( 'Your email address was confirmed. Please allow 24-48 hours for your account to be verified and activated.', 'cc' ) ?></p>
				<?php endif; ?>

			<?php else : ?>

				<h3><?php _e( 'Confirm your Email Address', 'cc' ) ?></h3>

				<?php do_action( 'bp_before_activate_content' ) ?>

				<p><?php _e( 'Please provide a valid activation key.', 'cc' ) ?></p>

				<form action="" method="get" class="standard-form" id="activation-form">

					<label for="key"><?php _e( 'Activation Key:', 'cc' ) ?></label>
					<input type="text" name="key" id="key" value="" />

					<p class="submit">
						<input type="submit" name="submit" value="<?php _e( 'Activate', 'cc' ) ?>" />
					</p>

				</form>

			<?php endif; ?>

			<?php do_action( 'bp_after_activate_content' ) ?>

		</div><!-- .page -->

		<?php do_action( 'bp_after_activation_page' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_footer(); ?>