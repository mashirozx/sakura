<?php

namespace Sakura\Lib;

use WP_REST_Posts_Controller;
use WP_REST_Request;
use WP_Error;
use WP_Query;

/**
 * @deprecated use apply_filters( "rest_{$this->post_type}_query", array $args, WP_REST_Request $request ) instead
 */
class ClassWpRestPostsController extends WP_REST_Posts_Controller
{
  /**
   * Retrieves a collection of posts.
   * Source: https://github.com/WordPress/wordpress-develop/blob/master/src/wp-includes/rest-api/endpoints/class-wp-rest-posts-controller.php
   * Memo: only change $registered = $this->get_collection_params_mod();
   * Based on commit 5383af8
   *
   * @since 4.7.0
   *
   * @param WP_REST_Request $request Full details about the request.
   * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
   */
  public function get_items($request)
  {
    // Ensure a search string is set in case the orderby is set to 'relevance'.
    if (!empty($request['orderby']) && 'relevance' === $request['orderby'] && empty($request['search'])) {
      return new WP_Error(
        'rest_no_search_term_defined',
        __('You need to define a search term to order by relevance.'),
        array('status' => 400)
      );
    }

    // Ensure an include parameter is set in case the orderby is set to 'include'.
    if (!empty($request['orderby']) && 'include' === $request['orderby'] && empty($request['include'])) {
      return new WP_Error(
        'rest_orderby_include_missing_include',
        __('You need to define an include parameter to order by include.'),
        array('status' => 400)
      );
    }

    // Retrieve the list of registered collection query parameters.
    $registered = $this->get_collection_params_mod();
    $args       = array();

    /*
		 * This array defines mappings between public API query parameters whose
		 * values are accepted as-passed, and their internal WP_Query parameter
		 * name equivalents (some are the same). Only values which are also
		 * present in $registered will be set.
		 */
    $parameter_mappings = array(
      'author'         => 'author__in',
      'author_exclude' => 'author__not_in',
      'exclude'        => 'post__not_in',
      'include'        => 'post__in',
      'menu_order'     => 'menu_order',
      'offset'         => 'offset',
      'order'          => 'order',
      'orderby'        => 'orderby',
      'page'           => 'paged',
      'parent'         => 'post_parent__in',
      'parent_exclude' => 'post_parent__not_in',
      'search'         => 's',
      'slug'           => 'post_name__in',
      'status'         => 'post_status',
    );

    /*
		 * For each known parameter which is both registered and present in the request,
		 * set the parameter's value on the query $args.
		 */
    foreach ($parameter_mappings as $api_param => $wp_param) {
      if (isset($registered[$api_param], $request[$api_param])) {
        $args[$wp_param] = $request[$api_param];
      }
    }

    // Check for & assign any parameters which require special handling or setting.
    $args['date_query'] = array();

    if (isset($registered['before'], $request['before'])) {
      $args['date_query'][] = array(
        'before' => $request['before'],
        'column' => 'post_date',
      );
    }

    if (isset($registered['modified_before'], $request['modified_before'])) {
      $args['date_query'][] = array(
        'before' => $request['modified_before'],
        'column' => 'post_modified',
      );
    }

    if (isset($registered['after'], $request['after'])) {
      $args['date_query'][] = array(
        'after'  => $request['after'],
        'column' => 'post_date',
      );
    }

    if (isset($registered['modified_after'], $request['modified_after'])) {
      $args['date_query'][] = array(
        'after'  => $request['modified_after'],
        'column' => 'post_modified',
      );
    }

    // Ensure our per_page parameter overrides any provided posts_per_page filter.
    if (isset($registered['per_page'])) {
      $args['posts_per_page'] = $request['per_page'];
    }

    if (isset($registered['sticky'], $request['sticky'])) {
      $sticky_posts = get_option('sticky_posts', array());
      if (!is_array($sticky_posts)) {
        $sticky_posts = array();
      }
      if ($request['sticky']) {
        /*
				 * As post__in will be used to only get sticky posts,
				 * we have to support the case where post__in was already
				 * specified.
				 */
        $args['post__in'] = $args['post__in'] ? array_intersect($sticky_posts, $args['post__in']) : $sticky_posts;

        /*
				 * If we intersected, but there are no post IDs in common,
				 * WP_Query won't return "no posts" for post__in = array()
				 * so we have to fake it a bit.
				 */
        if (!$args['post__in']) {
          $args['post__in'] = array(0);
        }
      } elseif ($sticky_posts) {
        /*
				 * As post___not_in will be used to only get posts that
				 * are not sticky, we have to support the case where post__not_in
				 * was already specified.
				 */
        $args['post__not_in'] = array_merge($args['post__not_in'], $sticky_posts);
      }
    }

    $args = $this->prepare_tax_query($args, $request);

    // Force the post_type argument, since it's not a user input variable.
    $args['post_type'] = $this->post_type;

    /**
     * Filters WP_Query arguments when querying posts via the REST API.
     *
     * The dynamic portion of the hook name, `$this->post_type`, refers to the post type slug.
     *
     * Possible hook names include:
     *
     *  - `rest_post_query`
     *  - `rest_page_query`
     *  - `rest_attachment_query`
     *
     * Enables adding extra arguments or setting defaults for a post collection request.
     *
     * @since 4.7.0
     * @since 5.7.0 Moved after the `tax_query` query arg is generated.
     *
     * @link https://developer.wordpress.org/reference/classes/wp_query/
     *
     * @param array           $args    Array of arguments for WP_Query.
     * @param WP_REST_Request $request The REST API request.
     */
    $args       = apply_filters("rest_{$this->post_type}_query", $args, $request);
    $query_args = $this->prepare_items_query($args, $request);
    $posts_query  = new WP_Query();
    $query_result = $posts_query->query($query_args);

    // Allow access to all password protected posts if the context is edit.
    if ('edit' === $request['context']) {
      add_filter('post_password_required', array($this, 'check_password_required'), 10, 2);
    }

    $posts = array();

    foreach ($query_result as $post) {
      if (!$this->check_read_permission($post)) {
        continue;
      }

      $data    = $this->prepare_item_for_response($post, $request);
      $posts[] = $this->prepare_response_for_collection($data);
    }

    // Reset filter.
    if ('edit' === $request['context']) {
      remove_filter('post_password_required', array($this, 'check_password_required'));
    }

    $page        = (int) $query_args['paged'];
    $total_posts = $posts_query->found_posts;

    if ($total_posts < 1) {
      // Out-of-bounds, run the query again without LIMIT for total count.
      unset($query_args['paged']);

      $count_query = new WP_Query();
      $count_query->query($query_args);
      $total_posts = $count_query->found_posts;
    }

    $max_pages = ceil($total_posts / (int) $posts_query->query_vars['posts_per_page']);

    if ($page > $max_pages && $total_posts > 0) {
      return new WP_Error(
        'rest_post_invalid_page_number',
        __('The page number requested is larger than the number of pages available.'),
        array('status' => 400)
      );
    }

    $response = rest_ensure_response($posts);

    $response->header('X-WP-Total', (int) $total_posts);
    $response->header('X-WP-TotalPages', (int) $max_pages);

    $request_params = $request->get_query_params();
    $base           = add_query_arg(urlencode_deep($request_params), rest_url(sprintf('%s/%s', $this->namespace, $this->rest_base)));

    if ($page > 1) {
      $prev_page = $page - 1;

      if ($prev_page > $max_pages) {
        $prev_page = $max_pages;
      }

      $prev_link = add_query_arg('page', $prev_page, $base);
      $response->link_header('prev', $prev_link);
    }
    if ($max_pages > $page) {
      $next_page = $page + 1;
      $next_link = add_query_arg('page', $next_page, $base);

      $response->link_header('next', $next_link);
    }

    return $response;
  }

