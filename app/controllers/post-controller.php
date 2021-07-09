<?php

namespace Sakura\Controllers;

use Sakura\Controllers\BaseController;
use Sakura\Lib\Exception;
use DOMDocument;

class PostController extends BaseController
{
  /**
   * Get post comment count
   *
   * @param integer $post_id
   * @return number
   */
  public static function get_comments_number(int $post_id)
  {
    return intval(get_comments_number($post_id));
  }

  public static function get_pagination_info($request)
  {
    $the_query = new \WP_Query($_GET);
    $total_page = $the_query->post_count;
    return $the_query;
  }

  /**
   * Get original post expert
   *
   * @param integer $post_id
   * @return string
   */
  public static function get_post_excerpt(int $post_id)
  {
    $post = get_post($post_id);
    if ($post) {
      // throw new Exception('whoop');
      return $post->post_excerpt;
    } else {
      throw new Exception("No such post \$id={$post_id}!");
    }
  }

  /**
   * Get post original Markdown content
   * based on https://wordpress.org/plugins/wp-githuber-md/
   *
   * @param integer $post_id
   * @return mixed string | null
   */
  public static function get_post_markdown(int $post_id)
  {
    $post = get_post($post_id);
    if ($post) {
      // won't check if post_content_filtered is empty, will check it in js
      // TODO: global check if markdown available.
      if (property_exists($post, 'post_content_filtered')) {
        return html_entity_decode($post->post_content_filtered, ENT_QUOTES);
      } else {
        return null;
      }
    }
  }

  /**
   * Content filter
   *
   * @param object $post $post obj in register_rest_field
   * @return object $post content modified
   */
  public static function rest_api_post_content_filter(array $post)
  {
    $post['content']['markdown'] = self::get_post_markdown($post['id']);
    return $post['content'];
  }

  public static function rest_api_post_excerpt_filter($post)
  {
    $post['excerpt']['plain'] = self::get_post_excerpt($post['id']);
    return $post['excerpt'];
  }

  public static function get_post_views($post_id)
  {
    if (false) {
      // if (!function_exists('wp_statistics_pages')) {
      //   throw new Exception(__('Please install pulgin WP-Statistics (https://wordpress.org/plugins/wp-statistics)', SAKURA_TEXT_DOMAIN));
      // } else {
      //   return intval(wp_statistics_pages('total', 'uri', $post_id));
      // }
    } else {
      $views = get_post_meta($post_id, 'views', true);
      if (!$views) {
        return 0;
      } else {
        return intval($views);
      }
    }
  }

  public static function get_post_word_count($post_id)
  {
    $post = get_post($post_id);
    if ($post) {
      // return $post->post_content;
      $doc = new DOMDocument();
      $internalErrors = libxml_use_internal_errors(true);
      $doc->loadHTML('<?xml encoding="utf-8" ?>' . $post->post_content);
      libxml_use_internal_errors($internalErrors);
      $string = $doc->textContent;
      $string = preg_replace('/\s+/', '', $string);
      return strlen($string);
    }
    return NAN;
  }
}
