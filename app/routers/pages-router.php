<?php

namespace Sakura\Routers;

class PagesRouter
{
  public function __construct()
  {
    $this->add_rewrite_rules();
  }

  public function add_rewrite_rules()
  {
    // This is the front end auth page. Add this rewrite rule
    // to avoid 404 message when auth page first load, so the
    // response from PHP can be a blank page.
    // TODO: blank
    add_action('init', function () {
      add_rewrite_rule('sakura/auth$', 'index.php', 'top');
    });

    // TODO: Docker health check route
    // add_action('init', function () {
    //   add_rewrite_rule('health$', 'index.php', 'top');
    // });
  }
}
