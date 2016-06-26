<?php


if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		} );
	return;
}

class StarterSite extends TimberSite {

	function __construct(){
		add_theme_support('post-formats');
		add_theme_support('post-thumbnails');
		add_theme_support('menus');

		add_filter('timber_context', array($this, 'add_to_context'));
		add_filter('get_twig', array($this, 'add_to_twig'));

		add_action('init', array($this, 'grg_register_post_types'));
		add_action('init', array($this, 'grg_register_taxonomies'));
		add_action('init', array($this, 'grg_register_menus'));
		add_action('init', array($this, 'grg_register_widgets'));
		parent::__construct();
	}


	// Note that the following included files only need to contain the taxonomy/CPT/Menu arguments and register_whatever function.
	// http://generatewp.com is nice

	function grg_register_post_types() {
			require('lib/custom-types.php');
	}

	function grg_register_taxonomies() {
			require('lib/taxonomies.php');
	}

	function grg_register_widgets() {
			require('lib/widgets.php');
	}

	function grg_register_menus() {
			require('lib/menus.php');
	}

	function add_to_context($context) {

		// Navs
		$context['main_nav'] = new TimberMenu('main_nav');
		$context['header_nav'] = new TimberMenu('header_nav');
		$context['footer_nav'] = new TimberMenu('footer_nav');
		$context['footer_widgets'] = Timber::get_sidebar('sidebar.php');

		// ACF Options Page
		$context['site_tagline'] = get_field('site_tagline', 'options');
		// $context['callout_tf'] = get_field('callout_tf', 'options');
		// $context['callout_bar'] = get_field('callout_bar', 'options');

		$context['favicon_16_url'] = get_field('favicon_16', 'options');
		$context['favicon_32_url'] = get_field('favicon_32', 'options');

		// Social
		$social = array(
			'twitter' => 'https://twitter.com/name',
			'linkedin' => 'https://www.linkedin.com/in/name',
			'rss' => 'https://feeds.feedburner.com/name'
		);

		$context['social'] = $social;

		// Site
		$context['site'] = $this;

		return $context;
	}

	function add_to_twig($twig){
		/* this is where you can add your own fuctions to twig */
		$twig->addExtension(new Twig_Extension_StringLoader());
		$twig->addFilter('myfoo', new Twig_Filter_Function('myfoo'));
		return $twig;
	}

}

new StarterSite();




/*
 **************************
 * GRG Utility Functions *
 **************************
 */




// Enqueue scripts
function grg_scripts() {

	// Use jQuery from a CDN, enqueue in footer
	if (!is_admin()) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js', array(), null, true);
  	wp_enqueue_script('jquery');
	}


	// Enqueue stylesheet and JS file with a jQuery dependency.
	// Note that we aren't using WordPress' default style.css, and instead enqueueing the file of compiled Sass.
	// Add stylesheet, minified for production, non-min for dev.
	if (WP_ENV == 'production') {
		wp_enqueue_style( 'grg-styles', get_template_directory_uri() . '/assets/css/main.min.css', 1.0);
		wp_enqueue_script( 'grg-js', get_template_directory_uri() . '/assets/js/build/scripts.min.js', array('jquery'), '1.0.0', true );

	} else {
		wp_enqueue_style( 'grg-styles', get_template_directory_uri() . '/assets/css/main.css', 1.0);
		wp_enqueue_script( 'grg-js', get_template_directory_uri() . '/assets/js/build/scripts.js', array('jquery'), '1.0.0', true );
	}

	// Add our JS
}
add_action( 'wp_enqueue_scripts', 'grg_scripts' );




	// Remove unused items from the Dashboard menu
	function grg_remove_menu_items(){
			remove_menu_page( 'edit.php' ); // Posts
			remove_menu_page( 'edit-comments.php' ); // Posts
			// remove_menu_page( 'users.php'); // Users
	}
	add_action( 'admin_menu', 'grg_remove_menu_items' );






// Show notice on Local. and Staging
function grg_env_notice() {
	if (WP_ENV != 'production') {
		echo '<p class="' . WP_ENV . ' env-notice">' . WP_ENV . '</p>';
	}
}
add_action('wp_head', 'grg_env_notice');




