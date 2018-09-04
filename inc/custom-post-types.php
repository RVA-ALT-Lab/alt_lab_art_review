<?php 
//art custom post type

// Register Custom Post Type art
// Post Type Key: art

function create_art_cpt() {

  $labels = array(
    'name' => __( 'art', 'Post Type General Name', 'textdomain' ),
    'singular_name' => __( 'Art', 'Post Type Singular Name', 'textdomain' ),
    'menu_name' => __( 'Art', 'textdomain' ),
    'name_admin_bar' => __( 'Art', 'textdomain' ),
    'archives' => __( 'Art Archives', 'textdomain' ),
    'attributes' => __( 'Art Attributes', 'textdomain' ),
    'parent_item_colon' => __( 'Art:', 'textdomain' ),
    'all_items' => __( 'All art', 'textdomain' ),
    'add_new_item' => __( 'Add New Art', 'textdomain' ),
    'add_new' => __( 'Add New', 'textdomain' ),
    'new_item' => __( 'New Art', 'textdomain' ),
    'edit_item' => __( 'Edit Art', 'textdomain' ),
    'update_item' => __( 'Update Art', 'textdomain' ),
    'view_item' => __( 'View Art', 'textdomain' ),
    'view_items' => __( 'View art', 'textdomain' ),
    'search_items' => __( 'Search art', 'textdomain' ),
    'not_found' => __( 'Not found', 'textdomain' ),
    'not_found_in_trash' => __( 'Not found in Trash', 'textdomain' ),
    'featured_image' => __( 'Featured Image', 'textdomain' ),
    'set_featured_image' => __( 'Set featured image', 'textdomain' ),
    'remove_featured_image' => __( 'Remove featured image', 'textdomain' ),
    'use_featured_image' => __( 'Use as featured image', 'textdomain' ),
    'insert_into_item' => __( 'Insert into art', 'textdomain' ),
    'uploaded_to_this_item' => __( 'Uploaded to this art', 'textdomain' ),
    'items_list' => __( 'Art list', 'textdomain' ),
    'items_list_navigation' => __( 'Art list navigation', 'textdomain' ),
    'filter_items_list' => __( 'Filter Art list', 'textdomain' ),
  );
  $args = array(
    'label' => __( 'art', 'textdomain' ),
    'description' => __( '', 'textdomain' ),
    'labels' => $labels,
    'menu_icon' => '',
    'supports' => array('title', 'editor', 'revisions', 'author', 'trackbacks', 'custom-fields', 'thumbnail',),
    'taxonomies' => array(),
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 5,
    'show_in_admin_bar' => true,
    'show_in_nav_menus' => true,
    'can_export' => true,
    'has_archive' => true,
    'hierarchical' => false,
    'exclude_from_search' => false,
    'show_in_rest' => true,
    'publicly_queryable' => true,
    'capability_type' => 'post',
    'menu_icon' => 'dashicons-admin-customizer',
  );
  register_post_type( 'art', $args );
  
  // flush rewrite rules because we changed the permalink structure
  global $wp_rewrite;
  $wp_rewrite->flush_rules();
}
add_action( 'init', 'create_art_cpt', 0 );