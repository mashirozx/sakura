<?php

namespace Sakura\Controllers;

use Sakura\Lib\Exception;
use Sakura\Controllers\BaseController;
use Sakura\Models\CommentModel;
use Sakura\Controllers\AvatarController;
use WP_REST_Request;
use WP_REST_Response;

class CommentController extends BaseController
{
  /**
   * Set comment ancestor_id from meta data when init
   * @return void
   */
  public static function init_comment_ancestor_meta()
  {
    $res = [];
    $comments = get_comments();
    foreach ($comments as $comment) {
      $comment_ID = $comment->comment_ID;
      // delete_comment_meta($comment_ID, self::$ancestor_id_meta_key);
      $ancestor_id = CommentModel::get_comment_ancestor_meta($comment_ID);
      // $ancestor_id = $ancestor_id == false ? false : intval($ancestor_id);
      // array_push($res, [$comment_ID, $ancestor_id]);
      if ($ancestor_id === 0) {
        array_push($res, "[KEEPT] {$comment_ID}:{$ancestor_id} -> {$comment_ID}:{$ancestor_id}");
      } elseif (empty($ancestor_id)) {
        $meta = CommentModel::set_comment_ancestor_meta($comment_ID);
        array_push($res, "[CREATE] {$comment_ID}:{$ancestor_id} -> {$comment_ID}:{$meta}");
      } elseif (empty(CommentModel::get_comments([$ancestor_id]))) {
        $meta = CommentModel::update_comment_ancestor_meta($comment_ID);
        array_push($res, "[UPDATE] {$comment_ID}:{$ancestor_id} -> {$comment_ID}:{$meta}");
      } else {
        array_push($res, "[KEEPT] {$comment_ID}:{$ancestor_id} -> {$comment_ID}:{$ancestor_id}");
      }
    }
    return $res;
  }

  /**
   * Set comment ancestor_id from meta data when init
   * @return void
   */
  public static function init_comment_user_agent_info_meta()
  {
    $res = [];
    $comments = get_comments();
    foreach ($comments as $comment) {
      $comment_ID = $comment->comment_ID;
      // delete_comment_meta($comment_ID, self::$ancestor_id_meta_key);
      $user_agent_info = CommentModel::get_comment_user_agent_info_meta($comment_ID);
      if (empty($user_agent_info)) {
        $meta = CommentModel::set_comment_user_agent_info_meta($comment_ID);
        array_push($res, ['type' => 'CREATE', 'ID' => $comment_ID, 'ua' => $meta]);
      } else {
        $meta = CommentModel::update_comment_user_agent_info_meta($comment_ID);
        array_push($res, ['type' => 'UPDATE', 'ID' => $comment_ID, 'ua' => $meta]);
      }
    }
    return $res;
  }

  /**
   * Get comment's all children/descendant
   *
   * @param integer $comment_ID
   * @param integer $per_page
   * @param integer $page
   * @param integer $offset
   * @param string $order (string) How to order retrieved comments. Accepts 'ASC', 'DESC'. Default: 'DESC'.
   *
   * @return array
   */
  public static function get_comment_children(int $comment_ID, int $per_page, int $page, int $offset, string $order)
  {
    return CommentModel::get_comments_with_public_fields([
      'meta_key' => CommentModel::$ancestor_id_meta_key,
      'meta_value' => $comment_ID,
      'number' => $per_page,
      'paged' => $page,
      'offset' => $offset,
      // (string|array) Comment status or array of statuses. To use 'meta_value' or 'meta_value_num', $meta_key must also be defined. To sort by a specific $meta_query clause, use that clause's array key. Accepts 'comment_agent', 'comment_approved', 'comment_author', 'comment_author_email', 'comment_author_IP', 'comment_author_url', 'comment_content', 'comment_date', 'comment_date_gmt', 'comment_ID', 'comment_karma', 'comment_parent', 'comment_post_ID', 'comment_type', 'user_id', 'comment__in', 'meta_value', 'meta_value_num', the value of $meta_key, and the array keys of $meta_query. Also accepts false, an empty array, or 'none' to disable ORDER BY clause. Default: 'comment_date_gmt'.
      // TODO: order by 'like'
      'orderby' => 'comment_date_gmt',
      'order' => $order,
    ]);
  }

  public static function get_comment_meta_fields(int $comment_ID)
  {
    return CommentModel::get_comment_meta_fields($comment_ID);
  }

  public static function get_comment_plain(int $comment_ID)
  {
    $comment = CommentModel::get_comment($comment_ID);
    return $comment->comment_content;
  }

  public static function rest_api_comment_content_filter(array $comment)
  {
    $comment['content']['plain'] = self::get_comment_plain($comment['id']);
    return $comment['content'];
  }
}
