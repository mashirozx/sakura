<?php

namespace Sakura\Controllers;

use WP_REST_Response;
use WP_REST_Request;
use WP_Rewrite;
use Sakura\Controllers\MenuController;
use Sakura\Controllers\CommentController;

class InitStateController extends BaseController
{
  public function show(WP_REST_Request $request)
  {
    $this->request_parser($request);

    $data = $this->get_initial_state();

    $response = new WP_REST_Response($data);
    $response->set_status($this->code);

    return $response;
  }

  public function get_initial_state()
  {
    return array(
      'api_base' => get_rest_url(),
      'root' => esc_url_raw(rest_url()),
      'nonce' => wp_create_nonce('wp_rest'),
      'routing' => $this->get_routing(),
      'site_info' => $this->get_site_info(),
      'menus' => (new MenuController)->get_menus(),
      // 'rewrite_rules' => (new \WP_Rewrite())->rewrite_rules(),
      'index' => (new WP_Rewrite())->index,
      'recaptcha_site_key' => '6LfHEoEbAAAAAI5p_XBlr1WxEvrsOSNQFCQNcT79', // v2 secret key: 6LfHEoEbAAAAAIh0w2I9PCcVoa0j71mO6t7fipsj
      // 'recaptcha_site_key' => '6LdKhX8bAAAAAF5HJprXtKvg3nfBJMfgd2o007PN' // v3 secret key: 6LdKhX8bAAAAAA010EXlQ32FWoYD1J2sLb8SaYLR
    );
  }

  public static function get_routing()
  {
    $routing = array(
      'category_base' => get_option('category_base'),
      'page_on_front' => null,
      'page_for_posts' => null,
      'permalink_structure' => get_option('permalink_structure'),
      'show_on_front' => get_option('show_on_front'),
      'tag_base' => get_option('tag_base'),
      'url' => get_bloginfo('url')
    );

    if ($routing['show_on_front'] === 'page') {
      $front_page_id = get_option('page_on_front');
      $posts_page_id = get_option('page_for_posts');

      if ($front_page_id) {
        $front_page = get_post($front_page_id);
        $routing['page_on_front'] = $front_page->post_name;
      }

      if ($posts_page_id) {
        $posts_page = get_post($posts_page_id);
        $routing['page_for_posts'] = $posts_page->post_name;
      }
    }

    return $routing;
  }

  public static function get_site_info()
  {
    return array(
      'woordpress_version' => get_bloginfo('version'),
      'sakura_version' => self::$version,
      'sakura_text_domain' => self::$text_domain,
      'description' => get_bloginfo('description'),
      'docTitle' => '',
      'loading' => false,
      'logo' => get_theme_mod('custom_logo'),
      'name' => get_bloginfo('name'),
      'posts_per_page' => get_option('posts_per_page'),
      'url' => get_bloginfo('url')
    );
  }

  // TODO: need auth first
  public function init_ancestor_meta_show(WP_REST_Request $request)
  {
    $this->request_parser($request);

    $data = CommentController::init_comment_ancestor_meta();

    $response = new WP_REST_Response($data);
    $response->set_status($this->code);

    return $response;
  }

  public function init_user_agent_info_meta_show(WP_REST_Request $request)
  {
    $this->request_parser($request);

    $data = CommentController::init_comment_user_agent_info_meta();

    $response = new WP_REST_Response($data);
    $response->set_status($this->code);

    return $response;
  }
}
