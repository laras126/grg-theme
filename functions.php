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
		add_action('init', array($this, 'grg_acf_utils'));
		add_action('init', array($this, 'grg_register_menus'));
		add_action('widgets_init', array($this, 'register_sidebars'));
		parent::__construct();
	}

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

	function grg_acf_utils() {
			require('lib/acf-utils.php');
	}

	function add_to_context($context) {

		// Navs
		$context['main_nav'] = new TimberMenu('main_nav');
		$context['footer_nav'] = new TimberMenu('footer_nav');
		$context['footer_widgets'] = Timber::get_sidebar('sidebar.php');

		// Site Wide Settings
		// Callout Bar Data
		$context['callout_tf'] = get_field('callout_tf', 'options');
		$context['callout_text'] = get_field('callout_text', 'options');

		// BrokerCheck Notice
		$context['brokercheck_tf'] = get_field('brokercheck_tf', 'options');
		$context['brokercheck_text'] = get_field('brokercheck_text', 'options');

		// Site Options
		$context['copyright'] = get_field('copyright', 'options');
		$context['site_disclosure'] = get_field('site_disclosure', 'options');

		// Social links array to display across the site
		$social = array(
			'twitter' => 'https://twitter.com/grgretirement',
			'facebook' => 'https://facebook.com/grgretirement,',
			'linkedin' => 'https://www.linkedin.com/in/grgretirement',
			'rss' => 'https://feedburner.com/grg'
		);

		// Add social array to site context
		$context['social'] = $social;


		// All Categories
		$context['cats'] = Timber::get_terms('category');

		// Posts page link
		$context['posts_page_link'] = get_permalink(get_option('page_for_posts' ));

		// This 'site' context below allows us to acces main site information like the site title or description.
		$context['site'] = $this;

		return $context;
	}

	// Here you can add your own fuctions to Twig. Don't worry about this section if you don't come across a need for it.
	// See more here: http://twig.sensiolabs.org/doc/advanced.html
	function add_to_twig($twig){
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

 // Disable WordPress Admin Bar for all users but admins
 // show_admin_bar ( false );


// Enqueue scripts
function grg_scripts() {

	// Use jQuery from a CDN, enqueue in footer
	if (!is_admin()) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js', array(), null, true);
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

			remove_menu_page( 'edit-comments.php' ); // Posts
			// remove_menu_page( 'users.php'); // Users
	}
	add_action( 'admin_menu', 'grg_remove_menu_items' );


// Reset post thumbnail size *NEEDED FOR Timmy Plugin**
set_post_thumbnail_size(0,0);

// Register yoru image sizes with Timmy
function get_image_sizes() {
		return array(
		'custom-4' => array(
			'resize' => array( 370 ),
			'srcset' => array( 2 ),
			'sizes' => '(min-width: 992px) 33.333vw, 100vw',
			'name' => 'Width 1/4 fix',
			'post_types' => array( 'post', 'page' ),
		),
	);
}



// Show notice on Local. and Staging
function grg_env_notice() {
	if (WP_ENV != 'production') {
		echo '<p class="' . WP_ENV . ' env-notice">' . WP_ENV . '</p>';
	}
}
add_action('wp_head', 'grg_env_notice');





 // Add excerpts to pages
 function grg_add_excerpts_to_pages() {
	 add_post_type_support( 'page', 'excerpt' );
 }
 add_action( 'init', 'grg_add_excerpts_to_pages' );





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


// allow SVG uploads
function allow_svg_mime_types( $mimes = array() ) {
    $mimes['svg']  = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'allow_svg_mime_types');

// Change WP-Login Logo
function custom_login_logo() {
	echo '<style type="text/css">
		#login h1 a, .login h1 a {
			background-image: url('.get_bloginfo('template_directory').'/assets/img/png/grg-icon-83.5x83.5@2x.png) 50% 50% no-repeat !important; }</style>';
}
add_action('login_head', 'custom_login_logo');







// Custom Wordpress Dashboard
// See: https://www.sitepoint.com/wordpress-dashboard-widgets-api/

function add_dashboard_widget()
{
    wp_add_dashboard_widget("IFP", "GRG Website Information", "display_ifp_dashboard_widget");
}

function display_ifp_dashboard_widget()
{
	echo "
	 <h3><strong>Accounts Information</strong></h3>
	 <ul>
	 	<li>Website Host: <a href='https://my.a2hosting.com/clientarea.php' target='blank'>A2 Hosting</a></li>
	  <li>Domain Registrar: <a href='https://namecheap.com' target='blank'>Namecheap</a></li>
		<li>Google: <a href='https://analytics.google.com'>Site Analytics</a> | <a href='https://www.google.com/business/' target='blank'>My Business</a></li>
		<li>Social Media: <a href='https://twitter.com/'>Twitter</a> | <a href='https://linkedin.com'>LinkedIn</a></li>
	 </ul>
	 <hr/>

	 <h3><strong>Compliance Portal</strong></h3>
	 <ul>
	 	<li>All website changes must be submitted through ComplianceMax via <a href='https://branchweb.lpl.com/WebShell/Default.aspx' target='blank'>LPL BranchWeb</a></li>
	 </ul>
	 <hr/>

	 <h3><strong>Technical Support</strong></h3>
	 <ul>
	 	<li><a href='https://my.a2hosting.com/submitticket.php'>Website Support</a></li>
	  <li><a href='mailto:chuck.teal@lpl.com'>LPL Email Support</a></li>
		<li><a href='mailto:marketing@ifpartners.com'>Site functionality feature request</a></li>
	 </ul>
	 <hr/>

	 <h3><strong>Resources</strong></h3>
	 <ul>
	 	<li><a href='https://codex.wordpress.org/First_Steps_With_WordPress' target='blank'>First Steps With WordPress</a> - A step-by-step tour through your WordPress site.</li>
	 	<li><a href='#' target='blank'>Easy Wordpress Manual</a> - A simple manual to guide you through the process of editing your site content</li>
		<li><a href='https://www.youtube.com/playlist?list=PL_9u00nsHteH2OBVX4YSU_HWWY_JSxFyj' target='blank'>WordPress Walkthrough Series 2015</a></li>
		<li><a href='http://webdesign.tutsplus.com/tutorials/troubleshooting-wordpress-in-60-seconds--cms-25231' target='blank'>Troubleshooting WordPress in 60 Seconds</a></li>
		<li><a href='https://www.youtube.com/user/wpbeginner' target='blank'>WPBeginner</a> - WordPress video tutorials for beginners</li>
	 </ul>
	 <hr/>

	<h3><strong>WordPress Courses & Tutorials</strong></h3>
	<ul>
		<li><a href='https://teamtreehouse.com/' target='blank'>TeamTreehouse</a></li>
		<li><a href='https://www.codeschool.com/' target='blank'>CodeSchool</a></li>
		<li><a href='http://www.wpbeginner.com/category/wp-tutorials/' target='blank'>WPBeginner</a></li>
		<li><a href='http://webdesign.tutsplus.com/categories/wordpress' target='blank'>Envatotuts+</a></li>
	</ul>
	<em>Information Last Updated: July 21, 2016</em>";
}

add_action("wp_dashboard_setup", "add_dashboard_widget");






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
