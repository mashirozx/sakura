<?php

// namespace Sakura;

define('SAKURA_VERSION', wp_get_theme()->get('Version'));
define('SAKURA_TEXT_DOMAIN', wp_get_theme()->get('TextDomain'));

// PHP loaders
require_once(__DIR__ . '/loader.php');

new \Sakura\Helpers\SetupHelper();
new \Sakura\Helpers\WhoopsHelper();
new \Sakura\Helpers\ViteRequireHelper();
new \Sakura\Helpers\CustomMenuMetaFieldsHelper();
new \Sakura\Helpers\CommentHelper();
new \Sakura\Helpers\PostQueryHelper('post');

new \Sakura\Routers\ApiRouter();
new \Sakura\Routers\PagesRouter();
