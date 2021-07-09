<?php

namespace Sakura\Models;

use Sakura\Lib\Exception;
use Sakura\Models\BaseModel;
use Sakura\Utils\UaParser;
use Sakura\Utils\IpParser;
use Sakura\Controllers\AvatarController;

class CommentModel extends BaseModel
{
  public static $ancestor_id_meta_key = 'ancestor_id';
  public static $user_agent_info_meta_key = 'user_agent_info';
  public static $user_location_meta_key = 'user_location';

  public static function get_comments(array $comment_IDs)
  {
    return get_comments(['comment__in' => $comment_IDs]);
  }

  public static function get_comment(int $comment_ID)
  {
    return get_comment($comment_ID);
  }

  public static function get_comment_childern(int $comment_ID)
  {
    return get_comments(['parent' => $comment_ID]);
  }

  public static function get_comments_of_post(int $post_id)
  {
    return get_comments(['post_id' => $post_id]);
  }

  public static function get_comment_parent_id(int $comment_ID)
  {
    $comment = self::get_comments([$comment_ID]);
    return intval($comment[0]->comment_parent);
  }

  public static function get_comment_ancestor_id(int $comment_ID, $target = true)
  {
    $parent_id = self::get_comment_parent_id($comment_ID);
    if ($parent_id > 0) {
      return self::get_comment_ancestor_id($parent_id, false);
    } else {
      return $target ? $parent_id : $comment_ID;
    }
  }

  public static function set_comment_ancestor_meta(int $comment_ID)
  {
    $ancestor_id = self::get_comment_ancestor_id($comment_ID);
    add_comment_meta($comment_ID, self::$ancestor_id_meta_key, $ancestor_id, true);
    return $ancestor_id;
  }

  public static function update_comment_ancestor_meta(int $comment_ID)
  {
    $ancestor_id = self::get_comment_ancestor_id($comment_ID);
    update_comment_meta($comment_ID, self::$ancestor_id_meta_key, $ancestor_id);
    return $ancestor_id;
  }

  /**
   * Get ancestor id from meta
   *
   * @param integer $comment_ID
   * @param boolean $display If ture, will throw error if meta not exist. If flase, will return NULL if not exist.
   * @return void
   */
  public static function get_comment_ancestor_meta(int $comment_ID, $display = false)
  {
    $ancestor_id = get_comment_meta($comment_ID, self::$ancestor_id_meta_key, true);
    if ($ancestor_id == '0') {
      return 0;
    } elseif (!$ancestor_id) {
      if ($display) {
        throw new Exception("Please init ancestor_id first");
      }
      return NULL;
    } else {
      return intval($ancestor_id);
    }
  }

  public static function set_comment_user_agent_info_meta(int $comment_ID)
  {
    $user_agent = self::get_comment($comment_ID)->comment_agent;
    $parser = new UaParser($user_agent);
    $user_agent_info = $parser->get_public_display_content();
    add_comment_meta($comment_ID, self::$user_agent_info_meta_key, $user_agent_info, true);
    return $user_agent_info;
  }

  public static function update_comment_user_agent_info_meta(int $comment_ID)
  {
    $user_agent = self::get_comment($comment_ID)->comment_agent;
    $parser = new UaParser($user_agent);
    $user_agent_info = $parser->get_public_display_content();
    update_comment_meta($comment_ID, self::$user_agent_info_meta_key, $user_agent_info);
    return $user_agent_info;
  }

  /**
   * Get user agent info from meta
   *
   * @param integer $comment_ID
   * @param boolean $display If ture, will throw error if meta not exist. If flase, will return [] if not exist.
   * @return void
   */
  public static function get_comment_user_agent_info_meta(int $comment_ID, $display = false)
  {
    $user_agent_info = get_comment_meta($comment_ID, self::$user_agent_info_meta_key, false);
    if (empty($user_agent_info) && $display) {
      throw new Exception("Please init user_agent_info first");
    }
    return $user_agent_info;
  }

  public static function set_comment_user_location_meta(int $comment_ID)
  {
    $comment_author_IP = self::get_comment($comment_ID)->comment_author_IP;
    $location = IpParser::get_location($comment_author_IP);
    add_comment_meta($comment_ID, self::$user_location_meta_key, $location, true);
    return $location;
  }

  public static function update_comment_user_location_meta(int $comment_ID)
  {
    $comment_author_IP = self::get_comment($comment_ID)->comment_author_IP;
    $location = IpParser::get_location($comment_author_IP);
    update_comment_meta($comment_ID, self::$user_location_meta_key, $location);
    return $location;
  }

  /**
   * Get user location from meta
   *
   * @param integer $comment_ID
   * @param boolean $display If ture, will throw error if meta not exist. If flase, will return false if not exist.
   * @return void
   */
  public static function get_comment_user_location_meta(int $comment_ID, $display = false)
  {
    $location = get_comment_meta($comment_ID, self::$user_location_meta_key, true);
    if (empty($location) && $display) {
      throw new Exception("Please init user_location first");
    }
    return $location;
  }

  /**
   * Get comment public display meta
   *
   * @param array string|array $args Optional. Array or string of arguments. See WP_Comment_Query::__construct() for information on accepted arguments. Default empty.
   * @return void
   */
  public static function get_comments_with_public_fields(array $args)
  {
    $comments = get_comments($args);

    $output_comments = [];

    foreach ($comments as $comment) {
      $output_comment = [
        'id' => intval($comment->comment_ID),
        'post' => intval($comment->comment_post_ID),
        'parent' => intval($comment->comment_parent),
        'author' => intval($comment->user_id),
        'author_name' => $comment->comment_author,
        // 'comment_author_email' => $comment->comment_author_email,
        'author_url' => $comment->comment_author_url,
        // 'comment_author_IP' => $comment->comment_author_IP,
        'date' => $comment->comment_date,
        'date_gmt' => $comment->comment_date_gmt,
        'content' => [
          'rendered' => '', // TODO
          'plain' => $comment->comment_content
        ],
        'link' => '', // TODO: get_comment_link(),
        // 'comment_karma' => $comment->comment_karma,
        'status' => $comment->comment_approved === '1' ? 'approved' : '', // TODO
        // 'comment_agent' => $comment->comment_agent,
        'type' => $comment->comment_type,
        'author_avatar_urls' => AvatarController::get_avatar($comment->comment_author_email),
      ];
      $output_comment['meta_fields'] = self::get_comment_meta_fields($comment->comment_ID);

      array_push($output_comments, $output_comment);
    }

    return $output_comments;
  }

  /**
   * Add fields to input array $comment and return it
   *
   * @param integer $comment_ID
   *
   * @return void
   */
  public static function get_comment_meta_fields(int $comment_ID)
  {
    $meta = [];

    // $ancestor_id = self::get_comment_ancestor_meta($comment_ID);
    // if ($ancestor_id === NULL) {
    //   $ancestor_id = self::set_comment_ancestor_meta($comment_ID);
    // }
    // $meta['ancestor_id'] = $ancestor_id;

    $user_agent_info = self::get_comment_user_agent_info_meta($comment_ID);
    if (empty($user_agent_info)) {
      $user_agent_info = self::set_comment_user_agent_info_meta($comment_ID);
    }
    $meta['user_agent_info'] = $user_agent_info;

    $user_location = self::get_comment_user_location_meta($comment_ID);
    // TODO: not fully tested if '' or false or NULL
    // TODO: language option
    if ($user_location === '') {
      $user_location = self::set_comment_user_location_meta($comment_ID);
    }
    $meta['user_location'] = $user_location;

    return $meta;
  }
}
