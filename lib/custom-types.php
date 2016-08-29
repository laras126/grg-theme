<?php

/*
 *
 * Custom Post Types
 *
 */

// Note that you only need the arguments and register_post_type function here. They are hooked into WordPress in functions.php.

$labels = array(
	'name'                  => _x( 'Team Members', 'Post Type General Name', 'grg' ),
	'singular_name'         => _x( 'Team Member', 'Post Type Singular Name', 'grg' ),
	'menu_name'             => __( 'Team Members', 'grg' ),
	'name_admin_bar'        => __( 'Team Member', 'grg' ),
	'archives'              => __( 'Item Archives', 'grg' ),
	'parent_item_colon'     => __( 'Parent Item:', 'grg' ),
	'all_items'             => __( 'All Items', 'grg' ),
	'add_new_item'          => __( 'Add New Item', 'grg' ),
	'add_new'               => __( 'Add New', 'grg' ),
	'new_item'              => __( 'New Item', 'grg' ),
	'edit_item'             => __( 'Edit Item', 'grg' ),
	'update_item'           => __( 'Update Item', 'grg' ),
	'view_item'             => __( 'View Item', 'grg' ),
	'search_items'          => __( 'Search Item', 'grg' ),
	'not_found'             => __( 'Not found', 'grg' ),
	'not_found_in_trash'    => __( 'Not found in Trash', 'grg' ),
	'featured_image'        => __( 'Featured Image', 'grg' ),
	'set_featured_image'    => __( 'Set featured image', 'grg' ),
	'remove_featured_image' => __( 'Remove featured image', 'grg' ),
	'use_featured_image'    => __( 'Use as featured image', 'grg' ),
	'insert_into_item'      => __( 'Insert into item', 'grg' ),
	'uploaded_to_this_item' => __( 'Uploaded to this item', 'grg' ),
	'items_list'            => __( 'Items list', 'grg' ),
	'items_list_navigation' => __( 'Items list navigation', 'grg' ),
	'filter_items_list'     => __( 'Filter items list', 'grg' ),
);
$rewrite = array(
  'slug' => 'team-members',
  'with_front' => false
);
$args = array(
	'label'                 => __( 'Team Member', 'grg' ),
	'description'           => __( 'Member Description', 'grg' ),
	'labels'                => $labels,
	'supports'              => array( 'title', 'excerpt', 'thumbnail', 'revisions', 'page-attributes', ),
	'taxonomies'            => array( 'category', 'post_tag' ),
	'hierarchical'          => false,
  'menu_icon'             => 'dashicons-businessman',
	'public'                => true,
	'show_ui'               => true,
	'show_in_menu'          => true,
	'menu_position'         => 5,
	'show_in_admin_bar'     => true,
	'show_in_nav_menus'     => true,
	'can_export'            => true,
	'has_archive'           => true,
	'exclude_from_search'   => false,
	'publicly_queryable'    => true,
	'capability_type'       => 'page',
  'rewrite'               => $rewrite
);
register_post_type( 'team_member', $args);
