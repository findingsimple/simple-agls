<?php
/*
Plugin Name: Simple AGLS
Plugin URI: http://plugins.findingsimple.com
Description: Simple plugin that helps integrate AGLS meta data into your WordPress powered site.
Version: 1.0
Author: Finding Simple
Author URI: http://findingsimple.com
License: GPL2
*/
/*  Copyright 2012  Jason Conroy  (email : plugins@findingsimple.com)

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
/**
 * @package Simple AGLS
 * @version 1.0
 * @author Jason Conroy <jason@findingsimple.com>
 * @copyright Copyright (c) 2012 Finding Simple
 * @link http://findingsimple.com/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

require_once dirname( __FILE__ ) . '/simple-agls-admin.php';

if ( ! class_exists( 'SIMPLE_AGLS' ) ) {

/**
 * So that themes and other plugins can customise the text domain, the FS_AGLS should
 * not be initialized until after the plugins_loaded and after_setup_theme hooks.
 * However, it also needs to run early on the init hook.
 *
 * @author Jason Conroy <jason@findingsimple.com>
 * @package SIMPLE-AGLS
 * @since 1.0
 */
function initialize_simple_agls() {
	SIMPLE_AGLS::init();
}
add_action( 'init', 'initialize_simple_agls', -1 );


class SIMPLE_AGLS {

	static $text_domain, $defaults;

	/**
	 * Hook into WordPress where appropriate.
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function init() {

		self::$text_domain = apply_filters( 'simple_agls_text_domain', 'SIMPLE_AGLS' );
		
		self::$defaults = array( 
			'element' => 'meta', 
			'echo' => true, 
			'show_default' => false,
			'before' => '', 
			'after' => "\n",
			'start' => "<!-- SIMPLE-AGLS START -->",
			'end' => "<!-- SIMPLE-AGLS END -->"
		); 
						
		/* Top */
		add_action( 'wp_head', __CLASS__ .'::agls_comment_start', 1 ); 

		/* Specify the schema/s used */
		add_action( 'wp_head', __CLASS__ .'::agls_namespace', 1 ); 

		/* Add mandatory agls <meta> elements to the <head> area. */
		add_action( 'wp_head', __CLASS__ .'::agls_creator', 1 ); 
		add_action( 'wp_head', __CLASS__ .'::agls_date', 1 ); 
		add_action( 'wp_head', __CLASS__ .'::agls_description', 1 ); /* Recommended */
		add_action( 'wp_head', __CLASS__ .'::agls_title', 1 );
		add_action( 'wp_head', __CLASS__ .'::agls_identifier', 1 ); /* Mandatory for online resources */
		add_action( 'wp_head', __CLASS__ .'::agls_publisher', 1 ); /* Mandatory for information resources */
		add_action( 'wp_head', __CLASS__ .'::agls_function', 1 ); /* Recommended if subject is not used */
		add_action( 'wp_head', __CLASS__ .'::agls_subject', 1 ); /* Recommended if function is not used. */

		/* Add conditional agls <meta> elements to the <head> area. */
		add_action( 'wp_head', __CLASS__ .'::agls_availability', 1 );
		add_action( 'wp_head', __CLASS__ .'::agls_language', 1 );

		/* Add optional agls <meta> elements to the <head> area. */
		add_action( 'wp_head', __CLASS__ .'::agls_type', 1 ); 
		add_action( 'wp_head', __CLASS__ .'::agls_audience', 1 );
		add_action( 'wp_head', __CLASS__ .'::agls_contributor', 1 ); 
		add_action( 'wp_head', __CLASS__ .'::agls_coverage', 1 );
		add_action( 'wp_head', __CLASS__ .'::agls_format', 1 );
		add_action( 'wp_head', __CLASS__ .'::agls_mandate', 1 ); 
		add_action( 'wp_head', __CLASS__ .'::agls_relation', 1 );
		add_action( 'wp_head', __CLASS__ .'::agls_rights', 1 );
		add_action( 'wp_head', __CLASS__ .'::agls_source', 1 );

