<?php

/**
 * Router
 */
add_action('rest_api_init', function () {
  register_rest_route('sakura/v1', '/image/upload', array(
      'methods' => 'POST',
      'callback' => 'upload_image',
  ));
  register_rest_route('sakura/v1', '/cache_search/json', array(
    'methods' => 'GET',
    'callback' => 'cache_search_json',
  ));
});

/**
 * Image uploader response
 */
function upload_image(WP_REST_Request $request)
{
  // see: https://developer.wordpress.org/rest-api/requests/

  // handle file params $file === $_FILES
  /**
   * curl \
   *   -F "filecomment=This is an img file" \
   *   -F "cmt_img_file=@screenshot.jpg" \
   *   https://dev.2heng.xin/wp-json/sakura/v1/image/upload
   */
  // $file = $request->get_file_params();
  if ( !check_ajax_referer('wp_rest', '_wpnonce', false) ) {
    $output = array(
      'status' => 403,
      'success' => false,
      'message' => 'Unauthorized client.',
      'link' => "https://view.moezx.cc/images/2019/11/14/step04.md.png",
      'proxy' => akina_option('cmt_image_proxy') . "https://view.moezx.cc/images/2019/11/14/step04.md.png",
    );
    $result = new WP_REST_Response($output, 403);
    $result->set_headers(array('Content-Type' => 'application/json'));
    return $result;
  }

  switch (akina_option("img_upload_api")) {
    case 'imgur':
      $image = file_get_contents($_FILES["cmt_img_file"]["tmp_name"]);
      $API_Request = Imgur_API($image);
      break;
    case 'smms':
      $image = $_FILES;
      $API_Request = SMMS_API($image);
      break;
    case 'chevereto':
      $image = file_get_contents($_FILES["cmt_img_file"]["tmp_name"]);
      $API_Request = Chevereto_API($image);
      break;
  }

  $result = new WP_REST_Response($API_Request, $API_Request->status);
  $result->set_headers(array('Content-Type' => 'application/json'));
  return $result;
}

/**
 * Chevereto upload interface
 */
function Chevereto_API($image)
{
    $fields = array(
      'source' => base64_encode($image),
      'key' => akina_option('chevereto_api_key')
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, akina_option('cheverto_url').'/api/1/upload');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

    $reply = curl_exec($ch);
    curl_close($ch);

    $reply = json_decode($reply);

    if ($reply->status_txt == 'OK' && $reply->status_code == 200) {
        $status = 200;
        $success = true;
        $message = "success";
        $link = $reply->image->image->url;
        $proxy = akina_option('cmt_image_proxy') . $link;
    } else {
        $status = $reply->status_code;
        $success = false;
        $message = $reply->error->message;
        $link = 'https://view.moezx.cc/images/2019/10/28/default_d_h_large.gif';
        $proxy = akina_option('cmt_image_proxy') . $link;
    }
    $output = array(
        'status' => $status,
        'success' => $success,
        'message' => $message,
        'link' => $link,
        'proxy' => $proxy,
    );
    return $output;
}

/**
 * Imgur upload interface
 */
function Imgur_API($image)
{
    $client_id = akina_option('imgur_client_id');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, akina_option('imgur_upload_image_proxy'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => base64_encode($image)));

    $reply = curl_exec($ch);
    curl_close($ch);

    $reply = json_decode($reply);

    if ($reply->success && $reply->status == 200) {
        $status = 200;
        $success = true;
        $message = "success";
        $link = $reply->data->link;
        $proxy = akina_option('cmt_image_proxy') . $link;
    } else {
        $status = $reply->status;
        $success = false;
        $message = $reply->data->error;
        $link = 'https://view.moezx.cc/images/2019/10/28/default_d_h_large.gif';
        $proxy = akina_option('cmt_image_proxy') . $link;
    }
    $output = array(
        'status' => $status,
        'success' => $success,
        'message' => $message,
        'link' => $link,
        'proxy' => $proxy,
    );
    return $output;
}

/**
 * smms upload interface
 */
