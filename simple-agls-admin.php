<?php

/* Settings for schema selection/setting site-wide */

if ( ! class_exists( 'FS_AGLS_Admin' ) ) {

/**
 * So that themes and other plugins can customise the text domain, the FS_AGLS_Admin should
 * not be initialized until after the plugins_loaded and after_setup_theme hooks.
 * However, it also needs to run early on the init hook.
 *
 * @author Jason Conroy <jason@findingsimple.com>
 * @package WP AGLS
 * @since 1.0
 */
function fs_initialize_wp_agls_admin() {
	FS_AGLS_Admin::init();
}
add_action( 'init', 'fs_initialize_wp_agls_admin', -1 );


class FS_AGLS_Admin {

	public static function init() {  

		// create custom plugin settings menu
		add_action( 'admin_menu',  __CLASS__ . '::wpagls_create_menu' );
	}

	public static function wpagls_create_menu() {

		//create new top-level menu
		add_options_page( 'AGLS Settings', 'AGLS', 'administrator', 'simple_agls', __CLASS__ . '::wpagls_settings_page', plugins_url( '/images/icon.png', __FILE__ ) );

		//call register settings function
		add_action( 'admin_init',  __CLASS__ . '::register_mysettings' );

	}


	public static function register_mysettings() {
	
		$page = 'wpagls-settings'; 

		//general settings
		add_settings_section( 
			'wpagls-general', 
			'General Settings',
			__CLASS__ . '::wpagls_general_callback',
			$page
		);

		add_settings_field(
			'wpagls-toggle-show-schema',
			'Toggle Schema Attribute',
			__CLASS__ . '::wpagls_toggle_show_schema_callback',
			$page,
			'wpagls-general'
		);

		//site-wide settings
		add_settings_section( 
			'wpagls-site-wide', 
			'Site-wide Values',
			__CLASS__ . '::wpagls_site_wide_callback',
			$page
		);

		add_settings_field(
			'agls-creator-corporate-name', 
			'Creator corporate name',
			__CLASS__ . '::wpagls_site_wide_corporate_name_callback',
			$page,
			'wpagls-site-wide'
		);

		add_settings_field(
			'agls-creator-address',
			'Creator corporate address',
			__CLASS__ . '::wpagls_site_wide_corporate_address_callback',
			$page,
			'wpagls-site-wide'
		);

		add_settings_field(
			'agls-creator-contact',
			'Creator corporate contact',
			__CLASS__ . '::wpagls_site_wide_corporate_contact_callback',
			$page,
			'wpagls-site-wide'
		);

		add_settings_field(
			'agls-function',
			'Function value',
			__CLASS__ . '::wpagls_site_wide_function_callback',
			$page,
			'wpagls-site-wide'
		);

		add_settings_field(
			'agls-audience',
			'Audience value',
			__CLASS__ . '::wpagls_site_wide_audience_callback',
			$page,
			'wpagls-site-wide'
		);

		add_settings_field(
			'agls-coverage',
			'Coverage value',
			__CLASS__ . '::wpagls_site_wide_coverage_callback',
			$page,
			'wpagls-site-wide'
		);

		add_settings_field(
			'agls-language',
			'Language value',
			__CLASS__ . '::wpagls_site_wide_language_callback',
			$page,
			'wpagls-site-wide'
		);

		add_settings_field(
			'agls-mandate',
			'Mandate value',
			__CLASS__ . '::wpagls_site_wide_mandate_callback',
			$page,
			'wpagls-site-wide'
		);

		add_settings_field(
			'agls-rights',
			'Rights value',
			__CLASS__ . '::wpagls_site_wide_rights_callback',
			$page,
			'wpagls-site-wide'
		);
		
		//register our settings
		
		register_setting( $page, 'wpagls-toggle-show-schema' );

		register_setting( $page, 'agls-creator-corporate-name' );
		register_setting( $page, 'agls-creator-address' );
		register_setting( $page, 'agls-creator-contact' );
		
		register_setting( $page, 'agls-function' );
		register_setting( $page, 'agls-audience' );
		register_setting( $page, 'agls-coverage' );
		register_setting( $page, 'agls-language' );
		register_setting( $page, 'agls-mandate' );
		register_setting( $page, 'agls-rights' );

	}

	public static function wpagls_settings_page() {
	
		$page = 'wpagls-settings'; 
	
	?>
	<div class="wrap">
	
		<h2>WP-AGLS Settings</h2>
		
		<?php settings_errors(); ?>
	
		<form method="post" action="options.php">
			
			<?php settings_fields( $page ); ?>
			
			<?php do_settings_sections( $page ); ?>
		
			<p class="submit">
				<input type="submit" class="button-primary" value="Save Changes" />
			</p>
		
		</form>
		
	</div>
	
	<?php 
	} 

	public static function wpagls_general_callback() {
		
		//do nothing
		
	}
	
	public static function wpagls_toggle_show_schema_callback() {
	
		echo '<input name="wpagls-toggle-show-schema" id="wpagls-toggle-show-schema" type="checkbox" value="1" class="code" ' . checked( 1, get_option('wpagls-toggle-show-schema'), false ) . ' /> Show schema';
		
	}
	
	public static function wpagls_site_wide_corporate_name_callback() {
	
		echo '<input name="agls-creator-corporate-name" type="text" id="agls-creator-corporate-name" class="regular-text" value="'. esc_attr( get_option('agls-creator-corporate-name') ) . '"  />';
		
	}
	
	public static function wpagls_site_wide_corporate_address_callback() {
	
		echo '<input name="agls-creator-address" type="text" id="agls-creator-address" class="regular-text" value="'. esc_attr( get_option('agls-creator-address') ) . '" />';
	
	}
	
	public static function wpagls_site_wide_corporate_contact_callback() {
	
		echo '<input name="agls-creator-contact" type="text" id="agls-creator-contact" class="regular-text" value="'. esc_attr( get_option('agls-creator-contact') ) . '" />';
		
	}

	public static function wpagls_site_wide_function_callback() {
	
		echo '<input name="agls-function" type="text" id="agls-function" class="regular-text" value="'. esc_attr( get_option('agls-function') ) . '" />';
		
	}

	public static function wpagls_site_wide_audience_callback() {
	
		echo '<input name="agls-audience" type="text" id="agls-audience" class="regular-text" value="'. esc_attr( get_option('agls-audience') ) . '"  />';
		
	}
	
	public static function wpagls_site_wide_coverage_callback() {
	
		echo '<input name="agls-coverage" type="text" id="agls-coverage" class="regular-text" value="'. esc_attr( get_option('agls-coverage') ) . '"  />';
		
	}
	
	public static function wpagls_site_wide_language_callback() {
	
		echo '<input name="agls-language" type="text" id="agls-language" class="regular-text" value="'. esc_attr( get_option('agls-language') ) . '"  />';
		
	}
	
	public static function wpagls_site_wide_mandate_callback() {
	
		echo '<input name="agls-mandate" type="text" id="agls-mandate" class="regular-text" value="'. esc_attr( get_option('agls-mandate') ) . '"  />';
		
	}
	
	public static function wpagls_site_wide_rights_callback() {
	
		echo '<input name="agls-rights" type="text" id="agls-rights" class="regular-text" value="'. esc_attr( get_option('agls-rights') ) . '"  />';
		
	}
	
	public static function wpagls_site_wide_callback() {
		
		//do nothing
		
	}

}

}

