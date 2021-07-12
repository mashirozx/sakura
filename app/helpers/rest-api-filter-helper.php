<?php

namespace Sakura\Helpers;

/**
 * @deprecated use apply_filters( "rest_{$this->post_type}_query", array $args, WP_REST_Request $request ) or filter instead
 */
class RestApiFilterHelper
{
  public function __construct()
  {
    add_action('rest_api_init', [$this, 'rest_api_filter_add_filters']);
  }

  /**
   * Add the necessary filter to each post type
   **/
  public function rest_api_filter_add_filters()
  {
    foreach (get_post_types(array('show_in_rest' => true), 'objects') as $post_type) {
      add_filter('rest_' . $post_type->name . '_query', [$this, 'rest_api_filter_add_filter_param'], 10, 2);
    }
  }

  /**
   * Add the filter parameter
   *
   * @param  array           $args    The query arguments.
   * @param  WP_REST_Request $request Full details about the request.
   * @return array $args.
   **/
  public function rest_api_filter_add_filter_param($args, $request)
  {
    // Bail out if no filter parameter is set.
    if (empty($request['filter']) || !is_array($request['filter'])) {
      return $args;
    }

    $filter = $request['filter'];

    if (isset($filter['posts_per_page']) && ((int) $filter['posts_per_page'] >= 1 && (int) $filter['posts_per_page'] <= 100)) {
      $args['posts_per_page'] = $filter['posts_per_page'];
    }

    global $wp;
    $vars = apply_filters('rest_query_vars', $wp->public_query_vars);

    // Allow valid meta query vars.
    $vars = array_unique(array_merge($vars, array('meta_query', 'meta_key', 'meta_value', 'meta_compare')));

    foreach ($vars as $var) {
      if (isset($filter[$var])) {
        $args[$var] = $filter[$var];
      }
    }
    return $args;
  }
}
