<?php
/*
Plugin Name: Custom Quotes
Plugin URI: https://www.mckaymultimedia.com/
Description: Custom Plugin used to modify general aspects of the MckayMultimedia.com plugins, themes, and overall website.
Version: 1.0.0
Author: Nathan McKay
Author URI: https://www.mckaymultimedia.com/
License: GPLv2 or later
Text Domain: custom-quotes
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

Copyright 2018 Nathan McKay
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) { die; }

// Register javascript and style on initialization
add_action('init', 'custom_quotes_register_script');
function custom_quotes_register_script() {
	wp_register_script( 'custom_quotes_scripts', plugins_url('/js/scripts.js', __FILE__), array('jquery'), '1.0.0' );
	wp_register_style( 'custom_quotes_styles', plugins_url('/css/styles.css', __FILE__), false, '1.0.0', 'all');
}

// Use the registered javascript and style above
add_action('wp_enqueue_scripts', 'custom_quotes_enqueue_style');
function custom_quotes_enqueue_style(){
	wp_enqueue_script( 'custom_quotes_scripts' );
	wp_enqueue_style( 'custom_quotes_styles' );
}

// Register Custom Post Type
function quotes_post_type() {
	$labels = array(
		'name'                  => _x( 'Quotes', 'Post Type General Name', 'custom-quotes' ),
		'singular_name'         => _x( 'Quote', 'Post Type Singular Name', 'custom-quotes' ),
		'menu_name'             => __( 'Quotes', 'custom-quotes' ),
		'name_admin_bar'        => __( 'Quote', 'custom-quotes' ),
		'archives'              => __( 'Quote Archives', 'custom-quotes' ),
		'attributes'            => __( 'Quote Attributes', 'custom-quotes' ),
		'parent_item_colon'     => __( 'Parent Quote:', 'custom-quotes' ),
		'all_items'             => __( 'All Quotes', 'custom-quotes' ),
		'add_new_item'          => __( 'Add New Quote', 'custom-quotes' ),
		'add_new'               => __( 'Add New', 'custom-quotes' ),
		'new_item'              => __( 'New Quote', 'custom-quotes' ),
		'edit_item'             => __( 'Edit Quote', 'custom-quotes' ),
		'update_item'           => __( 'Update Quote', 'custom-quotes' ),
		'view_item'             => __( 'View Quote', 'custom-quotes' ),
		'view_items'            => __( 'View Quotes', 'custom-quotes' ),
		'search_items'          => __( 'Search Quote', 'custom-quotes' ),
		'not_found'             => __( 'Not found', 'custom-quotes' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'custom-quotes' ),
		'featured_image'        => __( 'Featured Image', 'custom-quotes' ),
		'set_featured_image'    => __( 'Set featured image', 'custom-quotes' ),
		'remove_featured_image' => __( 'Remove featured image', 'custom-quotes' ),
		'use_featured_image'    => __( 'Use as featured image', 'custom-quotes' ),
		'insert_into_item'      => __( 'Insert into quote', 'custom-quotes' ),
		'uploaded_to_this_item' => __( 'Uploaded to this quote', 'custom-quotes' ),
		'items_list'            => __( 'Quotes list', 'custom-quotes' ),
		'items_list_navigation' => __( 'Quotes list navigation', 'custom-quotes' ),
		'filter_items_list'     => __( 'Filter quotes list', 'custom-quotes' ),
	);
	$args = array(
		'label'                 => __( 'Quote', 'custom-quotes' ),
		'description'           => __( 'Quote Description', 'custom-quotes' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-format-quote',
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => 'quotes',
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'quotes' ),
  		'show_in_rest'          => true,
  		'rest_base'             => 'quotes-api',
  		'rest_controller_class' => 'WP_REST_Posts_Controller',
  
	);
	register_post_type( 'quotes', $args );
}
add_action( 'init', 'quotes_post_type', 0 );

// Register Custom Post Type Rotator Shortcode
function custom_quotes_random_posts() { 
	$args = array(
		'post_type' => 'quotes',
		'orderby'   => 'rand',
		'posts_per_page' => 1, 
	);
	 
	$the_query = new WP_Query( $args );
	 
	if ( $the_query->have_posts() ) {
	 
		$string .= '<span>';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			/* $string .= '<li><a href="'. get_permalink() .'">'. get_the_title() .'</a></li>'; */ //Only used when returning multiple results. We are returning one post.
			$string .= get_the_content();
		}
		$string .= '</span>';
		/* Restore original Post Data */
		wp_reset_postdata();
	} else {
	 
	$string .= 'no posts found';
	}
	 
	return $string; 
} 
add_shortcode('custom-quotes-random-posts','custom_quotes_random_posts');
add_filter('widget_text', 'do_shortcode'); 