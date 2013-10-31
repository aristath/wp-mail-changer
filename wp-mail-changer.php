<?php
/*
Plugin Name: WordPress Mail Changer
Plugin URI: http://shoestrap.org
Description: A simple and easy way to change the default email address used by WordPress for generic emails.
Author: Aristeides Stathopoulos
Author URI: http://aristeides.com
Version: 1.0.0
*/

/*
 * Add the menu page to the "Settings" area
 */
function wpmc_create_menu() {
	add_options_page( 'Mail Change', 'Mail Change', 'manage_options', 'mail-change', 'wpmc_options_page' );
}
add_action('admin_menu', 'wpmc_create_menu');

/*
 * The contents of the page
 */
function wpmc_options_page() {
	$mail_from      = get_option( 'wpmc_mail_from' );
	$mail_from_name = get_option( 'wpmc_mail_from_name' );
	?>
	<div class="wrap">
		<h2><?php _e('Mail Changer Options'); ?></h2>
		<form method="post" action="options.php">
		
			<?php settings_fields('wpmc_options'); ?>
			
			<table class="form-table">
				<tbody>
					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('Email Address'); ?>
						</th>
						<td>
							<input id="wpmc_mail_from" name="wpmc_mail_from" type="text" class="email" value="<?php esc_attr_e( $mail_from ); ?>" />
							<?php if ( $mail_from != '' && !filter_var( $mail_from, FILTER_VALIDATE_EMAIL ) ) : ?>
								<label class="description" for="wpmc_mail_from"><span style="color: red;"><?php _e( 'Invalid address, please enter a valid email address.' ); ?></span></label>
							<?php else : ?>
								<label class="description" for="wpmc_mail_from"><?php _e( 'Enter your "from" email address' ); ?></label>
							<?php endif; ?>
						</td>
					</tr>
					<tr valign="top">	
						<th scope="row" valign="top">
							<?php _e('Email Name'); ?>
						</th>
						<td>
							<input id="wpmc_mail_from_name" name="wpmc_mail_from_name" type="text" class="regular-text" value="<?php esc_attr_e( $mail_from_name ); ?>" />
							<label class="description" for="mail_from_name"><?php _e( 'Enter your "from" email address' ); ?></label>
						</td>
					</tr>
				</tbody>
			</table>	
			<?php submit_button(); ?>
		
		</form>
	<?php
}


/*
 * Creates our settings in the options table
 */
function wpmc_register_option() {
	register_setting('wpmc_options', 'wpmc_mail_from' );
	register_setting('wpmc_options', 'wpmc_mail_from_name' );
}
add_action('admin_init', 'wpmc_register_option');


/*
 * Change the email address
 */
function wpmc_mail_from( $email_address ) {
	$mail_from = get_option( 'wpmc_mail_from' );
	return $mail_from;
}


/*
 * Change the email from field
 */
function wpmc_mail_from_name( $email_from ) {
	$mail_from_name = get_option( 'wpmc_mail_from_name' );
	return $mail_from_name;
}

function wpmc_apply_filters() {
	$mail_from      = get_option( 'wpmc_mail_from' );
	$mail_from_name = get_option( 'wpmc_mail_from_name' );

	if ( $mail_from != '' && filter_var( $mail_from, FILTER_VALIDATE_EMAIL ) )
		add_filter( 'wp_mail_from', 'wpmc_mail_from' );

	if ( $mail_from_name != '' && $mail_from != '' && filter_var( $mail_from, FILTER_VALIDATE_EMAIL ) )
		add_filter( 'wp_mail_from_name', 'wpmc_mail_from_name' );

}
add_action( 'init', 'wpmc_apply_filters' );