  /**
   * Prepares the 'tax_query' for a collection of posts.
   *
   * @since 5.7.0
   *
   * @param array           $args    WP_Query arguments.
   * @param WP_REST_Request $request Full details about the request.
   * @return array Updated query arguments.
   */
  private function prepare_tax_query(array $args, WP_REST_Request $request)
  {
    $relation = $request['tax_relation'];

    if ($relation) {
      $args['tax_query'] = array('relation' => $relation);
    }

    $taxonomies = wp_list_filter(
      get_object_taxonomies($this->post_type, 'objects'),
      array('show_in_rest' => true)
    );

    foreach ($taxonomies as $taxonomy) {
      $base = !empty($taxonomy->rest_base) ? $taxonomy->rest_base : $taxonomy->name;

      $tax_include = $request[$base];
      $tax_exclude = $request[$base . '_exclude'];

      if ($tax_include) {
        $terms            = array();
        $include_children = false;
        $operator         = 'IN';

        if (rest_is_array($tax_include)) {
          $terms = $tax_include;
        } elseif (rest_is_object($tax_include)) {
          $terms            = empty($tax_include['terms']) ? array() : $tax_include['terms'];
          $include_children = !empty($tax_include['include_children']);

          if (isset($tax_include['operator']) && 'AND' === $tax_include['operator']) {
            $operator = 'AND';
          }
        }

        if ($terms) {
          $args['tax_query'][] = array(
            'taxonomy'         => $taxonomy->name,
            'field'            => 'slug', // 'term_id',
            'terms'            => $terms,
            'include_children' => $include_children,
            'operator'         => $operator,
          );
        }
      }

      if ($tax_exclude) {
        $terms            = array();
        $include_children = false;

        if (rest_is_array($tax_exclude)) {
          $terms = $tax_exclude;
        } elseif (rest_is_object($tax_exclude)) {
          $terms            = empty($tax_exclude['terms']) ? array() : $tax_exclude['terms'];
          $include_children = !empty($tax_exclude['include_children']);
        }

        if ($terms) {
          $args['tax_query'][] = array(
            'taxonomy'         => $taxonomy->name,
            'field'            => 'term_id',
            'terms'            => $terms,
            'include_children' => $include_children,
            'operator'         => 'NOT IN',
          );
        }
      }
    }

    return $args;
  }

  public function get_public_item_schema_mod()
  {
    $schema = $this->get_public_item_schema();
    $schema['properties']['categories']['items']['type'] = [
      "string",
      "integer"
    ];
    return $schema;
  }

  public function get_collection_params_mod()
  {
    $params = $this->get_collection_params();
    $new  = [
      "title" => "Term Slug",
      "description" => "Match terms with the listed Slug.",
      "type" => "array",
      "items" => [
        "type" => "string"
      ]
    ];
    $params['categories']['oneOf'][0] = $new;
    return $params;
  }
}
