<?php

/*
 *
 * Taxonomies
 *
 */

// Same as with Custom Types, you only need the arguments and register_taxonomy function here. They are hooked into WordPress in functions.php.

$labels = array(
	'name'                       => _x( 'Page Categories', 'Taxonomy General Name', 'grg' ),
	'singular_name'              => _x( 'Page Category', 'Taxonomy Singular Name', 'grg' ),
	'menu_name'                  => __( 'Page Categories', 'grg' ),
	'all_items'                  => __( 'All Categories', 'grg' ),
	'parent_item'                => __( 'Parent Category', 'grg' ),
	'parent_item_colon'          => __( 'Parent Category:', 'grg' ),
	'new_item_name'              => __( 'New Category', 'grg' ),
	'add_new_item'               => __( 'Add New Category', 'grg' ),
	'edit_item'                  => __( 'Edit Category', 'grg' ),
	'update_item'                => __( 'Update Category', 'grg' ),
	'view_item'                  => __( 'View Category', 'grg' ),
	'separate_items_with_commas' => __( 'Separate Category', 'grg' ),
	'add_or_remove_items'        => __( 'Add or remove Categories', 'grg' ),
	'choose_from_most_used'      => __( 'Choose from the most used Categories', 'grg' ),
	'popular_items'              => __( 'Popular Categories', 'grg' ),
	'search_items'               => __( 'Search Categories', 'grg' ),
	'not_found'                  => __( 'Not Found', 'grg' ),
	'no_terms'                   => __( 'No Categories', 'grg' ),
	'items_list'                 => __( 'Categories list', 'grg' ),
	'items_list_navigation'      => __( 'Categories list navigation', 'grg' ),
);
$args = array(
	'labels'                     => $labels,
	'hierarchical'               => true,
	'public'                     => true,
	'show_ui'                    => true,
	'show_admin_column'          => true,
	'show_in_nav_menus'          => true,
	'show_tagcloud'              => true,
);
register_taxonomy( 'page_category', array( 'page' ), $args );



$role_labels = array(
	'name'                       => _x( 'Roles', 'Taxonomy General Name', 'grg' ),
	'singular_name'              => _x( 'Role', 'Taxonomy Singular Name', 'grg' ),
	'menu_name'                  => __( 'Roles', 'grg' ),
	'all_items'                  => __( 'All Roles', 'grg' ),
	'parent_item'                => __( 'Parent Role', 'grg' ),
	'parent_item_colon'          => __( 'Parent Role:', 'grg' ),
	'new_item_name'              => __( 'New Role', 'grg' ),
	'add_new_item'               => __( 'Add New Role', 'grg' ),
	'edit_item'                  => __( 'Edit Role', 'grg' ),
	'update_item'                => __( 'Update Role', 'grg' ),
	'view_item'                  => __( 'View Role', 'grg' ),
	'separate_items_with_commas' => __( 'Separate Role', 'grg' ),
	'add_or_remove_items'        => __( 'Add or remove Roles', 'grg' ),
	'choose_from_most_used'      => __( 'Choose from the most used Roles', 'grg' ),
	'popular_items'              => __( 'Popular Roles', 'grg' ),
	'search_items'               => __( 'Search Roles', 'grg' ),
	'not_found'                  => __( 'Not Found', 'grg' ),
	'no_terms'                   => __( 'No Roles', 'grg' ),
	'items_list'                 => __( 'Roles list', 'grg' ),
	'items_list_navigation'      => __( 'Roles list navigation', 'grg' ),
);
$role_args = array(
	'labels'                     => $role_labels,
	'hierarchical'               => true,
	'public'                     => true,
	'show_ui'                    => true,
	'show_admin_column'          => true,
	'show_in_nav_menus'          => true,
	'show_tagcloud'              => true,
);
register_taxonomy( 'role', array( 'team_member' ), $role_args );
