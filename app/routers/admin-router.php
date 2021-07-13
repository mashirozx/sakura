<?php

namespace Sakura\Routers;

use Sakura\Helpers\TemplateHelper;

class AdminRouter
{
  public function __construct()
  {
    add_action('admin_menu', [$this, 'add_theme_page']);
  }

  public function add_theme_page()
  {
    add_theme_page('page_title', 'menu-title', 'edit_theme_options', 'menu-slug', function () {
      $template = new TemplateHelper();
      echo $template->load('admin-rouer.twig')->renderBlock('admin_app', []);
    });
  }
}
