<?php
class cacheFile{
    private $_dir;
    const EXT = '.json';
    public function __construct() {
        $this->_dir = get_wp_root_path().'/themes/Sakura/cache/';
    }
    public function cacheData($key, $value = '', $path = '') {
        $filePath = $this->_dir.$path.$key.self::EXT;
        if ($value !== '') {
            if (is_null($value)) {
                return unlink($filePath);
            }
            $dir = dirname($filePath);
            if (!is_dir($dir)) {
                mkdir($dir, 0777);
            }
            return file_put_contents($filePath,$value);
        }
        if (!is_file($filePath)) {
            return false;
        } else {
            return json_decode(file_get_contents($filePath), true);
        }
    }
}

$vowels = array("[", "{","]","}","<",">","\r\n", "\r", "\n","-","'",'"','`'," ",":",";",'\\',"  ","toc");
$regex = <<<EOS
/<\/?[a-zA-Z]+("[^"]*"|'[^']*'|[^'">])*>|begin[\S\s]*\/begin|hermit[\S\s]*\/hermit|img[\S\s]*\/img|{{.*?}}|:.*?:/m
EOS;

$posts = new WP_Query('posts_per_page=-1&post_status=publish&post_type=post');
while ($posts->have_posts()) : $posts->the_post();
    $output .= '{"type":"post","link":"'.get_post_permalink().'","title":'.json_encode(get_the_title()).',"comments":"'.get_comments_number('0', '1', '%').'","text":'.json_encode(str_replace($vowels, " ",preg_replace($regex,' ',get_the_content()))).'},';
endwhile;
wp_reset_postdata();

$pages = get_pages();
foreach ($pages as $page) {
    $output .= '{"type":"page","link":"'.get_page_link($page).'","title":'.json_encode($page->post_title).',"comments":"'.$page->comment_count.'","text":'.json_encode(str_replace($vowels, " ",preg_replace($regex,' ',$page->post_content))).'},';
}

$tags = get_tags();
foreach ($tags as $tag) {
    $output .= '{"type":"tag","link":"'.get_term_link($tag).'","title":'.json_encode($tag->name).',"comments":"","text":""},';
}

$categories = get_categories();
foreach ($categories as $category) {
    $output .= '{"type":"category","link":"'.get_term_link($category).'","title":'.json_encode($category->name).',"comments":"","text":""},';
}
if(akina_option('live_search_comment')){
    $comments = get_comments();
    foreach ($comments as $comment) {
        $is_private = get_comment_meta($comment->comment_ID, '_private', true);
        if($is_private){
            $output .= '{"type":"comment","link":"'.get_comment_link($comment).'","title":'.json_encode(get_the_title($comment->comment_post_ID)).',"comments":"","text":'.json_encode($comment->comment_author."：该评论为私密评论").'},';
            continue;
        }else{
            $output .= '{"type":"comment","link":"'.get_comment_link($comment).'","title":'.json_encode(get_the_title($comment->comment_post_ID)).',"comments":"","text":'.json_encode(str_replace($vowels, " ",preg_replace($regex," ",$comment->comment_author."：".$comment->comment_content))).'},';
        }
    }
}

$output = substr($output,0,strlen($output)-1);

$data = '['.$output.']';
$TheFile = get_wp_root_path().'/themes/Sakura/cache/search.json';
$cacheFile = new cacheFile();
$cacheFile->cacheData('search', $data);