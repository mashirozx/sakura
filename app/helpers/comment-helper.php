<?php

namespace Sakura\Helpers;

use Sakura\Controllers\CommentController;
use Sakura\Models\CommentModel;
use WP_Comment;
use WP_Theme;

class CommentHelper
{
  public function __construct()
  {
    add_action('comment_post',  [$this, 'create_comment_actions'], 1, 3);
    add_action('edit_comment',  [$this, 'update_comment_actions'], 1, 2);
    add_action('delete_comment',  [$this, 'delete_comment_actions'], 1, 2);

    // TODO: not sure if it's ok to handle a lot of comments? If not ok, provide only the manual method
    // add_action('after_switch_theme', [$this, 'after_switch_theme_actions'], 1, 2);

    // deprecated, extends class-wp-rest-comments-controller instaed.
    // add_filter('rest_allow_anonymous_comments', '__return_true');
  }

  /**
   * Actions after create comment
   *
   * @param integer $comment_ID
   * @param int|string $comment_approved
   * @param array $commentdata
   *
   * @return void
   */
  public static function create_comment_actions(int $comment_ID,  $comment_approved, array $commentdata)
  {
    CommentModel::set_comment_ancestor_meta($comment_ID);
  }

  public static function update_comment_actions(int $comment_ID, array $data)
  {
    CommentModel::update_comment_ancestor_meta($comment_ID);
  }

  public static function delete_comment_actions(int $comment_ID, WP_Comment $comment)
  {
    // upgrade children/descendant comments' ancestor meta (ancestor means the parent/ancestor whose parent_id === 0)
    if (CommentModel::get_comment_ancestor_meta($comment_ID) == 0) {
      $descendant_comments = get_comments([
        'meta_key' => CommentModel::$ancestor_id_meta_key,
        'meta_value' => $comment_ID,
      ]);

      $child_comments = get_comments([
        'parent' => $comment_ID,
      ]);

      // set children's parent to 0
      foreach ($child_comments as $child_comment) {
        // https://developer.wordpress.org/reference/functions/wp_insert_comment/
        wp_update_comment([
          'comment_ID' => $child_comment->comment_ID,
          'comment_parent' => 0,
        ], false);
      }

      // update ancestor for descendant
      foreach ($descendant_comments as $descendant_comment) {
        CommentModel::update_comment_ancestor_meta($descendant_comment->comment_ID);
      }
    }
  }

  public static function after_switch_theme_actions(string $old_name, WP_Theme $old_theme)
  {
    CommentController::init_comment_ancestor_meta();
  }

  public static function filter_rest_allow_anonymous_comments($false, $request)
  {
    // return [
    //   'code' => 123
    // ];
    throw new \Exception("opps");
    // return true;
  }
}
