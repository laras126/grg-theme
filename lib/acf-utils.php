<?php

	/*
		Add a 'Links Only' toolbar style
		Credit: http://www.advancedcustomfields.com/resources/customize-the-wysiwyg-toolbars/
	*/

	// Add options page
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page(array(
		'page_title' 	=> 'Site Options',
		'menu_title'	=> 'Site Options',
		'menu_slug' 	=> 'site-options',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}
// Customize WYSIWYG toolbar
function my_toolbars( $toolbars )
{

	$toolbars['Text Based'] = array();
	$toolbars['Text Based'][1] = array('formatselect' , 'bold' , 'link' , 'italic' , 'unlink' );

	$toolbars['Very Simple' ] = array();
	$toolbars['Very Simple' ][1] = array('bold' , 'italic' , 'underline' );

	$toolbars['Links Only' ] = array();
	$toolbars['Links Only' ][1] = array('link', 'unlink');

	// Edit the "Full" toolbar and remove 'code'
	// - delet from array code from http://stackoverflow.com/questions/7225070/php-array-delete-by-value-not-key
	if( ($key = array_search('code' , $toolbars['Full' ][2])) !== false )
	{
	    unset( $toolbars['Full' ][2][$key] );
	}
	// return $toolbars - IMPORTANT!
	return $toolbars;
}
add_filter( 'acf/fields/wysiwyg/toolbars' , 'my_toolbars'  );





// Add ACF Styles
// https://www.advancedcustomfields.com/resources/acfinputadmin_head/
function my_acf_admin_head() {
	?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/acf.css">

	<?php
}
add_action('acf/input/admin_head', 'my_acf_admin_head');
