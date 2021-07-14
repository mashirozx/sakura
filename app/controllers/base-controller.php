<?php

namespace  Sakura\Controllers;

use Sakura\Lib\BaseClass;

/**
 * The controller abstract base
 * @since 1.0.0
 * @license GPLv3
 * @author mashirozx <moezhx@outlook.com>
 */
class BaseController extends BaseClass
{
  /**
   * The rest API request parameters
   * @since 0.0.1
   * @var WP_REST_Request
   */
  protected $request;


  /**
   * Response status code
   * @since 0.0.1
   * @var WP_REST_Request
   */
  protected $code = 200;

  protected function request_parser(\WP_REST_Request $request)
  {
    $this->request = $request;
  }
}
