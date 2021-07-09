<?php

namespace Sakura\Controllers;

use Sakura\Helpers\CustomMenuMetaFieldsHelper;

class MenuController extends BaseController
{
  public function show(\WP_REST_Request $request)
  {
    $this->request_parser($request);

    $location = isset($_GET['location']) ?? $_GET['location'];

    $data = !$location ? $this->get_menus() : $this->get_menu($location);

    $response = new \WP_REST_Response($data);
    $response->set_status($this->code);

    return $response;
  }

  public function get_menu_location(string $location_name)
  {
    $locations = \get_nav_menu_locations();
    return $locations[$location_name];
  }

  public function get_menu($location)
  {
    $id = $this->get_menu_location($location);
    if (!$id) {
      return [];
    }
    $menu_items = \wp_get_nav_menu_items($id, array());
    $fitered_menu_items = array();
    foreach ($menu_items as $menu_item) {
      $fitered_menu_item = array(
        "id" => $menu_item->ID,
        'title' => $menu_item->title,
        'url' => $menu_item->url,
        'type' => $menu_item->type,
        'parent' => intval($menu_item->menu_item_parent),
      );

      foreach (array_keys(CustomMenuMetaFieldsHelper::fileds()) as $name) {
        $fitered_menu_item[$name] = get_post_meta($menu_item->ID, "_custom_menu_meta_{$name}", true);
      }

      array_push($fitered_menu_items, $fitered_menu_item);
    }
    return $fitered_menu_items;
  }

  public  function get_menus()
  {
    $menus = array();
    // $locations is an array where ([NAME] = MENU_ID);
    $locations = get_nav_menu_locations();

    foreach (array_keys($locations) as $location) {
      $menu = $this->get_menu($location);
      $menus[$location] = $menu;
    }

    return $menus;
  }
}
