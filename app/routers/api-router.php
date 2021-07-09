<?php

namespace Sakura\Routers;

use WP_REST_Controller;
use WP_REST_Server;
use Sakura\Controllers\InitStateController;
use Sakura\Controllers\MenuController;
use Sakura\Controllers\PostController;
use Sakura\Controllers\AuthorController;
use Sakura\Controllers\MediaController;
use Sakura\Controllers\CategoryController;
use Sakura\Controllers\TagController;
use Sakura\Controllers\CommentController;
use Sakura\Lib\ClassWpRestCommentsController;

class ApiRouter extends WP_REST_Controller
{
  public function __construct()
  {
    $this->namespace = 'sakura/v1';
    $this->register_rest_routes();
    $this->register_rest_fields();
  }

  /**
   * Add routers
   * @since 1.0.0
   * @license MIT
   * @author mashirozx <moezhx@outlook.com>
   */
  public function register_rest_routes()
  {
    add_action('rest_api_init', function () {
      // theme's initial states
      register_rest_route(
        $this->namespace,
        '/init-state',
        [
          'methods' =>  WP_REST_Server::READABLE,
          'callback' => [new InitStateController(), 'show'],
          'permission_callback' => function () {
            return true;
          }
        ]
      );

      // get menu items
      register_rest_route(
        $this->namespace,
        '/menu',
        [
          'methods' => WP_REST_Server::READABLE,
          'callback' => [new MenuController(), 'show'],
          'permission_callback' => function () {
            return true;
          }
        ]
      );

      // initial comment ancestor meta
      // TODO: AUTH
      register_rest_route(
        $this->namespace,
        '/init-comments-ancestor-meta',
        [
          'methods' => WP_REST_Server::READABLE,
          'callback' => [new InitStateController(), 'init_ancestor_meta_show'],
          'permission_callback' => function () {
            return true;
          }
        ]
      );

      // initial comment ua info meta
      // TODO: AUTH
      register_rest_route(
        $this->namespace,
        '/init-comments-user-agent-info-meta',
        [
          'methods' => WP_REST_Server::READABLE,
          'callback' => [new InitStateController(), 'init_user_agent_info_meta_show'],
          'permission_callback' => function () {
            return true;
          }
        ]
      );

      $rest_comments_controller = new ClassWpRestCommentsController();

      // custom create comment
      register_rest_route(
        $this->namespace,
        '/comments',
        [
          'methods' => WP_REST_Server::CREATABLE,
          'callback' => [$rest_comments_controller, 'create_item'],
          'permission_callback' => [$rest_comments_controller, 'create_item_permissions_check'],
        ]
      );

      // custom edit comment
      register_rest_route(
        $this->namespace,
        '/comments' . '/(?P<id>[\d]+)',
        array(
          'args'   => array(
            'id' => array(
              'description' => __('Unique identifier for the comment.'),
              'type'        => 'integer',
            ),
          ),
          array(
            'methods'             => WP_REST_Server::EDITABLE,
            'callback'            => [$rest_comments_controller, 'update_item'],
            'permission_callback' => [$rest_comments_controller, 'update_item_permissions_check'],
            'args'                => $this->get_endpoint_args_for_item_schema(WP_REST_Server::EDITABLE),
          ),
          array(
            'methods'             => WP_REST_Server::DELETABLE,
            'callback'            => [$rest_comments_controller, 'delete_item'],
            'permission_callback' => [$rest_comments_controller, 'delete_item_permissions_check'],
            'args'                => array(
              'force'    => array(
                'type'        => 'boolean',
                'default'     => false,
                'description' => __('Whether to bypass Trash and force deletion.'),
              ),
              'password' => array(
                'description' => __('The password for the parent post of the comment (if the post is password protected).'),
                'type'        => 'string',
              ),
            ),
          ),
          'schema' => array($this, 'get_public_item_schema'),
        )
      );
    });
  }

  /**
   * Add fields to existing endpoint
   * @since 1.0.0
   * @license MIT
   * @author mashirozx <moezhx@outlook.com>
   */
  public function register_rest_fields()
  {
    // add fields to /posts & /pages endpoint
    add_action('rest_api_init', function () {
      $this->register_wp_post_rest_fields(['post', 'page']);
    });

    // add fields to /comments endpoint
    add_action('rest_api_init', function () {
      $this->register_wp_comment_rest_fields(['comment']);
    });
  }

