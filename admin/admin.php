<?php

/*

$name = get_option( 'agls_creator_corporate_name' );
$address = get_option( 'agls_creator_address' );
$contact = get_option( 'agls_creator_contact' );
$default = get_option( 'agls_function' );
$default = get_option( 'agls_audience' );
$default = get_option( 'agls_coverage' );
$sitewide = get_option( 'agls_language' );
$default = get_option( 'agls_mandate' );
$sitewide = get_option( 'agls_rights' );


schema option on/off
		
*/

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
			 
			//register our settings
			register_setting( $page , 'wpagls-toggle-show-schema' );

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
		
			echo '<input name="wpagls-toggle-show-schema" id="wpagls-toggle-show-schema" type="checkbox" value="1" class="code" ' . checked( 1, get_option('wpagls-toggle-show-schema'), false ) . ' /> Show "schema"';
			
		}

	} /* end WPAGLS_Admin */
	
} /* end if class exists */


?>