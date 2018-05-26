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
mashiro_option.cover_api = "<?php echo get_site_url() ?>/wp-content/themes/Sakura/cover/";
mashiro_option.qq_api_url = "https://api.mashiro.top/qqinfo/"; 
mashiro_option.qq_avatar_api_url = "https://api.mashiro.top/qqinfo/";

<?php if(akina_option('jsdelivr_cdn_test')){ ?>
mashiro_option.jsdelivr_css_src = "https://pages.shino.cc/cdn/css/lib.css";
<?php } else { ?>
mashiro_option.jsdelivr_css_src = "https://cdn.jsdelivr.net/gh/moezx/cdn@<?php echo akina_option('jsdelivr_cdn_version', 'latest'); ?>/css/lib.min.css";
<?php } ?>

/*End of Initial Variables*/
</script>
<?php }
add_action('wp_head', 'font_end_js_control');