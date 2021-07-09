<?php

namespace Sakura\Utils;

class Tools
{
  public static function echo_interceptor(callable $callback, ...$args)
  {
    ob_start();
    call_user_func($callback, ...$args);
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
  }

  // public function get_text_from_dom($node, $text) {
  //   if (!is_null($node->childNodes)) {
  //     foreach ($node->childNodes as $node) {
  //       $text = get_text_from_dom($node, $text);
  //     }
  //   }
  //   else {
  //     return $text . $node->textContent . ' ';
  //   }
  //   return $text;
  // }
}
