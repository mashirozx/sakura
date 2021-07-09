<?php

namespace Sakura\Utils;

use Sakura\Lib\Exception;
use GeoIp2\Database\Reader;
use Ritaswc\ZxIPAddress\IPTool;

class IpParser
{
  public static $version = SAKURA_VERSION;
  public static $text_domain = SAKURA_TEXT_DOMAIN;

  public static function get_location(string $ip)
  {
    try {
      // return self::get_ip_location_geoip2_string($ip);
      return self::get_ip_location_qqwary_string($ip);
    } catch (Exception $e) {
      return self::message()['unknown_error'] .  " [current: {$ip}]";
    }
  }

  public static function get_ip_location_qqwary_string(string $ip)
  {
    return preg_replace('/\s+/', ' ', self::get_ip_location_qqwary($ip)['disp']);
  }

  public static function get_ip_location_geoip2_string(string $ip)
  {
    // de en es fr ja pt-BR ru zh-CN
    $lang = "zh-CN";
    if (self::is_private_ip($ip)) {
      return [self::message()['private']];
    }
    $db = self::get_ip_location_geoip2($ip);
    $city =  self::auto_select_geoip2_lang($lang, $db->city->names);
    $subdivisions = self::auto_select_geoip2_lang($lang, $db->subdivisions[0]->names);
    $country = self::auto_select_geoip2_lang($lang, $db->country->names);
    $array = [$city, $subdivisions, $country];
    if ($lang === 'zh-CN' || 'ja') {
      $array = array_reverse($array);
    }
    return join(" ", array_filter($array));
  }

  private static function auto_select_geoip2_lang(string $default, array $names)
  {
    return $names[$default] ?? $names['en'] ?? $names['zh-CN'] ?? $names['de'] ?? $names['es'] ?? $names['fr'] ?? $names['ja'] ?? $names['ru'] ?? $names['pt-BR'] ?? '';
  }

  public static function message()
  {
    return [
      'private' => __("Intranet", self::$text_domain),
      'nomatch' => __("Earth", self::$text_domain),
      'unknown_error' => __("IP parser handling error.", self::$text_domain),
    ];
  }

  // https://github.com/maxmind/GeoIP2-php#readme
  public static function get_ip_location_geoip2(string $ip)
  {
    if (self::is_private_ip($ip)) {
      throw new Exception("GeoIP2 doesn't support private IP range. [current: {$ip}]");
    }
    $reader = new Reader(__DIR__ . '/../cache/GeoLite2-City.mmdb');
    $record = $reader->city($ip);
    return $record;
  }

  public static function get_ip_location_qqwary(string $ip)
  {
    return IPTool::query($ip);
  }

  static public function is_ip($ip = NULL): bool
  {
    return filter_var(
      $ip,
      FILTER_VALIDATE_IP,
      FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6
    ) === $ip ? TRUE : FALSE;
  }

  static public function is_ipv4($ip = NULL): bool
  {
    return filter_var(
      $ip,
      FILTER_VALIDATE_IP,
      FILTER_FLAG_IPV4
    ) === $ip ? TRUE : FALSE;
  }

  static public function is_ipv6($ip = NULL): bool
  {
    return filter_var(
      $ip,
      FILTER_VALIDATE_IP,
      FILTER_FLAG_IPV6
    ) === $ip ? TRUE : FALSE;
  }

  static public function is_public_ip($ip = NULL): bool
  {
    return filter_var(
      $ip,
      FILTER_VALIDATE_IP,
      FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
    ) === $ip ? TRUE : FALSE;
  }

  static public function is_public_ipv4($ip = NULL): bool
  {
    return filter_var(
      $ip,
      FILTER_VALIDATE_IP,
      FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
    ) === $ip ? TRUE : FALSE;
  }

  static public function is_public_ipv6($ip = NULL): bool
  {
    return filter_var(
      $ip,
      FILTER_VALIDATE_IP,
      FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
    ) === $ip ? TRUE : FALSE;
  }

  static public function is_private_ip($ip = NULL): bool
  {
    return self::is_ip($ip) && !self::is_public_ip($ip);
  }

  static public function is_private_ipv4($ip = NULL): bool
  {
    return self::is_ipv4($ip) && !self::is_public_ipv4($ip);
  }

  static public function is_private_ipv6($ip = NULL): bool
  {
    return self::is_ipv6($ip) && !self::is_public_ipv6($ip);
  }
}