// Add Options Page
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page('Theme Settings');
}




 // Add excerpts to pages
 function grg_add_excerpts_to_pages() {
	 add_post_type_support( 'page', 'excerpt' );
 }
 add_action( 'init', 'grg_add_excerpts_to_pages' );




 // Add a 'Very Simple' toolbar style for the WYSIWYG editor in ACF
 // http://www.advancedcustomfields.com/resources/customize-the-wysiwyg-toolbars/
 function grg_acf_wysiwyg_toolbar( $toolbars ) {

	 $toolbars['Text Based'] = array();

	 // Only one row of buttons
	 $toolbars['Text Based'][1] = array('formatselect' , 'bold' , 'link' , 'italic' , 'unlink' );

	 return $toolbars;
 }
 add_filter( 'acf/fields/wysiwyg/toolbars' , 'grg_acf_wysiwyg_toolbar'  );



// Move excerpt box to top of post editor
// https://wpartisan.me/tutorials/wordpress-how-to-move-the-excerpt-meta-box-above-the-editor

/**
 * Removes the regular excerpt box. We're not getting rid
 * of it, we're just moving it above the wysiwyg editor
 *
 * @return null
 */
function oz_remove_normal_excerpt() {
	remove_meta_box( 'postexcerpt' , 'post' , 'normal' );
}
add_action( 'admin_menu' , 'oz_remove_normal_excerpt' );

/**
 * Add the excerpt meta box back in with a custom screen location
 *
 * @param  string $post_type
 * @return null
 */
function oz_add_excerpt_meta_box( $post_type ) {
	if ( in_array( $post_type, array( 'post', 'page' ) ) ) {
		add_meta_box(
			'oz_postexcerpt',
			__( 'Excerpt', 'thetab-theme' ),
			'post_excerpt_meta_box',
			$post_type,
			'after_title',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'oz_add_excerpt_meta_box' );

/**
 * You can't actually add meta boxes after the title by default in WP so
 * we're being cheeky. We've registered our own meta box position
 * `after_title` onto which we've regiestered our new meta boxes and
 * are now calling them in the `edit_form_after_title` hook which is run
 * after the post tile box is displayed.
 *
 * @return null
 */
function oz_run_after_title_meta_boxes() {
	global $post, $wp_meta_boxes;
	# Output the `below_title` meta boxes:
	do_meta_boxes( get_current_screen(), 'after_title', $post );
}
add_action( 'edit_form_after_title', 'oz_run_after_title_meta_boxes' );




// Make custom fields work with Yoast SEO (only impacts the light, but helpful!)
// https://imperativeideas.com/making-custom-fields-work-yoast-wordpress-seo/

if ( is_admin() ) { // check to make sure we aren't on the front end
	add_filter('wpseo_pre_analysis_post_content', 'grg_add_custom_to_yoast');

	function grg_add_custom_to_yoast( $content ) {
		global $post;
		$pid = $post->ID;

		$custom = get_post_custom($pid);
		unset($custom['_yoast_wpseo_focuskw']); // Don't count the keyword in the Yoast field!

		foreach( $custom as $key => $value ) {
			if( substr( $key, 0, 1 ) != '_' && substr( $value[0], -1) != '}' && !is_array($value[0]) && !empty($value[0])) {
				$custom_content .= $value[0] . ' ';
			}
		}

		$content = $content . ' ' . $custom_content;
		return $content;

		remove_filter('wpseo_pre_analysis_post_content', 'grg_add_custom_to_yoast'); // don't let WP execute this twice
	}
}

// Remove inline gallery styles
add_filter( 'use_default_gallery_style', '__return_false' );




// Google Analytics snippet from HTML5 Boilerplate
// Cookie domain is 'auto' configured. See: http://goo.gl/VUCHKM

define('GOOGLE_ANALYTICS_ID', 'UA-XXXXXXXX-X');
function mtn_google_analytics() { ?>
<script>
	<?php if (WP_ENV === 'production') : ?>
		(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
		function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
		e=o.createElement(i);r=o.getElementsByTagName(i)[0];
		e.src='//www.google-analytics.com/analytics.js';
		r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
	<?php else : ?>
		function ga() {
			console.log('GoogleAnalytics: ' + [].slice.call(arguments));
		}
	<?php endif; ?>
	ga('create','<?php echo GOOGLE_ANALYTICS_ID; ?>','auto');ga('send','pageview');
</script>

<?php }

if (GOOGLE_ANALYTICS_ID && (WP_ENV !== 'production' || !current_user_can('manage_options'))) {
	add_action('wp_footer', 'mtn_google_analytics', 20);
}
