<?php
/**
 * Maintenance mode template that's shown to logged out users.
 *
 * @package   grg-maintenance-mode
 * @copyright Copyright (c) 2016, Alex Mamel
 * @license   GPL2+
 */
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<link href='//fonts.googleapis.com/css?family=Lato:400,400italic,700|Playfair+Display' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo plugins_url( 'assets/css/maintenance.css', dirname( __FILE__ ) ); ?>">

	<title>Down for Maintenance | <?php echo esc_html( get_bloginfo( 'name' ) ); ?></title>
</head>

<body>

<div id="container">

	<header>
		<h1><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
	</header>

	<main>
		<p>Down for a bit of maintenance&mdash;be back soon!</p>
	</main>

</div>

</body>
</html>
