<?php

namespace Sakura\Controllers;

use WP_REST_Server;
use WP_REST_Request;
use WP_Error;
use Sakura\Models\OptionModel;

class ConfigurationController extends BaseController
{
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
   * @since 4.7.0
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
          'methods'             => WP_REST_Server::CREATABLE,
          'callback'            => array($this, 'create_config'),
          'permission_callback' => array($this, 'create_config_permissions_check'),
          // 'args'                => $this->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
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

  public function create_config(WP_REST_Request $request)
  {
    $original = (array) $this->get_config($request);
    $json = (array) self::json_validate($request->get_body());
    if (empty(array_diff($original, $json))) {
      return $original;
    }

    $config = OptionModel::create($this->rest_base, $json);
    $config = $config ? $config : OptionModel::update($this->rest_base, $json);
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

  public function create_config_permissions_check(WP_REST_Request $request)
  {
    return true;
  }

  public function update_config(WP_REST_Request $request)
  {
    return $this->create_config($request);
  }

  public function update_config_permissions_check(WP_REST_Request $request)
  {
    return true;
  }

  public static function json_validate(string $string)
  {
    $json = json_decode($string);

    return $json;
  }
}
