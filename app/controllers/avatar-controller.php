<?php

namespace Sakura\Controllers;

use Sakura\Controllers\BaseController;

class AvatarController extends BaseController
{
  /**
   * Get avatar set of all sizes
   *
   * @param mixed (int|string) $id_or_email
   * @return string
   */
  public static function get_avatar($id_or_email)
  {
    // TODO: use standard 24/48/96
    $sizes = [
      // 'small' => 24,
      // 'normal' => 48,
      // 'large' => 96,
      '24' => 24,
      '48' => 48,
      '96' => 96
    ];
    $avatar_array = [];
    foreach ($sizes as $key => $value) {
      $avatar_array[$key] = get_avatar_url($id_or_email, ['size' => $value, 'default' => 'avatar_default']);
    }
    return $avatar_array;
  }
}
