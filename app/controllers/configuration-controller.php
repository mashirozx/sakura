<?php

namespace Sakura\Controllers;

use WP_REST_Server;
use WP_REST_Request;
use WP_Error;
use Sakura\Lib\Exception;
use Sakura\Models\OptionModel;

class ConfigurationController extends BaseController
{
  public function public_options()
  {
    $keys = [
      // key  => default value
      'title' => 'Theme Sakura',
    ];
    $res = [];
    foreach ($keys as $key => $default) {
      $res[$key] = $this->sakura_options($key, $default);
    }
    return $res;
  }

  /**
   * Constructor.
   *
   * @since 5.0.0
   */
  public function __construct()
  {
    $this->namespace = 'sakura/v1';
    $this->rest_base = 'config';
  }

  /**
   * Registers the routes for comments.
   *
   * @since 5.0.0
   *
   * @see register_rest_route()
   */
  public function register_routes()
  {
    register_rest_route(
      $this->namespace,
      '/' . $this->rest_base,
      array(
        array(
          'methods'             => WP_REST_Server::READABLE,
          'callback'            => array($this, 'get_config'),
          'permission_callback' => array($this, 'get_config_permissions_check'),
          // 'args'                => $this->get_collection_params(),
        ),
        array(
          'methods'             => WP_REST_Server::EDITABLE,
          'callback'            => array($this, 'update_config'),
          'permission_callback' => array($this, 'update_config_permissions_check'),
          // 'args'                => $this->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
        ),
        // 'schema' => array($this, 'get_public_item_schema'),
      )
    );
  }

  public function get_config(WP_REST_Request $request)
  {
    $config = (array) OptionModel::get($this->rest_base);
    if (!$config) {
      return new WP_Error(
        'no_such_option',
        __('Maybe you should save the configuration bufore using it.', self::$text_domain),
        array('status' => 500)
      );
    } else {
      return $config;
    }
  }

  public function get_config_permissions_check(WP_REST_Request $request)
  {
    return true;
  }

  public function update_config(WP_REST_Request $request)
  {
    $original = (array) $this->get_config($request);
    $json = (array) self::json_validate($request->get_body());
    if (empty(array_diff($original, $json))) {
      return $original;
    }

    $config = OptionModel::update($this->rest_base, $json);
    if (!$config) {
      return new WP_Error(
        'save_config_failure',
        __('Unable to save configuration.', self::$text_domain),
        array('status' => 500)
      );
    } else {
      return $this->get_config($request);
    }
  }

  public function update_config_permissions_check(WP_REST_Request $request)
  {
    return true;
  }

  public function inite_theme()
  {
    $config = OptionModel::create($this->rest_base, (array)[]);
  }

  public static function json_validate(string $string)
  {
    $json = json_decode($string);

    return $json;
  }

  public function set_key_value(string $key, $value)
  {
    $json = (array) OptionModel::get($this->rest_base);
    if (!$json) {
      return new WP_Error(
        'no_such_option',
        __('Maybe you should save the configuration bufore using it.', self::$text_domain),
        array('status' => 500)
      );
    }
    $json[$key] = $value;
    $config = OptionModel::update($this->rest_base, $json);
    $config = $config ? $config : OptionModel::create($this->rest_base, $json);
    return $config;
  }

  public function sakura_options(string $namespace, $default)
  {
    $config = (array) OptionModel::get($this->rest_base);
    if (array_key_exists($namespace, $config)) {
      return $config[$namespace];
    } else {
      $this->set_key_value($namespace, $default);
      return $default;
    }
    // translators: %s: $namespace */
    // throw new Exception(
    //   sprintf(__("No existing database saving value or default value for option '%s'.", self::$text_domain), $namespace)
    // );
  }
}
