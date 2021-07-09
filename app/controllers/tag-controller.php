<?php

namespace Sakura\Controllers;

use Sakura\Controllers\TermController;

class TagController extends TermController
{
  public static function get_the_tags(int $post_id)
  {
    return get_the_tags($post_id);
  }
}
