<?php

/*
 *
 * Custom Menus
 *
 */

// Register your menus here.


	$locations = array(
		'main_nav' => __( 'Primary Menu', 'grg_theme' ),
		'footer_nav' => __( 'Footer Links', 'grg_theme' ),
	);
	register_nav_menus( $locations );
