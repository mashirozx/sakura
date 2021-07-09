<?php

namespace Sakura\Helpers;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Util\Misc;
use Whoops\Run;

class WhoopsHelper
{
  public function __construct()
  {
    if (self::is_debug()) {
      $whoops = new Run();
      // TODO: not working??
      if (wp_is_json_request()) {
        $whoops->pushHandler(new JsonResponseHandler);
      } elseif (Misc::isCommandLine()) {
        $whoops->pushHandler(new PlainTextHandler);
      } else {
        $whoops->pushHandler(new PrettyPageHandler);
      }
      $whoops->register();
    }
  }

  /**
   * @return bool
   */
  public static function is_debug()
  {
    return defined('WP_DEBUG') && WP_DEBUG;
  }
}
