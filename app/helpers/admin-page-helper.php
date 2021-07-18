<?php

namespace Sakura\Helpers;

use Sakura\Helpers\ViteHelper;
use Sakura\Controllers\InitStateController;
use Sakura\Controllers\OptionController;

class AdminPageHelper extends ViteHelper
{
  public $page_title;
  public $menu_title;
  public $menu_slug;

  public function __construct()
  {
    $this->page_title = __('Sakura theme options', self::$text_domain);
    $this->menu_title = __('Sakura Options', self::$text_domain);
    $this->menu_slug = __('sakura_options', self::$text_domain);
    add_action('admin_menu', [$this, 'add_theme_page']);
    add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
  }

  public function add_theme_page()
  {
    add_theme_page($this->page_title, $this->menu_title, 'edit_theme_options', $this->menu_slug, function () {
      $template = new TemplateHelper();
      echo $template->load('admin-page-helper.twig')->renderBlock('admin_app', []);
    });
  }

  public function enqueue_scripts($hook)
  {
    if ("appearance_page_{$this->menu_slug}" === $hook) {
      $this->enqueue_common_scripts();
      if (SAKURA_DEVEPLOMENT) {
        $this->enqueue_development_scripts();
      } else {
        $this->enqueue_production_scripts();
      }
    }
  }

  public function enqueue_development_scripts()
  {
    wp_enqueue_script('[type:module]vite-client', self::$development_host . '/@vite/client', array(), null, false);

    wp_enqueue_script('[type:module]dev-main', self::$development_host . '/src/admin/main.ts', array(), null, true);

    wp_localize_script('[type:module]dev-main', 'AdminColors', $this->get_admin_color_css());

    wp_localize_script('[type:module]dev-main', 'InitState', (new InitStateController())->get_initial_state());

    wp_localize_script('[type:module]dev-main', 'SakuraOptions', ['data' => (new OptionController())->get_all_options()]);
  }

  public function enqueue_production_scripts()
  {
    $entry_key = 'src/admin/main.ts';
    $assets_base_path = get_template_directory_uri() . '/assets/admin/';
    $manifest = self::get_manifest_file('admin');

    // <script type="module" crossorigin src="http://localhost:9000/assets/index.36b06f45.js"></script>
    wp_enqueue_script('[type:module]chunk-entrance.js', $assets_base_path . $manifest[$entry_key]['file'], array(), null, false);

    wp_localize_script('[type:module]chunk-entrance.js', 'AdminColors', $this->get_admin_color_css());

    wp_localize_script('[type:module]chunk-entrance.js', 'InitState', (new InitStateController())->get_initial_state());

    wp_localize_script('[type:module]chunk-entrance.js', 'SakuraOptions', (new OptionController())->get_all_options());

    // <link rel="modulepreload" href="http://localhost:9000/assets/vendor.b3a324ba.js">
    foreach ($manifest[$entry_key]['imports'] as $index => $import) {
      wp_enqueue_style("[ref:modulepreload]chunk-vendors{$import}", $assets_base_path . $manifest[$import]['file']);
      if (empty($manifest[$import]['css'])) {
        continue;
      }
      foreach ($manifest[$import]['css'] as $css_index => $css_path) {
        wp_enqueue_style("sakura-chunk-{$import}-{$css_index}.css", $assets_base_path . $css_path);
      }
    }

    // <link rel="stylesheet" href="http://localhost:9000/assets/index.2c78c25a.css">
    foreach ($manifest[$entry_key]['css'] as $index => $path) {
      wp_enqueue_style("sakura-chunk-{$index}.css", $assets_base_path . $path);
    }
  }

  public function enqueue_common_scripts()
  {
    wp_enqueue_media();

    wp_enqueue_style('style.css', get_template_directory_uri() . '/style.css');

    wp_enqueue_style('fontawesome-free', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/all.min.css');
  }

  public  function get_admin_color_css()
  {
    // {"name":"Default","url":false,"colors":["#1d2327","#2c3338","#2271b1","#72aee6"],"icon_colors":{"base":"#a7aaad","focus":"#72aee6","current":"#fff"}}
    global $_wp_admin_css_colors;
    $theme = (array) $_wp_admin_css_colors[get_user_option('admin_color')];
    // $scheme = [
    //   'dark-primary' => $theme['colors'][0],
    //   'dark-secondary' => $theme['colors'][1],
    //   'light-primary' => $theme['colors'][2],
    //   'light-secondary' => $theme['colors'][3],
    //   'icon-base' => $theme['icon_colors']['base'],
    //   'icon-focus' => $theme['icon_colors']['focus'],
    //   'icon-current' => $theme['icon_colors']['current'],
    // ];
    return $theme;
    // $css = '';
    // foreach ($scheme as $key => $value) {
    //   $css .= "--{$key}:{$value};";
    // }
    // return $css;
  }
}
