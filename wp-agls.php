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

function wpagls_init() {

	load_plugin_textdomain( 'wpagls', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );

	require_once dirname( __FILE__ ) . '/core.php';
	
	if (class_exists("WPAGLS_Core")) {
		$wpagls = new WPAGLS_Core();
	}

}

add_action('init','wpagls_init');