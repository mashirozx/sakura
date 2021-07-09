<?php

namespace Sakura\Controllers;

use Sakura\Controllers\TermController;

class CategoryController extends TermController
{
  public static function get_the_category(int $post_id)
  {
    return get_the_category($post_id);
  }
}
