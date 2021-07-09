<?php
/*------------------------------------*\
Auto loaders
\*------------------------------------*/

// Composer autoload
require_once(__DIR__ . '/vendor/autoload.php');

// Autoload namespace Sakura
spl_autoload_register(function ($class_name) {
  $namespaces = explode('\\', $class_name);
  $namespaces_length = count($namespaces);

  if ($namespaces[0] !== 'Sakura') {
    // new Exception("No such class '{$class_name}'");
    return;
  }

  $path = __DIR__;
  $index = 1;

  foreach ($namespaces as $namespace) {
    if ($index === 1) {
      $path .= '';
    } elseif ($index < $namespaces_length) {
      $path .= '/' . strtolower($namespace);
    } else {
      $path .= '/' . strtolower(preg_replace('%([a-z])([A-Z])%', '\1-\2', $namespace));
    }
    $index++;
  }

  // TODO: check if file exists before require
  require_once $path . '.php';
});
