<?php

namespace Sakura\Helpers;

use Sakura\Controllers;

class ViteRequireHelper
{
  // TODO: use a common .env file
  // public $development_host = 'http://192.168.28.26:9000';
  public $development_host = 'http://127.0.0.1:9000';

  function __construct()
  {
    add_action('wp_enqueue_scripts', [$this, 'enqueue_common_scripts']);
    add_action('wp_enqueue_scripts', [$this, 'enqueue_development_scripts']);
    // add_action('wp_enqueue_scripts', [$this, 'enqueue_production_scripts']);
    // add tag filters
    add_filter('script_loader_tag', [$this, 'script_tag_filter'], 10, 3);
    add_filter('style_loader_tag', [$this, 'style_tag_filter'], 10, 3);
  }

  public function enqueue_development_scripts()
  {
    wp_enqueue_script('[type:module]vite-client', $this->development_host . '/@vite/client', array(), null, false);
    wp_enqueue_script('[type:module]dev-main', $this->development_host . '/src/main.ts', array(), null, true);
  }

  public function enqueue_production_scripts()
  {
    $assets_base_path = get_template_directory_uri() . '/assets/dist/';
    $manifest = $this->get_manifest_file();

    // <script type="module" crossorigin src="http://localhost:9000/assets/index.36b06f45.js"></script>
    wp_enqueue_script('[type:module]chunk-vendors.js', $assets_base_path . $manifest['index.html']['file'], array(), null, false);

    // <link rel="modulepreload" href="http://localhost:9000/assets/vendor.b3a324ba.js">
    foreach ($manifest['index.html']['imports'] as $index => $import) {
      wp_enqueue_style("[ref:modulepreload]chunk-vendors-{$index}.js", $assets_base_path . $manifest[$import]['file']);
    }

    // <link rel="stylesheet" href="http://localhost:9000/assets/index.2c78c25a.css">
    foreach ($manifest['index.html']['css'] as $index => $path) {
      wp_enqueue_style("sakura-chunk-{$index}.css", $assets_base_path . $path);
    }
  }

  public function enqueue_common_scripts()
  {
    wp_enqueue_style('style.css', get_template_directory_uri() . '/style.css');

    wp_enqueue_style('fontawesome-free', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/all.min.css');

    // TODO: don't use vue.js as handler
    wp_enqueue_script('vue.js', 'https://unpkg.com/vue@next', array(), false, true);

    wp_localize_script('vue.js', 'InitState', (new Controllers\InitStateController())->get_initial_state());

    wp_enqueue_script('recaptcha', 'https://www.recaptcha.net/recaptcha/api.js?render=6LdKhX8bAAAAAF5HJprXtKvg3nfBJMfgd2o007PN', array(), false, true);
  }

  public function script_tag_filter($tag, $handle, $src)
  {
    if (preg_match('/^\[([^:]*)\:([^\]]*)\]/', $handle)) {
      preg_match('/^\[([^:]*)\:([^\]]*)\]/', $handle, $matches, PREG_OFFSET_CAPTURE);
      $template = new TemplateHelper();
      $tag =  $template->load('vite-require-helper.twig')->renderBlock('script', ['key' => $matches[1][0], 'value' => $matches[2][0], 'src' => esc_url($src)]);
    }
    return $tag;
  }

  public function style_tag_filter($tag, $handle, $src)
  {
    if (preg_match('/^\[([^:]*)\:([^\]]*)\]/', $handle)) {
      preg_match('/^\[([^:]*)\:([^\]]*)\]/', $handle, $matches, PREG_OFFSET_CAPTURE);
      $template = new TemplateHelper();
      $tag =  $template->load('vite-require-helper.twig')->renderBlock('style', ['key' => $matches[1][0], 'value' => $matches[2][0], 'href' => esc_url($src)]);
    }
    return $tag;
  }

  public function get_manifest_file()
  {
    $manifest = file_get_contents(__DIR__ . '/../assets/dist/manifest.json');
    return json_decode($manifest, true);
  }
}
