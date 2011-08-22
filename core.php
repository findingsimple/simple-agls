<?php

class WPAGLS_Core {
	private static $options;
	
	function init($options) {
		self::$options = $options;
				
		add_action('wp_head', array( __CLASS__, 'display_meta' ) , 9999);
	
	}
	
	//Display Meta
	function display_meta( $atts, $content = null) {
		$options = self::$options->get();
	
		//WPAGLS_Core::some_function();
		
	} 
	

	
}
?>