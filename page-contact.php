<?php
/**
 * Template Name: Contact
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
$context['form'] = FrmFormsController::get_form_shortcode(
		array(
			'id' => 3,
			'title' => false,
			'description' => false
		) );
Timber::render(array('pages/page-contact.twig', 'pages/page.twig'), $context);
