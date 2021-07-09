<?php

/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since 1.0.0
 */

// namespace Sakura;

// use Sakura\Utils;

// $template = new Helpers\TemplateHelper();
// $params = [
//   'language_attributes' => Utils\echo_interceptor('language_attributes'),
//   'bloginfo' => Utils\echo_interceptor('bloginfo', 'charset'),
//   'wp_head' => Utils\echo_interceptor('wp_head')
// ];
// echo $template->render('header.twig', $params);

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php wp_head(); ?>
</head>

<body>
  <div id="app" class="container">
    <h1>Vite is loading</h1>
    <p>we will render basic content with PHP here in terms of better SEO.</p>
