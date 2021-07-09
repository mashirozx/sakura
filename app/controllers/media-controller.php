<?php

namespace Sakura\Controllers;

use Sakura\Controllers\BaseController;
use Sakura\Lib\Exception;

class MediaController extends BaseController
{
  /**
   * Markup for wp_upload_dir, drop sensitive fs info
   *
   * @return string ie. "http://wp.moezx.cc/wp-content/uploads"
   */
  public static function get_upload_baseurl()
  {
    $dir = wp_upload_dir();
    return $dir['baseurl'];
  }

  /**
   * Get attachment metadata by attachment_id, also returns the resources' url
   *
   * @param integer $attachment_id
   * @return mixed (array | false) return false if attachment_id is invalid (in rest API post endpoint, WP will return attachment_id as 0, which means there is no attachment)
   */
  public static function get_attachment_metadata(int $attachment_id)
  {
    $metadata = wp_get_attachment_metadata($attachment_id);
    if (!$metadata) {
      // throw new Exception("Invalid file name. \$attachment_id=$attachment_id");
      return false;
    }
    $file = $metadata['file'];
    $file_name = basename($file);
    $subdir = str_replace($file_name, '', $file);
    $url_prefix = Self::get_upload_baseurl() . '/' . $subdir;

    $metadata['url'] = $url_prefix . $file_name;

    foreach ($metadata['sizes'] as $size => $meta) {
      $metadata['sizes'][$size]['url'] = $url_prefix . $meta['file'];
    }
    return $metadata;
  }
}
