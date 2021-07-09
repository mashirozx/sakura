<?php

namespace Sakura\Helpers;

use Exception;

/**
 * Add custom fields to menu item
 * https://www.kathyisawesome.com/add-custom-fields-to-wordpress-menu-items/
 */
class CustomMenuMetaFieldsHelper
{
  public static function fileds()
  {
    return [
      'icon' => [
        'label' => __('Icon', SAKURA_TEXT_DOMAIN),
        'desc' => __('Menu item icon', SAKURA_TEXT_DOMAIN),
      ],
      'class' => [
        'label' => __('Class', SAKURA_TEXT_DOMAIN),
        'desc' => __('Menu item class class <code>Array&lt;string&gt;</code>', SAKURA_TEXT_DOMAIN),
      ],
    ];
  }

  public static function is_key_in_fields(string $key)
  {
    return array_key_exists($key, self::fileds());
  }

  public function __construct()
  {
    add_action('wp_nav_menu_item_custom_fields', [$this, 'add_fileds'], 10, 2);
    add_action('wp_update_nav_menu_item', [$this, 'update_fileds'], 10, 2);
  }

  public function add_fileds($item_id, $item)
  {
    $template = new TemplateHelper();
    echo $template->load('custom-menu-meta-fields-helper.twig')->renderBlock('id_input', ['item_id' => $item_id,]);

    foreach ($this->fileds() as $key => $value) {
      wp_nonce_field("custom_menu_meta_{$key}_nonce", "_custom_menu_meta_{$key}_nonce_name");
      $custom_menu_meta = get_post_meta($item_id, "_custom_menu_meta_{$key}", true);
      $label = $value['label'];
      $desc = $value['desc'];
      $esc_attr_custom_menu_meta = esc_attr($custom_menu_meta);

      echo $template->load('custom-menu-meta-fields-helper.twig')->renderBlock('input_field', [
        'item_id' => $item_id,
        'key' => $key,
        'label' => $label,
        'esc_attr_custom_menu_meta' => $esc_attr_custom_menu_meta,
        'desc' => $desc
      ]);
    }
  }

  /**
   * admain-ajax filter
   */
  public function update_fileds($menu_id, $menu_item_db_id)
  {
    // throw new Exception("Debug $menu_id");
    foreach ($this->fileds() as $key => $value) {
      if (!isset($_POST["_custom_menu_meta_{$key}_nonce_name"])) {
        // when first add a menu item, this can be empty (undefined)
        return $menu_id;
      } else if (!wp_verify_nonce($_POST["_custom_menu_meta_{$key}_nonce_name"], "custom_menu_meta_{$key}_nonce")) {
        throw new Exception("Invalid `_custom_menu_meta_{$key}_nonce_name` in {$_POST}, \$menu_id={$menu_id}");
        return $menu_id;
      }

      if (isset($_POST["custom_menu_meta_{$key}"][$menu_item_db_id])) {
        $sanitized_data = sanitize_text_field($_POST["custom_menu_meta_{$key}"][$menu_item_db_id]);
        update_post_meta($menu_item_db_id, "_custom_menu_meta_{$key}", $sanitized_data);
      } else {
        delete_post_meta($menu_item_db_id, "_custom_menu_meta_{$key}");
      }
    }
  }
}