		/* Tail */
		add_action( 'wp_head', __CLASS__ .'::agls_comment_end', 1 ); 

	} 


	/**
	 * Meta start comment
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_comment_start( $args = array() ) {
	
		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_comment_start_args', $args );
		extract( $args, EXTR_SKIP );
		
		if ( !$echo )
			return $before . $start . $after;
		
		echo $before . $start . $after;

	}

	/**
	 * Meta end comment
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_comment_end( $args = array() ) {

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_comment_end_args', $args );
		extract( $args, EXTR_SKIP );

		if ( !$echo )
			return $before . $end . $after;
		
		echo $before . $end . $after;
		
	}

	/**
	 * Specify namespace/s used
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_namespace( $args = array() ) {

		$args['element'] = 'link';
		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_namespace_args', $args );
		extract( $args, EXTR_SKIP );
		
		$attributes = array(
			'rel' => 'schema.DCTERMS',
			'href' => 'http://purl.org/dc/terms/'
		);
		
		if ( !empty($attributes) && (get_option('simple_agls-toggle-dublin-core-namespace') == 1) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

		$attributes = array(
			'rel' => 'schema.AGLSTERMS',
			'href' => 'http://www.agls.gov.au/agls/terms/'
		);
		
		if ( !empty($attributes) && (get_option('simple_agls-toggle-agls-namespace') == 1) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Creator term
	 *
	 * HTML/XHTML syntax - DCTERMS.creator
	 * Definition - An entity primarily responsible for making the resource
	 * Obligation - Mandatory
	 * Syntax encoding schemes - AglsAgent, GOLD, URI
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_creator( $args = array() ) {

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_creator_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		$name = get_option( 'agls-creator-corporate-name' );
		$address = get_option( 'agls-creator-address' );
		$contact = get_option( 'agls-creator-contact' );

		if ( ( !empty($name) ) && ( !empty($address) ) && ( !empty($contact) ) ) {

			$attributes = array(
				'name' => 'DCTERMS.creator',
				'content' => 'CorporateName=' . $name . '; address=' . $address . '; contact=' . $contact . ';'
			);
			
			if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
				$attributes['scheme'] = 'AGLSTERMS.AglsAgent';
			}
		
		}

		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );
		
	}

	/**
	 * Date term
	 *
	 * Auto populates with date created
	 *
	 * HTML/XHTML syntax - DCTERMS.date
	 * Definition - A point or period of time associated with an event in the life of the resource.
	 * Obligation - Mandatory unless a related property is used.
	 * Syntax encoding schemes - ISO8601, XSD.date, XSD.dateTime
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_date( $args = array() ) {

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_date_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		if ( is_singular() ) {

			$attributes = array(
				'name' => 'DCTERMS.date',
				'content' => get_the_date( 'c' )
			);
			
			if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
				$attributes['scheme'] = 'DCTERMS.ISO8601';
			}

		}
		
		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}
	
	/**
	 * Date Modified term
	 *
	 * HTML/XHTML syntax - DCTERMS.modified
	 * Definition - Date on which the resource was changed.
	 * Obligation - Optional — may be used in place of date.
	 * Syntax encoding schemes - ISO8601, XSD.date, XSD.dateTime
	 *
	 * Auto populates with date modified
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_date_modified( $args = array() ) {

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_date_modified_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		if ( is_singular() && ( get_the_modified_time() != get_the_time() ) ) {

			$attributes = array(
				'name' => 'DCTERMS.modified',
				'content' => get_the_modified_time( 'c' )
			);
			
			if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
				$attributes['scheme'] = 'DCTERMS.ISO8601';
			}

		}
		
		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Description term
	 *
	 * HTML/XHTML syntax - DCTERMS.description
	 * Definition - An account of the resource.
	 * Obligation - Recommended.
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_description( $args = array() ) {

		global $post;

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_description_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		/* If viewing the home/posts page, get the site's description. */
		if ( is_home() )
			$description = get_bloginfo( 'description' );

		/* If viewing a singular post. */
		elseif ( is_singular() || is_admin() ) {

			/* By default use the post excerpt. */
			$description = get_post_field( 'post_excerpt', $post->ID );

			$individual = get_post_meta( $post->ID, 'DCTERMS.description', true );

			/* If no description was found and viewing the site's front page, use the site's description. */
			if ( empty( $description ) && is_front_page() )
				$description = get_bloginfo( 'description' );

			if ( !empty( $individual ) && !$show_default )
				$description = $individual;
		}

		/* If viewing an archive page. */
		elseif ( is_archive() ) {

			/* If viewing a user/author archive. */
			if ( is_author() ) {

				/* Get the meta value for the 'Description' user meta key. */
				$description = get_user_meta( get_query_var( 'author' ), 'Description', true );

				/* If no description was found, get the user's description (biographical info). */
				if ( empty( $description ) )
					$description = get_the_author_meta( 'description', get_query_var( 'author' ) );
			}

			/* If viewing a taxonomy term archive, get the term's description. */
			elseif ( is_category() || is_tag() || is_tax() )
				$description = term_description( '', get_query_var( 'taxonomy' ) );

			/* If viewing a custom post type archive. */
			elseif ( is_post_type_archive() ) {

				/* Get the post type object. */
				$post_type = get_post_type_object( get_query_var( 'post_type' ) );

				/* If a description was set for the post type, use it. */
				if ( isset( $post_type->description ) )
					$description = $post_type->description;
			}
		}

		/* Format the description. */
		if ( !empty( $description ) )
			$attributes = array(
				'name' => 'DCTERMS.description',
				'content' => str_replace( array( "\r", "\n", "\t" ), '', esc_attr( strip_tags( $description ) ) )
			);
		
		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Title term
	 *
	 * HTML/XHTML syntax - DCTERMS.title
	 * Definition - A name given to the resource.
	 * Obligation - Mandatory.
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_title( $args = array() ) {

		global $post;

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_title_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		$individual = get_post_meta( $post->ID, 'DCTERMS.title', true );

		if (is_singular() || is_admin() ) {
			$title = get_the_title();
		} else if(is_search()) {
			$title = __( 'Search', self::$text_domain );
		} else if(is_category()) {
			$title = single_cat_title("", false);
		} else if(is_tag()) {
			$title = single_tag_title("", false);
		} else if(is_tax()) {
			$title = single_term_title("", false);
		} else if(is_author()) {
			$id = get_query_var( 'author' );
			$title = get_the_author_meta( 'display_name', $id );
		} else if(is_date()) {
			$title = __( 'Archives by date', self::$text_domain );
		} else if(is_post_type_archive()) {
			$post_type = get_post_type_object( get_query_var( 'post_type' ) );
			$title = post_type_archive_title("", false);
		} else if(is_archive()) {
			$title = __( 'Archives', self::$text_domain );
		} else if(is_404()) {
			$title = __( 'Page Not Found', self::$text_domain );
		} elseif ( is_home() || is_front_page() ) {
			$title = get_bloginfo( 'name' );
		}

		if ( !empty( $individual ) && !$show_default ) 
			$title = $individual;

		if ( !empty( $title ) )
			$attributes = array(
				'name' => 'DCTERMS.title',
				'content' => esc_attr( $title )
			);

		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Identifier term
	 *
	 * Auto populate with the permalink (page url)
	 *
	 * HTML/XHTML syntax - DCTERMS.identifier
	 * Definition - An unambiguous reference to the resource within a given context.
	 * Obligation - Conditional - Mandatory for online resources.
	 * Syntax encoding schemes - DOI,ISBN,ISSN,URI
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_identifier( $args = array() ) {

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_identifier_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		if (is_singular()) {
			$url = get_permalink();
		} else if(is_search()) {
			$search = get_query_var('s');
			$url = get_bloginfo('url') . "/search/". $search;
		} else if(is_category()) {
			$url =  get_category_link( get_queried_object() );
		} else if(is_tag()) {
			$url = get_tag_link( get_queried_object() );
		} else if(is_tax()) {
			$url = get_term_link( get_queried_object() );
		} else if(is_author()) {
			$id = get_query_var( 'author' );
			$url = get_author_posts_url( $id );
		} else if(is_year()) {
			$url = get_year_link( get_query_var('year') );
		} else if(is_month()) {
			$url = get_month_link( get_query_var('year'), get_query_var('monthnum') );
		} else if(is_day()) {
			$url = get_day_link( get_query_var('year'), get_query_var('monthnum'), get_query_var('day') );
		} else if(is_post_type_archive()) {
			$url = get_post_type_archive_link( get_query_var('post_type') );
		} else if(is_home()) {
			$url = get_bloginfo('url');
			$url = preg_replace("~^https?://[^/]+$~", "$0/", $url); //trailing slash
		} else {
			$url = '';
		}
		
		if ( !empty( $url ) )
			$attributes = array(
				'name' => 'DCTERMS.identifier',
				'content' => $url
			);
		
		if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
			$attributes['scheme'] = 'DCTERMS.URI';
		}

		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Availability term
	 *
	 * HTML/XHTML syntax - AGLSTERMS.availability
	 * Definition - How the resource can be obtained or accessed, or contact information for obtaining the resource.
	 * Obligation - Conditional - Mandatory for descriptions of offline resources.
	 * Syntax encoding schemes - AglsAvail, URI
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_availability( $args = array() ) {

		global $post;

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_availability_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();
		
		if (is_singular()) {
			$url = get_permalink();
		} else if(is_search()) {
			$search = get_query_var('s');
			$url = get_bloginfo('url') . "/search/". $search;
		} else if(is_category()) {
			$url =  get_category_link( get_queried_object() );
		} else if(is_tag()) {
			$url = get_tag_link( get_queried_object() );
		} else if(is_tax()) {
			$url = get_term_link( get_queried_object() );
		} else if(is_author()) {
			$id = get_query_var( 'author' );
			$url = get_author_posts_url( $id );
		} else if(is_year()) {
			$url = get_year_link( get_query_var('year') );
		} else if(is_month()) {
			$url = get_month_link( get_query_var('year'), get_query_var('monthnum') );
		} else if(is_day()) {
			$url = get_day_link( get_query_var('year'), get_query_var('monthnum'), get_query_var('day') );
		} else if(is_post_type_archive()) {
			$url = get_post_type_archive_link( get_query_var('post_type') );
		} else if(is_home()) {
			$url = get_bloginfo('url');
			$url = preg_replace("~^https?://[^/]+$~", "$0/", $url); //trailing slash
		} else {
			$url = '';
		}

		if ( !empty( $url ) )
			$attributes = array(
				'name' => 'AGLSTERMS.availability',
				'content' => $url
			);
			
		if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
			$attributes['scheme'] = 'DCTERMS.URI';
		}

		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Publisher term
	 *
	 * HTML/XHTML syntax - DCTERMS.publisher
	 * Definition - An entity responsible for making the resource available.
	 * Obligation - Conditional—Mandatory for information resources (optional for descriptions of services).
	 * Syntax encoding schemes - AglsAgent, GOLD, URI
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_publisher( $args = array() ) {

		global $post;

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_publisher_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();
		
		$publisher = '';

		$name = get_option( 'agls-creator-corporate-name' );
		$address = get_option( 'agls-creator-address' );
		$contact = get_option( 'agls-creator-contact' );

		if ( ( !empty($name) ) && ( !empty($address) ) && ( !empty($contact) ) ) {
			$default = 'CorporateName=' . $name . '; address=' . $address . '; contact=' . $contact . ';';
		} else {
			$default = '';
		}

		$sitewide = get_option( 'agls-publisher' );
		$individual = get_post_meta( $post->ID, 'DCTERMS.publisher', true );
		
		/* Use default */
		if (!empty($default))
			$publisher = $default;
		
		/* Override the default with the sitewide if set */
		if (!empty($sitewide))
			$publisher = $sitewide;
		
		/* If an individual publisher has been set and is not being overridden by default use individual */
		if (!empty($individual) && !$show_default)
			$publisher = $individual;

		if ( !empty($publisher ) )
			$attributes = array(
				'name' => 'DCTERMS.publisher',
				'content' => $publisher
			);
			
		if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
			$attributes['scheme'] = 'AGLSTERMS.AglsAgent';
		}

		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Type term
	 *
	 * Auto populate with the default type "text" - all web pages are text http://dublincore.org/documents/2000/07/11/dcmi-type-vocabulary/#text
	 *
	 * HTML/XHTML syntax - DCTERMS.type
	 * Definition -￼The nature or genre of the resource.
	 * Obligation - Optional
	 * Vocabulary encoding schemes -￼DCMIType
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 *
	 * http://www.agls.gov.au/documents/agls-document/ contains a list of preferred types
	 *
	 */
	public static function agls_type( $args = array() ) {

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_type_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		$attributes = array(
			'name' => 'DCTERMS.type',
			'content' => 'text'
		);
		
		if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
			$attributes['scheme'] = 'DCTERMS.DCMIType';
		}

		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Function term
	 *
	 * HTML/XHTML syntax - AGLSTERMS.function
	 * Definition - The business function to which the resource relates.
	 * Obligation - Recommended if subject is not used.
	 * Vocabulary encoding scheme - AGIFT
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 *
	 * Government agencies may use the Australian Governments’ Interactive Functions 
	 * Thesaurus (AGIFT) as a source of function terms and a Vocabulary Encoding 
	 * Scheme. 
	 *
	 */
	public static function agls_function( $args = array() ) {

		global $post;

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_function_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();
		
		/* This is the sitewide value */
		$default = get_option( 'agls-function' );

		$individual = get_post_meta( $post->ID, 'AGLSTERMS.function', true );

		$function = '';

		if (!empty($default))
			$function = $default;

		if (!empty($individual) && !$show_default)
			$function = $individual;

		if (!empty($function)) 
			$attributes = array(
				'name' => 'AGLSTERMS.function',
				'content' => esc_attr( $function )
			);
			
		if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
			$attributes['scheme'] = 'AGLSTERMS.AGIFT';
		}

		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Subject term
	 *
	 * HTML/XHTML syntax - DCTERMS.subject
	 * Definition - The topic of the resource.
	 * Obligation -￼Recommended if function is not used.
	 * Vocabulary encoding scheme - APAIS, APT, LCSH, MESH, TAGS
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_subject( $args = array() ) {

		global $post;

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_subject_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		/* Set an empty $subject variable. */
		$subject = '';

		/* If on a singular post and not a preview. */
		if ( is_singular() || is_admin() ) {

			/* Get the meta value for the 'DCTERMS.subject' meta key. */
			$subject = get_post_meta( $post->ID, 'DCTERMS.subject', true );
			
			/* If no subject were found. */
			if ( empty( $subject ) || $show_default ) {
				
				/* Set subject as array */
				$subject = array();

				/* Get all taxonomies for the current post type. */
				$taxonomies = get_object_taxonomies( $post->post_type );
				
				/* If taxonomies were found for the post type. */
				if ( is_array( $taxonomies ) ) {

					/* Loop through the taxonomies, getting the terms for the current post. */
					foreach ( $taxonomies as $tax ) {

						if ( $terms = get_the_term_list( $post->ID, $tax, '', ', ', '' ) )
							$subject[] = $terms;
					}

					/* If keywords were found, join the array into a comma-separated string. */
					if ( !empty( $subject) )
						$subject = join( ', ', $subject );
				}
			}
		}

		/* If we have keywords, format for output. */
		if ( !empty( $subject ) )
			$attributes = array(
				'name' => 'DCTERMS.subject',
				'content' => esc_attr( strip_tags( $subject ) )
			);
			
		if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes )  ) {
			$attributes['scheme'] = 'AGLSTERMS.TAGS';
		}
			
		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Audience term
	 *
	 * HTML/XHTML syntax - DCTERMS.audience
	 * Definition -￼A class of entity for whom the resource is intended or useful.
	 * Obligation - Optional
	 * Vocabulary encoding schemes -￼agls-audience, ANZSCO, ANZSIC, edna-audience
	 *
	 * If no audience set, auto populates with All
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_audience( $args = array() ) {

		global $post;

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_audience_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		$default = get_option( 'agls-audience' );

		$individual = get_post_meta( $post->ID, 'DCTERMS.audience', true );

		$audience = 'All';

		if (!empty($default))
			$audience = $default;

		if (!empty($individual) && !$show_default)
			$audience = $individual;

		if (!empty($audience))		
			$attributes = array(
				'name' => 'DCTERMS.audience',
				'content' => $audience
			);
			
		if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
			$attributes['scheme'] = 'AGLSTERMS.agls-audience';
		}
			
		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Coverage term
	 *
	 * HTML/XHTML syntax -￼DCTERMS.coverage
	 * Definition -￼The spatial or temporal topic of the resource, the spatial applicability of the resource, or the jurisdiction under which the resource is relevant.
	 * Obligation - Optional
	 * Syntax encoding schemes -￼Box, Point
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_coverage( $args = array() ) {

		global $post;

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_coverage_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		$default = get_option( 'agls-coverage' );

		$individual = get_post_meta( $post->ID, 'DCTERMS.coverage', true );

		$coverage = '';

		if (!empty($default))
			$coverage = $default;

		if (!empty($individual) && !$show_default)
			$coverage = $individual;

		if (!empty($coverage)) 
			$attributes = array(
				'name' => 'DCTERMS.coverage',
				'content' => esc_attr( $coverage )
			);
			
		if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
			$attributes['scheme'] = '';
		}
			
		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );
		
	}

	/**
	 * Language term
	 *
	 * Defaults to "en-AU" if not set site-wide
	 *
	 * HTML/XHTML syntax - DCTERMS.language
	 * Definition - A language of the resource.
	 * Obligation - Recommended where the language of the resource is not English.
	 * Syntax encoding scheme - RFC4646
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_language( $args = array() ) {

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_language_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		$sitewide = get_option( 'agls-language' );
		
		//use WP language setting
		$language = get_bloginfo('language');
			
		if (!empty($sitewide) && !$show_default) 
			$language = $sitewide;

		if (!empty($language)) 
			$attributes = array(
				'name' => 'DCTERMS.language',
				'content' => esc_attr( $language ) 
			);
			
		if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
			$attributes['scheme'] = 'DCTERMS.RFC4646';
		}

		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Contributor term
	 *
	 * HTML/XHTML syntax -￼DCTERMS.contributor
	 * Definition -￼An entity responsible for making contributions to the resource.
	 * Obligation - Optional
	 * Syntax encoding schemes -￼AglsAgent, GOLD, URI
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_contributor( $args = array() ) {

		global $post;

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_contributor_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		$contributor = '';

		/* If viewing a singular post, get the post author's display name. */
		if ( is_singular() )
			$contributor = get_the_author_meta( 'display_name', $post->post_author );

		/* If viewing a user/author archive, get the user's display name. */
		elseif ( is_author() )
			$contributor = get_the_author_meta( 'display_name', $post->ID );

		/* If an author was found, wrap it in the proper HTML and escape the author name. */
		if ( !empty( $contributor ) )
			$attributes = array(
				'name' => 'DCTERMS.contributor',
				'content' => esc_attr( $contributor ) 
			);

		if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
			$attributes['scheme'] = '';
		}


		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Format term
	 *
	 * HTML/XHTML syntax -￼DCTERMS.format
	 * Definition - The file format, physical medium, or dimensions of the resource.
	 * Obligation - Optional
	 * Syntax encoding scheme - IMT
	 *
	 * Auto populate with the doc type
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_format( $args = array() ) {

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_format_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		$attributes = array(
			'name' => 'DCTERMS.format',
			'content' => get_bloginfo( 'html_type' )
		);
		
		if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
			$attributes['scheme'] = 'DCTERMS.IMT';
		}

		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Mandate term
	 *
	 * HTML/XHTML syntax -￼AGLSTERMS.mandate
	 * Definition - A specific legal instrument which requires or drives the creation or provision of the resource.
	 * Obligation - Optional
	 * Syntax encoding scheme - URI
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_mandate( $args = array() ) {

		global $post;

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_mandate_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		$default = get_option( 'agls-mandate' );

		$individual = get_post_meta( $post->ID, 'AGLSTERMS.mandate', true );

		$mandate = '';

		if (!empty($default))
			$mandate = $default;

		if (!empty($individual) && !$show_default)
			$mandate = $individual;

		if (!empty($mandate)) 
			$attributes = array(
				'name' => 'AGLSTERMS.mandate',
				'content' => esc_attr( $mandate )
			);
			
		if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
			$attributes['scheme'] = 'DCTERMS.URI';
		}

		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Relation term
	 *
	 * HTML/XHTML syntax -￼DCTERMS.relation
	 * Definition - A related resource.
	 * Obligation - Optional
	 * Syntax encoding scheme - URI
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_relation( $args = array() ) {

		global $post;

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_relation_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		$relation = get_post_meta( $post->ID, 'DCTERMS.relation', true );

		if (!empty($relation))
			$attributes = array(
				'name' => 'DCTERMS.relation',
				'content' => esc_attr( $relation )
			);
			
		if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
			$attributes['scheme'] = 'DCTERMS.URI';
		}

		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Rights term
	 *
	 * Auto populate copyright information
	 *
	 * HTML/XHTML syntax -￼DCTERMS.rights
	 * Definition - Information about rights held in and over the resource.
	 * Obligation - Optional
	 * Syntax encoding scheme - URI
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_rights( $args = array() ) {
		global $post;

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_rights_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		$sitewide = get_option( 'agls-rights' );

		$individual = get_post_meta( $post->ID, 'DCTERMS.rights', true );

		/* If viewing a singular post, get the post month and year. */
		if ( is_singular() )
			$date = get_the_time( esc_attr__( 'F Y', self::$text_domain ) );

		/* For all other views, get the current year. */
		else
			$date = date( esc_attr__( 'Y', self::$text_domain ) );

		$rights = 'Copyright Commonwealth of Australia' . $date;		

		if (!empty($sitewide))
			$rights = $sitewide;

		if (!empty($individual) && !$show_default)
			$rights = $individual;

		if (!empty($rights))
			$attributes = array(
				'name' => 'DCTERMS.rights',
				'content' => esc_attr( $rights )
			);
			
		if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
			$attributes['scheme'] = '';
		}

		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Source term
	 *
	 * HTML/XHTML syntax -￼DCTERMS.source
	 * Definition - Information about a resource from which the described resource is derived.
	 * Obligation - Optional
	 * Syntax encoding scheme - ISBN, ISSN, URI
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */
	public static function agls_source( $args = array() ) {

		global $post;

		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_source_args', $args );
		extract( $args, EXTR_SKIP );

		$attributes = array();

		$source = get_post_meta( $post->ID, 'DCTERMS.source', true );

		if (!empty($source))
			$attributes = array(
				'name' => 'DCTERMS.source',
				'content' => esc_attr( $source )
			);
			
		if ( ( get_option('simple_agls-toggle-scheme-attribute') == 1 ) && !empty( $attributes ) ) {
			$attributes['scheme'] = '';
		}

		if ( !$echo && !empty($attributes) )
			return SIMPLE_AGLS::agls_output( $attributes , $args );
		
		if ( !empty($attributes) )
			echo SIMPLE_AGLS::agls_output( $attributes , $args );

	}

	/**
	 * Format output according to argument
	 *
	 * @author Jason Conroy <jason@findingsimple.com>
	 * @package SIMPLE-AGLS
	 * @since 1.0
	 */	
	public static function agls_output( $attributes = array() , $args = array() ) {
	
		$args = wp_parse_args( $args, self::$defaults );
		$args = apply_filters( 'agls_output_args', $args );
		extract( $args, EXTR_SKIP );
	
		if ($echo && !empty($attributes) ) {
		
			$tag = $args['before'] . '<' . $element . ' ';
            
            foreach ($attributes as $attribute => $value) {
            	
            	if ( !empty( $value ) ) 
                	$tag .= $attribute . '="' . $value . '" ';
            
            }
            
            $tag .= '/>' . $args['after'];
            
            return $tag;

		} 
		
		return $attributes;
	
	}

}

}