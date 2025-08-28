<?php
/**
 * Plugin Name: WP Mail Changer
 * Plugin URI: https://aristath.github.io
 * Description: A simple and easy way to change the default email address used by WordPress for generic emails.
 * Author: Aristeides Stathopoulos
 * Author URI: https://aristath.github.io
 * Version: 1.1.0
 * Text Domain: wpmc
 */

// Exit if accessed directly.
if ( ! \defined( 'ABSPATH' ) ) {
	exit;
}

// Register the settings.
\add_action( 'admin_init', function () {
	\register_setting( 'wpmc_options', 'wpmc_mail_from' );
	\register_setting( 'wpmc_options', 'wpmc_mail_from_name' );
} );

// Filter the from email address.
\add_filter( 'wp_mail_from', function ( $email_address ) {
	$value = \get_option( 'wpmc_mail_from', $email_address );
	return \filter_var( $value, \FILTER_VALIDATE_EMAIL ) ? $value : $email_address;
} );

// Filter the from email name.
\add_filter( 'wp_mail_from_name', function ( $email_from ) {
	$value = \get_option( 'wpmc_mail_from_name', $email_from );
	return $value ? $value : $email_from;
} );


// Add the menu page to the "Settings" area.
\add_action( 'admin_menu', function () {
	\add_options_page( 
		\esc_html__( 'Mail Change', 'wpmc' ), 
		\esc_html__( 'Mail Change', 'wpmc' ), 
		'manage_options', 
		'mail-change', 
		function () {
			$mail_from      = \get_option( 'wpmc_mail_from' );
			$mail_from_name = \get_option( 'wpmc_mail_from_name' );
			?>
			<div class="wrap">
				<h2><?php \esc_html_e( 'Mail Changer Options', 'wpmc' ); ?></h2>
				<form method="post" action="options.php">
					<?php settings_fields( 'wpmc_options' ); ?>
					<table class="form-table">
						<tbody>
							<tr valign="top">	
								<th scope="row" valign="top">
									<?php \esc_html_e( 'Email Address', 'wpmc' ); ?>
								</th>
								<td>
									<input id="wpmc_mail_from" name="wpmc_mail_from" type="text" class="email" value="<?php echo \esc_attr( $mail_from ); ?>" />
									<?php if ( $mail_from !== '' && ! filter_var( $mail_from, FILTER_VALIDATE_EMAIL ) ) : ?>
										<label class="description" for="wpmc_mail_from"><span style="color: red;"><?php \esc_html_e( 'Invalid address, please enter a valid email address.', 'wpmc' ); ?></span></label>
									<?php else : ?>
										<label class="description" for="wpmc_mail_from"><?php \esc_html_e( 'Enter your "from" email address', 'wpmc' ); ?></label>
									<?php endif; ?>
								</td>
							</tr>
							<tr valign="top">	
								<th scope="row" valign="top">
									<?php \esc_html_e( 'Email Name', 'wpmc' ); ?>
								</th>
								<td>
									<input id="wpmc_mail_from_name" name="wpmc_mail_from_name" type="text" class="regular-text" value="<?php echo \esc_attr( $mail_from_name ); ?>" />
									<label class="description" for="mail_from_name"><?php \esc_html_e( 'Enter your "from" email address', 'wpmc' ); ?></label>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<?php \esc_html_e( 'Please note that in order for the name to be used, the email address will also have to be valid.', 'wpmc' ); ?>
								</td>
							</tr>
						</tbody>
					</table>	
					<?php \submit_button(); ?>
				</form>
			<?php
		} 
	);
} );
