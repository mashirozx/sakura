<?php

namespace Sakura\Models;

class OptionModel extends BaseModel
{
  public static $namespace = 'sakura_options';

  public static function the_key(string $key)
  {
    return self::$namespace . "_{$key}";
  }

  public static function create(string $key,  $value)
  {
    return add_option(self::the_key($key), $value);
  }

  public static function get(string $key)
  {
    return get_option(self::the_key($key));
  }

  public static function update(string $key, $value)
  {
    return update_option(self::the_key($key), $value);
  }

  public static function delete(string $key)
  {
    return delete_option(self::the_key($key));
  }
}
