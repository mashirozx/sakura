<?php
function font_end_js_control() { ?>
<script>
/*Initial Variables*/
var mashiro_option = new Object();
var mashiro_global = new Object();
mashiro_option.NProgressON = <?php if ( akina_option('nprogress_on') ){ echo 'true'; } else { echo 'false'; } ?>;
mashiro_option.email_domain = "<?php echo akina_option('email_domain', ''); ?>";
mashiro_option.email_name = "<?php echo akina_option('email_name', ''); ?>";
mashiro_option.cookie_version_control = "<?php echo akina_option('cookie_version', ''); ?>";
mashiro_option.qzone_autocomplete = false;
mashiro_option.site_name = "<?php echo akina_option('site_name', ''); ?>";
mashiro_option.author_name = "<?php echo akina_option('author_name', ''); ?>";
mashiro_option.template_url = "<?php echo get_template_directory_uri(); ?>";
mashiro_option.site_url = "<?php echo site_url(); ?>";
mashiro_option.qq_api_url = "<?php echo rest_url('sakura/v1/qqinfo/json'); ?>"; 
// mashiro_option.qq_avatar_api_url = "https://api.2heng.xin/qqinfo/";
mashiro_option.live_search = <?php if ( akina_option('live_search') ){ echo 'true'; } else { echo 'false'; } ?>;

<?php if( akina_option('sakura_skin_bg' )){ $bg_arry=explode(",", akina_option('sakura_skin_bg' ));?>
mashiro_option.skin_bg0 = "<?php echo $bg_arry[0] ?>";
mashiro_option.skin_bg1 = "<?php echo $bg_arry[1] ?>";
mashiro_option.skin_bg2 = "<?php echo $bg_arry[2] ?>";
mashiro_option.skin_bg3 = "<?php echo $bg_arry[3] ?>";
mashiro_option.skin_bg4 = "<?php echo $bg_arry[4] ?>";
mashiro_option.skin_bg5 = "<?php echo $bg_arry[5] ?>";
mashiro_option.skin_bg6 = "<?php echo $bg_arry[6] ?>";
mashiro_option.skin_bg7 = "<?php echo $bg_arry[7] ?>";
<?php }else {?>
mashiro_option.skin_bg0 = "none";
mashiro_option.skin_bg1 = <?php echo (SAKURA_STATIC_ASSETS_HOST);?>"gh/mashirozx/sakura@static/cdn/img/sakura.png";
mashiro_option.skin_bg2 = <?php echo (SAKURA_STATIC_ASSETS_HOST);?>"gh/mashirozx/sakura@static/cdn/img/plaid2dbf8.jpg";
mashiro_option.skin_bg3 = <?php echo (SAKURA_STATIC_ASSETS_HOST);?>"gh/mashirozx/sakura@static/cdn/img/star02.png";
mashiro_option.skin_bg4 = <?php echo (SAKURA_STATIC_ASSETS_HOST);?>"gh/mashirozx/sakura@static/cdn/img/kyotoanimation.png";
mashiro_option.skin_bg5 = <?php echo (SAKURA_STATIC_ASSETS_HOST);?>"gh/mashirozx/sakura@static/cdn/img/dot_orange.gif";
mashiro_option.skin_bg6 = "https://api.mashiro.top/bing/";
mashiro_option.skin_bg7 = <?php echo (SAKURA_STATIC_ASSETS_HOST);?>"gh/mashirozx/sakura@static/other-sites/api-index/images/me.png";
<?php } ?>

mashiro_option.darkmode = <?php if ( akina_option('darkmode') ){ echo 'true'; } else { echo 'false'; } ?>;

<?php if( is_home() ){ ?>
mashiro_option.land_at_home = true;
<?php }else {?>
mashiro_option.land_at_home = false;
<?php } ?>

<?php if(akina_option('image_viewer') == 0){ ?>
mashiro_option.baguetteBoxON = false;
<?php }else {?>
mashiro_option.baguetteBoxON = true;
<?php } ?>

<?php if(akina_option('clipboard_copyright') == 0){ ?>
mashiro_option.clipboardCopyright = false;
<?php }else {?>
mashiro_option.clipboardCopyright = true;
<?php } ?>

<?php if(akina_option('entry_content_theme') == "sakura"){ ?>
mashiro_option.entry_content_theme_src = "<?php echo get_template_directory_uri() ?>/cdn/theme/sakura.css?<?php echo SAKURA_VERSION.akina_option('cookie_version', ''); ?>";
<?php }elseif(akina_option('entry_content_theme') == "github") {?>
mashiro_option.entry_content_theme_src = "<?php echo get_template_directory_uri() ?>/cdn/theme/github.css?<?php echo SAKURA_VERSION.akina_option('cookie_version', ''); ?>";
<?php } ?>
mashiro_option.entry_content_theme = "<?php echo akina_option('entry_content_theme'); ?>";

<?php if(akina_option('jsdelivr_cdn_test')){ ?>
mashiro_option.jsdelivr_css_src = "<?php echo get_template_directory_uri() ?>/cdn/css/lib.css?<?php echo SAKURA_VERSION.akina_option('cookie_version', ''); ?>";
<?php } else { ?>
mashiro_option.jsdelivr_css_src = <?php echo (SAKURA_STATIC_ASSETS_HOST);?>"gh/mashirozx/Sakura@<?php echo SAKURA_VERSION; ?>/cdn/css/lib.min.css";
<?php } ?>
<?php if (akina_option('aplayer_server') != 'off'): ?>
mashiro_option.float_player_on = true;
mashiro_option.meting_api_url = "<?php echo rest_url('sakura/v1/meting/aplayer'); ?>";
<?php endif; ?>

mashiro_option.cover_api = "<?php echo rest_url('sakura/v1/image/cover'); ?>";

mashiro_option.windowheight = /Mobile|Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent) ? 'fixed' : 'auto';
/*End of Initial Variables*/
</script>
<?php }
add_action('wp_head', 'font_end_js_control');
