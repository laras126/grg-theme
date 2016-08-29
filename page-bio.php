<?php
/**
* Template Name: Bio Page
*/

$args = array(
  'post_type' => 'team_member'
);

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
$context['members'] = Timber::get_posts($args);
$context['roles'] = Timber::get_terms('role');
Timber::render('pages/page-bio.twig', $context );
