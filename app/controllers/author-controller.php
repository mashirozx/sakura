<?php

namespace Sakura\Controllers;

use Sakura\Controllers\UserController;
use Sakura\Controllers\AvatarController;
use Sakura\Lib\Exception;

class AuthorController extends UserController
{
  /**
   * Get author meta by ID with mutiple fields
   *
   * @param integer $author_id
   * @param array $fields
   *
   * @return void
   */
  public static function get_author_meta_fields(int $author_id, array $fields)
  {
    $author_meta = [];
    foreach ($fields as $field) {
      $meta = get_the_author_meta($field, $author_id);
      if (isset($meta)) {
        $author_meta[$field] = $meta;
      } else {
        throw new Exception("No such author or field: \$id={$author_id}, \$fields={$field}");
      }
    }
    return $author_meta;
  }

  /**
   * Get usefull meta fileds of author
   *
   * @param integer $author_id
   *
   * @return void
   */
  public static function get_author_meta(int $author_id)
  {
    $fields = ['description', 'display_name', 'nickname', 'user_level', 'user_nicename', 'user_status', 'user_url'];
    $meta = self::get_author_meta_fields($author_id, $fields);
    $meta['avatar'] = AvatarController::get_avatar($author_id);
    return $meta;
  }
}
