<?php

namespace Sakura\Helpers;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * Twig engine template loader markup
 * @since 0.0.1
 * @license MIT
 * @author mashirozx <moezhx@outlook.com>
 */
class TemplateHelper
{
  public $loader;
  public $twig;
  public $template;

  public function __construct()
  {
    $this->loader = new FilesystemLoader(array_map(function ($path) {
      return __DIR__ . '/' . $path;
    }, $this->loader_path_array()));
    $this->twig = new Environment($this->loader);
  }

  public static function loader_path_array()
  {
    return [
      '../views/helpers',
      '../views'
    ];
  }

  public function load($template_name)
  {
    $this->template = $this->twig->load($template_name);
    return $this->template;
  }

  public function render(...$params)
  {
    return $this->twig->render(...$params);
  }

  public function addFunction($function_names)
  {
    if (is_array($function_names)) {
      foreach ($function_names as $function_name) {
        $this->twig->addFunction(new TwigFunction($function_name, $function_name));
      }
    } elseif (is_string($function_names)) {
      $this->twig->addFunction(new TwigFunction($function_names, $function_names));
    }
  }
}
