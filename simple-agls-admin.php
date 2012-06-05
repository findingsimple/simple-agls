<?php

if ( ! class_exists( 'SIMPLE_AGLS_Admin' ) ) {

/**
 * So that themes and other plugins can customise the text domain, the SIMPLE_AGLS_Admin should
 * not be initialized until after the plugins_loaded and after_setup_theme hooks.
 * However, it also needs to run early on the init hook.
 *
 * @author Jason Conroy <jason@findingsimple.com>
 * @package WP AGLS
 * @since 1.0
 */
function simple_initialize_agls_admin() {
	SIMPLE_AGLS_Admin::init();
}
add_action( 'init', 'simple_initialize_agls_admin', -1 );


class SIMPLE_AGLS_Admin {

	public static function init() {  

		/* create custom plugin settings menu */
		add_action( 'admin_menu',  __CLASS__ . '::simple_agls_create_menu' );

		/* Add the post AGLS meta box on the 'add_meta_boxes' hook. */
		add_action( 'add_meta_boxes', __CLASS__ . '::add_agls_meta_box' );

		/* Save the post AGLS meta box data on the 'save_post' hook. */
		add_action( 'save_post', __CLASS__ . '::save_agls_meta_box', 10, 2 );

	}

	public static function simple_agls_create_menu() {

		//create new top-level menu
		add_options_page( 'AGLS Settings', 'AGLS', 'administrator', 'simple_agls', __CLASS__ . '::simple_agls_settings_page' );

		//call register settings function
		add_action( 'admin_init',  __CLASS__ . '::register_mysettings' );

	}


	public static function register_mysettings() {
	
		$page = 'simple_agls-settings'; 

		//general settings
		add_settings_section( 
			'simple_agls-general', 
			'General Settings',
			__CLASS__ . '::simple_agls_general_callback',
			$page
		);

		add_settings_field(
			'simple_agls-toggle-show-schema',
			'Toggle Schema Attribute',
			__CLASS__ . '::simple_agls_toggle_show_schema_callback',
			$page,
			'simple_agls-general'
		);

		//site-wide settings
		add_settings_section( 
			'simple_agls-site-wide', 
			'Site-wide Values',
			__CLASS__ . '::simple_agls_site_wide_callback',
			$page
		);

		add_settings_field(
			'agls-creator-corporate-name', 
			'Creator corporate name',
			__CLASS__ . '::simple_agls_site_wide_corporate_name_callback',
			$page,
			'simple_agls-site-wide'
		);

		add_settings_field(
			'agls-creator-address',
			'Creator corporate address',
			__CLASS__ . '::simple_agls_site_wide_corporate_address_callback',
			$page,
			'simple_agls-site-wide'
		);

		add_settings_field(
			'agls-creator-contact',
			'Creator corporate contact',
			__CLASS__ . '::simple_agls_site_wide_corporate_contact_callback',
			$page,
			'simple_agls-site-wide'
		);

		add_settings_field(
			'agls-function',
			'Function value',
			__CLASS__ . '::simple_agls_site_wide_function_callback',
			$page,
			'simple_agls-site-wide'
		);

		add_settings_field(
			'agls-audience',
			'Audience value',
			__CLASS__ . '::simple_agls_site_wide_audience_callback',
			$page,
			'simple_agls-site-wide'
		);

		add_settings_field(
			'agls-coverage',
			'Coverage value',
			__CLASS__ . '::simple_agls_site_wide_coverage_callback',
			$page,
			'simple_agls-site-wide'
		);

		add_settings_field(
			'agls-language',
			'Language value',
			__CLASS__ . '::simple_agls_site_wide_language_callback',
			$page,
			'simple_agls-site-wide'
		);

		add_settings_field(
			'agls-mandate',
			'Mandate value',
			__CLASS__ . '::simple_agls_site_wide_mandate_callback',
			$page,
			'simple_agls-site-wide'
		);

		add_settings_field(
			'agls-rights',
			'Rights value',
			__CLASS__ . '::simple_agls_site_wide_rights_callback',
			$page,
			'simple_agls-site-wide'
		);
		
		//register our settings
		
		register_setting( $page, 'simple_agls-toggle-show-schema' );

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

	public static function simple_agls_settings_page() {
	
		$page = 'simple_agls-settings'; 
	
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

	public static function simple_agls_general_callback() {
		
		//do nothing
		
	}
	
	public static function simple_agls_toggle_show_schema_callback() {
	
		echo '<input name="simple_agls-toggle-show-schema" id="simple_agls-toggle-show-schema" type="checkbox" value="1" class="code" ' . checked( 1, get_option('simple_agls-toggle-show-schema'), false ) . ' /> Show schema';
		
	}
	
	public static function simple_agls_site_wide_corporate_name_callback() {
	
		echo '<input name="agls-creator-corporate-name" type="text" id="agls-creator-corporate-name" class="regular-text" value="'. esc_attr( get_option('agls-creator-corporate-name') ) . '"  />';
		
	}
	
	public static function simple_agls_site_wide_corporate_address_callback() {
	
		echo '<input name="agls-creator-address" type="text" id="agls-creator-address" class="regular-text" value="'. esc_attr( get_option('agls-creator-address') ) . '" />';
	
	}
	
	public static function simple_agls_site_wide_corporate_contact_callback() {
	
		echo '<input name="agls-creator-contact" type="text" id="agls-creator-contact" class="regular-text" value="'. esc_attr( get_option('agls-creator-contact') ) . '" />';
		
	}

	public static function simple_agls_site_wide_function_callback() {
	
		echo '<input name="agls-function" type="text" id="agls-function" class="regular-text" value="'. esc_attr( get_option('agls-function') ) . '" />';
		
	}

	public static function simple_agls_site_wide_audience_callback() {
	
		echo '<input name="agls-audience" type="text" id="agls-audience" class="regular-text" value="'. esc_attr( get_option('agls-audience') ) . '"  />';
		
	}
	
	public static function simple_agls_site_wide_coverage_callback() {
	
		echo '<input name="agls-coverage" type="text" id="agls-coverage" class="regular-text" value="'. esc_attr( get_option('agls-coverage') ) . '"  />';
		
	}
	
	public static function simple_agls_site_wide_language_callback() {
	
		echo '<input name="agls-language" type="text" id="agls-language" class="regular-text" value="'. esc_attr( get_option('agls-language') ) . '"  />';
		
	}
	
	public static function simple_agls_site_wide_mandate_callback() {
	
		echo '<input name="agls-mandate" type="text" id="agls-mandate" class="regular-text" value="'. esc_attr( get_option('agls-mandate') ) . '"  />';
		
	}
	
	public static function simple_agls_site_wide_rights_callback() {
	
		echo '<input name="agls-rights" type="text" id="agls-rights" class="regular-text" value="'. esc_attr( get_option('agls-rights') ) . '"  />';
		
	}
	
	public static function simple_agls_site_wide_callback() {
		
		//do nothing
		
	}

	/**
	 * Adds the post SEO meta box for all public post types.
	 *
	 * @since 1.2.0
	 */
	public static function add_agls_meta_box() {

		/* Get all available public post types. */
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		/* Loop through each post type, adding the meta box for each type's post editor screen. */
		foreach ( $post_types as $type )
			add_meta_box( 'agls-meta-data', sprintf( __( 'AGLS Metadata', SIMPLE_AGLS::$text_domain ), $type->labels->singular_name ), __CLASS__ . '::agls_meta_box_display', $type->name, 'normal', 'high' );
	}

	/**
	 * Displays the AGLS meta box.
	 *
	 * @since 1.2.0
	 */
	public static function agls_meta_box_display( $object, $box ) { ?>
	
		<?php $args = array (
			'echo' => false
		); ?>

		<input type="hidden" name="eeo-agls-meta-box-seo" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />

		<div class="post-settings">

		<p>
			<label for="agls-title"><?php _e( 'Title:', SIMPLE_AGLS::$text_domain ); ?></label>
			<br />
			<input type="text" name="agls-title" id="agls-title" value="<?php echo esc_attr( get_post_meta( $object->ID, 'DCTERMS.title', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
			<br />
			<span style="color:#aaa;">Default: <?php $default = SIMPLE_AGLS::agls_title( $args ); echo $default['content']; ?></span>
		</p>

		<p>
			<label for="agls-description"><?php _e( 'Description:', SIMPLE_AGLS::$text_domain ); ?></label>
			<br />
			<textarea name="agls-description" id="agls-description" cols="60" rows="2" tabindex="30" style="width: 99%;"><?php echo esc_textarea( get_post_meta( $object->ID, 'DCTERMS.description', true ) ); ?></textarea>
			<br />
			<span style="color:#aaa;">Default: <?php $default = SIMPLE_AGLS::agls_description( $args ); echo $default['content']; ?></span>
		</p>

		<p>
			<label for="agls-subject"><?php _e( 'Subject:', SIMPLE_AGLS::$text_domain ); ?></label>
			<br />
			<input type="text" name="agls-subject" id="agls-subject" value="<?php echo esc_attr( get_post_meta( $object->ID, 'DCTERMS.subject', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
			<br />
			<span style="color:#aaa;">Default: <?php $default = SIMPLE_AGLS::agls_subject( $args ); echo $default['content']; ?></span>
		</p>

		<p>
			<label for="agls-publisher"><?php _e( 'Publisher:', SIMPLE_AGLS::$text_domain ); ?></label>
			<br />
			<input type="text" name="agls-publisher" id="agls-publisher" value="<?php echo esc_attr( get_post_meta( $object->ID, 'DCTERMS.publisher', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
			<br />
			<span style="color:#aaa;">Default: <?php $default = SIMPLE_AGLS::agls_publisher( $args ); echo $default['content']; ?></span>
		</p>

		<p>
			<label for="agls-availability"><?php _e( 'Availability:', SIMPLE_AGLS::$text_domain ); ?></label>
			<br />
			<input type="text" name="agls-availability" id="agls-availability" value="<?php echo esc_attr( get_post_meta( $object->ID, 'AGLSTERMS.availability', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
		</p>

		<p>
			<label for="agls-function"><?php _e( 'Function:', SIMPLE_AGLS::$text_domain ); ?></label>
			<br />
			<input type="text" name="agls-function" id="agls-function" value="<?php echo esc_attr( get_post_meta( $object->ID, 'AGLSTERMS.function', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
			<br />
			<span style="color:#aaa;">Default: <?php $default = SIMPLE_AGLS::agls_function( $args ); echo $default['content']; ?></span>
		</p>

		<p>
			<label for="agls-mandate"><?php _e( 'Mandate:', SIMPLE_AGLS::$text_domain ); ?></label>
			<br />
			<input type="text" name="agls-mandate" id="agls-mandate" value="<?php echo esc_attr( get_post_meta( $object->ID, 'AGLSTERMS.mandate', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
			<br />
			<span style="color:#aaa;">Default: <?php $default = SIMPLE_AGLS::agls_mandate( $args ); echo $default['content']; ?></span>
		</p>

		<p>
			<label for="agls-audience"><?php _e( 'Audience:', SIMPLE_AGLS::$text_domain ); ?></label>
			<br />
			<input type="text" name="agls-audience" id="agls-audience" value="<?php echo esc_attr( get_post_meta( $object->ID, 'DCTERMS.audience', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
			<br />
			<span style="color:#aaa;">Default: <?php $default = SIMPLE_AGLS::agls_audience( $args ); echo $default['content']; ?></span>
		</p>

		<p>
			<label for="agls-coverage"><?php _e( 'Coverage:', SIMPLE_AGLS::$text_domain ); ?></label>
			<br />
			<input type="text" name="agls-coverage" id="agls-coverage" value="<?php echo esc_attr( get_post_meta( $object->ID, 'DCTERMS.coverage', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
			<br />
			<span style="color:#aaa;">Default: <?php $default = SIMPLE_AGLS::agls_coverage( $args ); echo $default['content']; ?></span>
		</p>

		<p>
			<label for="agls-relation"><?php _e( 'Relation:', SIMPLE_AGLS::$text_domain ); ?></label>
			<br />
			<input type="text" name="agls-relation" id="agls-relation" value="<?php echo esc_attr( get_post_meta( $object->ID, 'DCTERMS.relation', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
		</p>

		<p>
			<label for="agls-rights"><?php _e( 'Rights:', SIMPLE_AGLS::$text_domain ); ?></label>
			<br />
			<input type="text" name="agls-rights" id="agls-rights" value="<?php echo esc_attr( get_post_meta( $object->ID, 'DCTERMS.rights', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
			<br />
			<span style="color:#aaa;">Default: <?php $default = SIMPLE_AGLS::agls_rights( $args ); echo $default['content']; ?></span>
		</p>

		<p>
			<label for="agls-source"><?php _e( 'Source:', SIMPLE_AGLS::$text_domain ); ?></label>
			<br />
			<input type="text" name="agls-source" id="agls-source" value="<?php echo esc_attr( get_post_meta( $object->ID, 'DCTERMS.source', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
		</p>

		</div><!-- .form-table --><?php
	}

	/**
	 * Saves the post AGLS meta box settings as post metadata.
	 *
	 */
	public static function save_agls_meta_box( $post_id, $post ) {

		/* Verify the nonce before proceeding. */
		if ( !isset( $_POST['eeo-agls-meta-box-seo'] ) || !wp_verify_nonce( $_POST['eeo-agls-meta-box-seo'], basename( __FILE__ ) ) )
			return $post_id;

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
			return $post_id;

		$meta = array(
			'DCTERMS.title' => strip_tags( $_POST['agls-title'] ),
			'DCTERMS.description' => strip_tags( $_POST['agls-description'] ),
			'DCTERMS.subject' => strip_tags( $_POST['agls-subject'] ),
			'DCTERMS.publisher' => strip_tags( $_POST['agls-publisher'] ),
			'AGLSTERMS.availability' => strip_tags( $_POST['agls-availability'] ),
			'AGLSTERMS.function' => strip_tags( $_POST['agls-function'] ),
			'AGLSTERMS.mandate' => strip_tags( $_POST['agls-mandate'] ),
			'DCTERMS.audience' => strip_tags( $_POST['agls-audience'] ),
			'DCTERMS.coverage' => strip_tags( $_POST['agls-coverage'] ),
			'DCTERMS.relation' => strip_tags( $_POST['agls-relation'] ),
			'DCTERMS.rights' => strip_tags( $_POST['agls-rights'] ),
			'DCTERMS.source' => strip_tags( $_POST['agls-source'] )
		);

		foreach ( $meta as $meta_key => $new_meta_value ) {

			/* Get the meta value of the custom field key. */
			$meta_value = get_post_meta( $post_id, $meta_key, true );

			/* If a new meta value was added and there was no previous value, add it. */
			if ( $new_meta_value && '' == $meta_value )
				add_post_meta( $post_id, $meta_key, $new_meta_value, true );

			/* If the new meta value does not match the old value, update it. */
			elseif ( $new_meta_value && $new_meta_value != $meta_value )
				update_post_meta( $post_id, $meta_key, $new_meta_value );

			/* If there is no new meta value but an old value exists, delete it. */
			elseif ( '' == $new_meta_value && $meta_value )
				delete_post_meta( $post_id, $meta_key, $meta_value );
		}
	}

}

}


