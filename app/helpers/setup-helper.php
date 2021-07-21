<?php

namespace Sakura\Helpers;

// use Sakura\Controllers\OptionController;

class SetupHelper
{
  public function __construct()
  {
    add_action('after_setup_theme', [$this, 'setup']);
    // Disable WP Admin Bar
    add_filter('show_admin_bar', '__return_false');
    // TODO: enable this if http?
    // add_filter('wp_is_application_passwords_available', '__return_true');
    // Allow rest API CORS.
    add_action('rest_api_init', [$this, 'wp_rest_allow_all_cors'], 15);
    // post excerpt more context
    add_filter('excerpt_more', [$this, 'changes_post_excerpt_more']);
    // post excerpt length
    add_filter('excerpt_length', [$this, 'changes_post_excerpt_length'], 10);
    // count post views
    add_action('get_header', [$this, 'set_post_views']);
    // Inite config options
    // won't need anymore with options?
    // add_action('after_switch_theme', [new OptionController(), 'inite_theme'], 1, 2);
  }

  public function setup()
  {

    add_theme_support('title-tag');

    add_theme_support('post-thumbnails');

    add_theme_support('custom-logo', array(
      'height' => 160,
      'width' => 160,
    ));

    register_nav_menus($this->menu_locations());
  }

  public static function menu_locations()
  {
    return [
      'header_menu' => esc_html('Header Menu (show on page header)', SAKURA_TEXT_DOMAIN),
    ];
  }

  public static function wp_rest_allow_all_cors()
  {
    // Remove the default filter.
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');

    // Add a Custom filter.
    add_filter('rest_pre_serve_request', function ($value) {
      header('Access-Control-Allow-Origin: *');
      header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
      header('Access-Control-Allow-Credentials: true');
      return $value;
    });
  }

  public function changes_post_excerpt_more(string $more)
  {
    return ' ...';
  }

  public function changes_post_excerpt_length(int $length)
  {
    return 120;
  }

  /**
   * Post view times counter
   *
   * @return void
   */
  public function set_post_views()
  {
    if (is_singular()) {
      global $post;
      $post_id = intval($post->ID);
      if ($post_id) {
        $views = (int) get_post_meta($post_id, 'views', true);
        if (!update_post_meta($post_id, 'views', ($views + 1))) {
          add_post_meta($post_id, 'views', 1, true);
        }
      }
    }
  }
}
