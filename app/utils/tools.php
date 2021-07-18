<?php

namespace Sakura\Utils;

use Rogervila\ArrayDiffMultidimensional;

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

  public static function get_text_from_dom($node, $text)
  {
    if (!is_null($node->childNodes)) {
      foreach ($node->childNodes as $node) {
        $text = self::get_text_from_dom($node, $text);
      }
    } else {
      return $text . $node->textContent . ' ';
    }
    return $text;
  }

  /**
   * https://stackoverflow.com/a/3877494/8083009
   *
   * @param array $aArray1
   * @param array $aArray2
   *
   * @return array
   */
  public static function array_recursive_diff(array $aArray1, array  $aArray2)
  {
    $aReturn = array();

    foreach ($aArray1 as $mKey => $mValue) {
      if (array_key_exists($mKey, $aArray2)) {
        if (is_array($mValue)) {
          $aRecursiveDiff = self::array_recursive_diff($mValue, $aArray2[$mKey]);
          if (count($aRecursiveDiff)) {
            $aReturn[$mKey] = $aRecursiveDiff;
          }
        } else {
          if ($mValue != $aArray2[$mKey]) {
            $aReturn[$mKey] = $mValue;
          }
        }
      } else {
        $aReturn[$mKey] = $mValue;
      }
    }
    return $aReturn;
  }
}
