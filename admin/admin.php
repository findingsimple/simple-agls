<?php

class WPAGLS_Admin_Page extends scbAdminPage {

	function setup() {
	
		$this->textdomain = 'wpagls';
	
		$this->args = array(
			'page_title' => 'WP-DFP Settings',
			'menu_title' => __( 'DFP', $this->textdomain ),
			'page_slug' => 'wp-dfp',
		);
	}

	function page_content() {
		echo html( 'h3', 'Account Details' );
		
		echo $this->form_table( array(
			array(
				'title' => __( 'Property Code', $this->textdomain ),
				'type' => 'text',
				'name' => 'property_code',
				'desc' => __( 'Found in the DFP admin area under Network Settings', $this->textdomain )
			),
			array(
				'title' => __( 'Ad Unit Names', $this->textdomain ),
				'type' => 'textarea',
				'name' => 'ad_units',
				'extra' => 'rows="5" cols="50"',
				'desc' => __( 'Please use a new line for each name', $this->textdomain )
			),
			array(
				'title' => __( 'Use Iframe Technique', $this->textdomain ),
				'type' => 'radio',
				'name' => 'use_iframe',
				'value' => array(
					'yes' => 'Yes',
					'no' => 'No'
				),
				'selected' => 'no'
			),
		) );

	}
}

?>