function SMMS_API($image)
{
  $client_id = akina_option('smms_client_id');

  $filename = $image['cmt_img_file']['name'];
  $filedata = $image['cmt_img_file']['tmp_name'];
  $filesize = $image['cmt_img_file']['size'];

  $url = "https://sm.ms/api/v2/upload";
  $headers = array();
  array_push($headers, "Content-Type: multipart/form-data");
  array_push($headers, "Authorization: Basic " . $client_id);
  array_push($headers, "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97");

  $finfo = new \finfo(FILEINFO_MIME_TYPE);
  $mimetype = $finfo->file($filedata);

  $fields = array('smfile' => curl_file_create($filedata, $mimetype, $filename));

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

  $reply = curl_exec($ch);
  curl_close($ch);

  $reply = json_decode($reply);

  if ($reply->success && $reply->code == 'success') {
    $status = 200;
    $success = true;
    $message = $reply->message;
    $link = $reply->data->url;
    $proxy = akina_option('cmt_image_proxy') . $link;
  } else if (preg_match("/Image upload repeated limit/i", $reply->message, $matches)) {
    $status = 200; // sm.ms 接口不规范，建议检测到重复的情况下返回标准化的 code，并单独把 url 放进一个字段
    $success = true;
    $message = $reply->message;
    $link = str_replace('Image upload repeated limit, this image exists at: ', '', $reply->message);
    $proxy = akina_option('cmt_image_proxy') . $link;
  } else {
    $status = 400;
    $success = false;
    $message = $reply->message;
    $link = 'https://view.moezx.cc/images/2019/10/28/default_d_h_large.gif';
    $proxy = akina_option('cmt_image_proxy') . $link;
  }
  $output = array(
    'status' => $status,
    'success' => $success,
    'message' => $message,
    'link' => $link,
    'proxy' => $proxy,
  );
  return $output;
}

/*
 * 定制实时搜索 rest api
 * @rest api接口路径：https://sakura.2heng.xin/wp-json/sakura/v1/cache_search/json
 * @可在cache_search_json()函数末尾通过设置 HTTP header 控制 json 缓存时间
 */
function cache_search_json()
{
    $vowels = array("[", "{", "]", "}", "<", ">", "\r\n", "\r", "\n", "-", "'", '"', '`', " ", ":", ";", '\\', "  ", "toc");
    $regex = <<<EOS
/<\/?[a-zA-Z]+("[^"]*"|'[^']*'|[^'">])*>|begin[\S\s]*\/begin|hermit[\S\s]*\/hermit|img[\S\s]*\/img|{{.*?}}|:.*?:/m
EOS;

    $posts = new WP_Query('posts_per_page=-1&post_status=publish&post_type=post');
    while ($posts->have_posts()): $posts->the_post();
        $output .= '{"type":"post","link":"' . get_post_permalink() . '","title":' . json_encode(get_the_title()) . ',"comments":"' . get_comments_number('0', '1', '%') . '","text":' . json_encode(str_replace($vowels, " ", preg_replace($regex, ' ', get_the_content()))) . '},';
    endwhile;
    wp_reset_postdata();

    $pages = get_pages();
    foreach ($pages as $page) {
        $output .= '{"type":"page","link":"' . get_page_link($page) . '","title":' . json_encode($page->post_title) . ',"comments":"' . $page->comment_count . '","text":' . json_encode(str_replace($vowels, " ", preg_replace($regex, ' ', $page->post_content))) . '},';
    }

    $tags = get_tags();
    foreach ($tags as $tag) {
        $output .= '{"type":"tag","link":"' . get_term_link($tag) . '","title":' . json_encode($tag->name) . ',"comments":"","text":""},';
    }

    $categories = get_categories();
    foreach ($categories as $category) {
        $output .= '{"type":"category","link":"' . get_term_link($category) . '","title":' . json_encode($category->name) . ',"comments":"","text":""},';
    }
    if (akina_option('live_search_comment')) {
        $comments = get_comments();
        foreach ($comments as $comment) {
            $is_private = get_comment_meta($comment->comment_ID, '_private', true);
            if ($is_private) {
                $output .= '{"type":"comment","link":"' . get_comment_link($comment) . '","title":' . json_encode(get_the_title($comment->comment_post_ID)) . ',"comments":"","text":' . json_encode($comment->comment_author . "：" . __("The comment is private", "sakura") /*该评论为私密评论*/) . '},';
                continue;
            } else {
                $output .= '{"type":"comment","link":"' . get_comment_link($comment) . '","title":' . json_encode(get_the_title($comment->comment_post_ID)) . ',"comments":"","text":' . json_encode(str_replace($vowels, " ", preg_replace($regex, " ", $comment->comment_author . "：" . $comment->comment_content))) . '},';
            }
        }
    }

    $output = substr($output, 0, strlen($output) - 1);

    $data = '[' . $output . ']';
    $result = new WP_REST_Response(json_decode($data), 200);
    $result->set_headers(array('Content-Type' => 'application/json',
        'Cache-Control' => 'max-age=3600')); // json 缓存控制

    return $result;
}
