<?php

namespace Sakura\Controllers;

use Sakura\Controllers\BaseController;

/**
 * Base controller for WP_Term
 */
class TermController extends BaseController
{
  public static function get_terms(int $post_id)
  {
    return get_terms($post_id);
  }
}