  /**
   * Common method of adding rest api fields to WP_POST output
   *
   * @param array $object_type
   * @return void
   */
  public function register_wp_post_rest_fields(array $object_type)
  {
    /**
     * Add comment_count field to $post
     */
    register_rest_field(
      $object_type,
      'comment_count',
      [
        'get_callback' => function ($post, $attr, $request, $object_type) {
          return PostController::get_comments_number($post['id']);
        },
        'update_callback' => null,
        'schema' => null
      ]
    );

    register_rest_field(
      $object_type,
      'view_count',
      [
        'get_callback' => function ($post, $attr, $request, $object_type) {
          return PostController::get_post_views($post['id']);
        },
        'update_callback' => null,
        'schema' => null
      ]
    );

    register_rest_field(
      $object_type,
      'words_count',
      [
        'get_callback' => function ($post, $attr, $request, $object_type) {
          return PostController::get_post_word_count($post['id']);
        },
        'update_callback' => null,
        'schema' => null
      ]
    );

    /**
     * Add markdown field to $post['content]
     */
    register_rest_field(
      $object_type,
      'content',
      [
        'get_callback' => function ($post, $attr, $request, $object_type) {
          return PostController::rest_api_post_content_filter($post);
        },
        'update_callback' => null,
        'schema' => null
      ]
    );

    /**
     * Add plain field to $post['excerpt]
     */
    register_rest_field(
      $object_type,
      'excerpt',
      [
        'get_callback' => function ($post, $attr, $request, $object_type) {
          return PostController::rest_api_post_excerpt_filter($post);
        },
        'update_callback' => null,
        'schema' => null
      ]
    );

    register_rest_field(
      $object_type,
      'author_meta',
      [
        'get_callback' => function ($post, $attr, $request, $object_type) {
          return AuthorController::get_author_meta($post['author']);
        },
        'update_callback' => null,
        'schema' => null
      ]
    );

    register_rest_field(
      $object_type,
      'featured_media_meta',
      [
        'get_callback' => function ($post, $attr, $request, $object_type) {
          return MediaController::get_attachment_metadata($post['featured_media']);
        },
        'update_callback' => null,
        'schema' => null
      ]
    );


    register_rest_field(
      $object_type,
      'categories_meta',
      [
        'get_callback' => function ($post, $attr, $request, $object_type) {
          return CategoryController::get_the_category($post['id']);
        },
        'update_callback' => null,
        'schema' => null
      ]
    );

    register_rest_field(
      $object_type,
      'tags_meta',
      [
        'get_callback' => function ($post, $attr, $request, $object_type) {
          return TagController::get_the_tags($post['id']);
        },
        'update_callback' => null,
        'schema' => null
      ]
    );
    // end of public func
  }

  /**
   * Common method of adding rest api fields to WP_COMMENT output
   *
   * @param array $object_type
   * @return void
   */
  public static function register_wp_comment_rest_fields(array $object_type)
  {
    /**
     * Add markdown field to $post['content]
     */
    register_rest_field(
      $object_type,
      'content',
      [
        'get_callback' => function ($comment, $attr, $request, $object_type) {
          return CommentController::rest_api_comment_content_filter($comment);
        },
        'update_callback' => null,
        'schema' => null
      ]
    );

    // get the custom meta fields
    register_rest_field(
      $object_type,
      'meta_fields',
      [
        'get_callback' => function ($comment, $attr, $request, $object_type) {
          return CommentController::get_comment_meta_fields($comment['id']);
        },
        'update_callback' => null,
        'schema' => null
      ]
    );

    // get comment children preview
    // register_rest_field(
    //   $object_type,
    //   'children_preview',
    //   [
    //     'get_callback' => function ($comment, $attr, $request, $object_type) {
    //       return CommentController::get_comment_children($comment['id'], 3, 1, 0, 'DESC');
    //     },
    //     'update_callback' => null,
    //     'schema' => null
    //   ]
    // );
  }
}
