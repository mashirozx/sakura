<?php

namespace Sakura\Helpers;

class PostQueryHelper
{
  /**
   * Post type.
   *
   * @since 5.0.0
   * @var string
   */
  protected $post_type;

  public function __construct(string $post_type)
  {
    $this->post_type = $post_type;
    add_filter("rest_{$post_type}_query", [$this, 'filter_rest_post_query'], 10, 2);
  }

  /**
   * Filter rest posts by category slug
   *
   * @param array $args
   * @param WP_Rest_Rquest $request
   * @return array $args
   */
  public function filter_rest_post_query($args, $request)
  {
    $args['tax_query'] = [];

    $taxonomies = wp_list_filter(get_object_taxonomies($this->post_type, 'objects'), array('show_in_rest' => true));

    foreach ($taxonomies as $taxonomy) {
      $base = !empty($taxonomy->rest_base) ? $taxonomy->rest_base : $taxonomy->name;

      if (!isset($request["{$base}_slug"])) {
        continue;
      }

      array_push($args['tax_query'], [
        'taxonomy'         => $taxonomy->name,
        'field'            => 'slug',
        'terms'            => explode(',', $request["{$base}_slug"]),
        'include_children' => true,
        'operator'         => 'IN',
      ]);
    }
    return $args;
  }
}
