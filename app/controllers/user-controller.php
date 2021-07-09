<?php

namespace Sakura\Controllers;

use Sakura\Controllers\BaseController;
use Sakura\Controllers\AvatarController;

class UserController extends BaseController
{
  /**
   * Warning: be carefull to use this, use UserController::get_user_public_meta
   * instead if not necessary
   *
   * @param integer $user_id
   * @param string $key
   * @param boolean $single
   *
   * @return mixed array | false
   */
  public static function get_user_meta(int $user_id, string $key = '', bool $single = false)
  {
    return  get_user_meta($user_id, $key, $single);
  }

  public static function get_user_public_meta(int $user_id)
  {
    $white_list = ['nickname', 'first_name', 'last_name', 'description', 'wp_user_level'];
    $meta = self::get_user_meta($user_id);
    $public_meta = [];

    foreach ($white_list as $key) {
      $public_meta[$key] = $meta[$key];
    }

    $public_meta['avatar'] = AvatarController::get_avatar($user_id);

    return $public_meta;
  }
}
