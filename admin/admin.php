<?php

/* Settings for schema selection/setting site-wide */

if (!class_exists("WPAGLS_Admin")) {
	
	class WPAGLS_Admin {
	
		function __construct() {  
			
			// create custom plugin settings menu
			add_action('admin_menu',  array($this, 'wpagls_create_menu') );
		
		}

		function wpagls_create_menu() {
				
			//create new top-level menu
			add_menu_page('WP-AGLS Settings', 'WP-AGLS', 'administrator', __FILE__, array($this, 'wpagls_settings_page' ) , plugins_url('/images/icon.png', __FILE__ ) );
		
			//call register settings function
			add_action( 'admin_init',  array($this, 'register_mysettings' ) );
			
		}


		function register_mysettings() {
		
			$page = 'wpagls-settings'; 

			//general settings
			
			add_settings_section( 
			 	'wpagls-general', 
			 	'General Settings',
			 	array($this , 'wpagls_general_callback' ),
			 	$page
			 );
			 
 			add_settings_field(
 				'wpagls-toggle-show-schema',
				'Toggle Schema Attribute',
			 	array($this , 'wpagls_toggle_show_schema_callback' ),
				$page ,
				'wpagls-general'
			);
			
			//site-wide settings
			add_settings_section( 
			 	'wpagls-site-wide', 
			 	'Site-wide Values',
			 	array($this , 'wpagls_site_wide_callback' ),
			 	$page
			 );		

 			add_settings_field(
 				'agls-creator-corporate-name', 
 				'Creator corporate name',
			 	array($this , 'wpagls_site_wide_corporate_name_callback' ),
				$page ,
				'wpagls-site-wide'
			);
			
 			add_settings_field(
 				'agls-creator-address',
				'Creator corporate address',
			 	array($this , 'wpagls_site_wide_corporate_address_callback' ),
				$page ,
				'wpagls-site-wide'
			);
			
 			add_settings_field(
 				'agls-creator-contact',
				'Creator corporate contact',
			 	array($this , 'wpagls_site_wide_corporate_contact_callback' ),
				$page ,
				'wpagls-site-wide'
			);
			
 			add_settings_field(
 				'agls-function',
				'Function value',
			 	array($this , 'wpagls_site_wide_function_callback' ),
				$page ,
				'wpagls-site-wide'
			);
			
 			add_settings_field(
 				'agls-audience',
				'Audience value',
			 	array($this , 'wpagls_site_wide_audience_callback' ),
				$page ,
				'wpagls-site-wide'
			);
			
 			add_settings_field(
 				'agls-coverage',
				'Coverage value',
			 	array($this , 'wpagls_site_wide_coverage_callback' ),
				$page ,
				'wpagls-site-wide'
			);
			
 			add_settings_field(
 				'agls-language',
				'Language value',
			 	array($this , 'wpagls_site_wide_language_callback' ),
				$page ,
				'wpagls-site-wide'
			);
			
 			add_settings_field(
 				'agls-mandate',
				'Mandate value',
			 	array($this , 'wpagls_site_wide_mandate_callback' ),
				$page ,
				'wpagls-site-wide'
			);
			
 			add_settings_field(
 				'agls-rights',
				'Rights value',
			 	array($this , 'wpagls_site_wide_rights_callback' ),
				$page ,
				'wpagls-site-wide'
			);
			
			//register our settings
			
			register_setting( $page , 'wpagls-toggle-show-schema' );

			register_setting( $page , 'agls-creator-corporate-name' );
			register_setting( $page , 'agls-creator-address' );
			register_setting( $page , 'agls-creator-contact' );
			
			register_setting( $page , 'agls-function' );
			register_setting( $page , 'agls-audience' );
			register_setting( $page , 'agls-coverage' );
			register_setting( $page , 'agls-language' );
			register_setting( $page , 'agls-mandate' );
			register_setting( $page , 'agls-rights' );

		}

		function wpagls_settings_page() {
		
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

		function wpagls_general_callback() {
			
			//do nothing
			
		}
		
		function wpagls_toggle_show_schema_callback() {
		
			echo '<input name="wpagls-toggle-show-schema" id="wpagls-toggle-show-schema" type="checkbox" value="1" class="code" ' . checked( 1, get_option('wpagls-toggle-show-schema'), false ) . ' /> Show schema';
			
		}
		
		function wpagls_site_wide_corporate_name_callback() {
		
			echo '<input name="agls-creator-corporate-name" type="text" id="agls-creator-corporate-name" class="regular-text" value="'. esc_attr( get_option('agls-creator-corporate-name') ) . '"  />';
			
		}
		
		function wpagls_site_wide_corporate_address_callback() {
		
			echo '<input name="agls-creator-address" type="text" id="agls-creator-address" class="regular-text" value="'. esc_attr( get_option('agls-creator-address') ) . '" />';
		
		}
		
		function wpagls_site_wide_corporate_contact_callback() {
		
			echo '<input name="agls-creator-contact" type="text" id="agls-creator-contact" class="regular-text" value="'. esc_attr( get_option('agls-creator-contact') ) . '" />';
			
		}

		function wpagls_site_wide_function_callback() {
		
			echo '<input name="agls-function" type="text" id="agls-function" class="regular-text" value="'. esc_attr( get_option('agls-function') ) . '" />';
			
		}

		function wpagls_site_wide_audience_callback() {
		
			echo '<input name="agls-audience" type="text" id="agls-audience" class="regular-text" value="'. esc_attr( get_option('agls-audience') ) . '"  />';
			
		}
		
		function wpagls_site_wide_coverage_callback() {
		
			echo '<input name="agls-coverage" type="text" id="agls-coverage" class="regular-text" value="'. esc_attr( get_option('agls-coverage') ) . '"  />';
			
		}
		
		function wpagls_site_wide_language_callback() {
		
			echo '<input name="agls-language" type="text" id="agls-language" class="regular-text" value="'. esc_attr( get_option('agls-language') ) . '"  />';
			
		}
		
		function wpagls_site_wide_mandate_callback() {
		
			echo '<input name="agls-mandate" type="text" id="agls-mandate" class="regular-text" value="'. esc_attr( get_option('agls-mandate') ) . '"  />';
			
		}
		
		function wpagls_site_wide_rights_callback() {
		
			echo '<input name="agls-rights" type="text" id="agls-rights" class="regular-text" value="'. esc_attr( get_option('agls-rights') ) . '"  />';
			
		}
		
		function wpagls_site_wide_callback() {
			
			//do nothing
			
		}

	} /* end WPAGLS_Admin */
	
} /* end if class exists */


?>