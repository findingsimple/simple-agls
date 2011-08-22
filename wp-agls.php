<?php
/*
Plugin Name: WP-AGLS
Plugin URI: http://plugins.findingsimple.com
Description: Simple plugin that helps integrate AGLS meta data into you WordPress powered site.
Version: 0.1
Author: Jason Conroy
Author URI: http://findingsimple.com
License: GPL2
*/
/*  Copyright 2011  Jason Conroy  (email : plugins@findingsimple.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//

require dirname(__FILE__) . '/scb/load.php';

function _wpagls_init() {

	load_plugin_textdomain( 'wpagls', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );

	require_once dirname( __FILE__ ) . '/core.php';

	// Creating an options object
	$options = new scbOptions( 'wpagls', __FILE__, array(
		'property_code' => __( 'ca-pub-', 'wpdfp' ),
		'ad_units' => __( '', 'wpdfp' ),
		'use_iframe' => __( 'no', 'wpdfp' )
	) );
	
	WPAGLS_Core::init( $options );

	// Creating a settings page object
	if ( is_admin() ) {
		require_once dirname( __FILE__ ) . '/admin/admin.php';
		new WPAGLS_Admin_Page( __FILE__, $options );
	}
}
scb_init( '_wpagls_init' );