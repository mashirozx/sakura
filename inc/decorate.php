<?php
function customizer_css() { ?>
<style type="text/css">
<?php // Style Settings
if ( akina_option('shownav') ) { ?>
.site-top .lower nav {display: block !important;}
<?php } // Style Settings ?>
<?php // theme-skin
if ( akina_option('theme_skin') ) { ?>
.author-profile i , .post-like a , .post-share .show-share , .sub-text , .we-info a , span.sitename , .post-more i:hover , #pagination a:hover , .post-content a:hover , .float-content i:hover{ color: <?php echo akina_option('theme_skin'); ?> }
.feature i , /*.feature-title span ,*/ .download , .navigator i:hover , .links ul li:before , .ar-time i , span.ar-circle , .object , .comment .comment-reply-link , .siren-checkbox-radio:checked + .siren-checkbox-radioInput:after { background: <?php echo akina_option('theme_skin'); ?> }
::-webkit-scrollbar-thumb { background: <?php echo akina_option('theme_skin'); ?> }
.download , .navigator i:hover , .link-title , .links ul li:hover , #pagination a:hover , .comment-respond input[type='submit']:hover { border-color: <?php echo akina_option('theme_skin'); ?> }
.entry-content a:hover , .site-info a:hover , .comment h4 a , #comments-navi a.prev , #comments-navi a.next , .comment h4 a:hover , .site-top ul li a:hover , .entry-title a:hover , #archives-temp h3 , span.page-numbers.current , .sorry li a:hover , .site-title a:hover , i.iconfont.js-toggle-search.iconsearch:hover , .comment-respond input[type='submit']:hover, blockquote:before, blockquote:after { color: <?php echo akina_option('theme_skin'); ?> }

#aplayer-float .aplayer-lrc-current { color: <?php echo akina_option('theme_skin'); ?> !important}

.is-active-link::before, .commentbody:not(:placeholder-shown)~.input-label, .commentbody:focus~.input-label {
    background-color: <?php echo akina_option('theme_skin'); ?> !important
}

.commentbody:focus {
    border-color: <?php echo akina_option('theme_skin'); ?> !important
}

.insert-image-tips:hover, .insert-image-tips-hover{ 
    color: <?php echo akina_option('theme_skin'); ?>;
    border: 1px solid <?php echo akina_option('theme_skin'); ?>
}

.site-top ul li a:after {
    background-color: <?php echo akina_option('theme_skin'); ?>
}

.scrollbar,.butterBar-message {
    background: <?php echo akina_option('theme_skin'); ?> !important
}

#nprogress .spinner-icon{ 
    border-top-color: <?php echo akina_option('theme_skin'); ?>; 
    border-left-color: <?php echo akina_option('theme_skin'); ?>
}

#nprogress .bar {
    background: <?php echo akina_option('theme_skin'); ?>
}

.changeSkin-gear,.toc{
    background:rgba(255,255,255,<?php echo akina_option('sakura_skin_alpha','') ?>);
}

<?php if(akina_option('entry_content_theme') == "sakura"){ ?>
.entry-content th {
    background-color: <?php echo akina_option('theme_skin'); ?>
}
<?php } ?>
<?php if(akina_option('live_search')){ ?>
.search-form--modal .search-form__inner {
    bottom: unset !important;
    top: 10% !important;
}
<?php } ?>

<?php if(akina_option('feature_align') == 'left'){ ?>
.post-list-thumb .post-content-wrap {
    float: left;
    padding-left: 30px;
    padding-right: 0;
    text-align: right;
    margin: 20px 10px 10px 0
}
.post-list-thumb .post-thumb {
    float: left
}

.post-list-thumb .post-thumb a {
    border-radius: 10px 0 0 10px
}
<?php }if(akina_option('feature_align') == 'alternate'){ ?>
.post-list-thumb:nth-child(2n) .post-content-wrap {
    float: left;
    padding-left: 30px;
    padding-right: 0;
    text-align: right;
    margin: 20px 10px 10px 0
}
.post-list-thumb:nth-child(2n) .post-thumb {
    float: left
}

.post-list-thumb:nth-child(2n) .post-thumb a {
    border-radius: 10px 0 0 10px
}
<?php } ?>


.post-list-thumb{opacity: 0}
.post-list-show {opacity: 1}

<?php } // theme-skin ?>
<?php // Custom style
if ( akina_option('site_custom_style') ) {
  echo akina_option('site_custom_style');
} 
// Custom style end ?>
<?php // liststyle
if ( akina_option('list_type') == 'square') { ?>
.feature img{ border-radius: 0px; !important; }
.feature i { border-radius: 0px; !important; }
<?php } // liststyle ?>
<?php // comments
if ( akina_option('toggle-menu') == 'no') { ?>
.comments .comments-main {display:block !important;}
.comments .comments-hidden {display:none !important;}
<?php } // comments ?>
<?php 
$image_api = 'background-image: url("'.rest_url('sakura/v1/image/cover').'");';
$bg_style = akina_option('focus_height') ? 'background-position: center center;background-attachment: inherit;' : '';
?>
.centerbg{<?php echo $image_api.$bg_style ?>background-position: center center;background-attachment: inherit;}
.rotating {
    -webkit-animation: rotating 6s linear infinite;
    -moz-animation: rotating 6s linear infinite;
    -ms-animation: rotating 6s linear infinite;
    -o-animation: rotating 6s linear infinite;
    animation: rotating 6s linear infinite;
}
<?php if(akina_option('comment_info_box_width', '')): ?>
.cmt-popup {
    --widthA: <?php echo akina_option('comment_info_box_width', ''); ?>%;
    --widthB: calc(var(--widthA) - 71px);
    --widthC: calc(var(--widthB) / 3);
    width: var(--widthC);
}
<?php endif;?>
body.dark #main-container,body.dark .pattern-center:after,body.dark #mo-nav,body.dark .headertop-bar::after,body.dark .site-content,body.dark .comments,body.dark .site-footer{background:#31363b !important;}body.dark .pattern-center-blank,body.dark .yya,body.dark .blank,body.dark .changeSkin-gear,body.dark .toc,body.dark .search-form input{background:rgba(49,54,59,0.85);}body.dark .single-reward .reward-row{background:#bebebe;}body.dark .font-family-controls button{background-color:#828080;}body.dark .single-reward .reward-row:before{border-bottom:13px solid #bebebe;}body.dark .search-form--modal,body.dark .ins-section .ins-section-header{border-bottom:none;background:rgba(49,54,59,0.95);}body.dark .ins-section .ins-search-item:hover,body.dark .ins-section .ins-search-item.active,body.dark .ins-section .ins-search-item:hover .ins-slug,body.dark .ins-section .ins-search-item.active .ins-slug,body.dark .ins-section .ins-search-item:hover .ins-search-preview,body.dark .ins-section .ins-search-item.active .ins-search-preview,body.dark .ins-section .ins-search-item:hover header,body.dark .ins-section .ins-search-item:hover .iconfont{color:#fff;background:#31363b;}body.dark .search_close:after,body.dark .search_close:before{background-color:#eee;}body.dark .search_close:hover:after,body.dark .search_close:hover:before{background-color:#3daee9;}body.dark input.m-search-input{background:#bebebe;}body.dark .openNav .icon,body.dark .openNav .icon:after,body.dark .openNav .icon:before{background-color:#eee;}body.dark .site-header:hover{background:#31363b;}body.dark .post-date,body.dark .post-list-thumb a,body.dark .menhera-container .emoji-item:hover{color:#424952;}body.dark .entry-content p,body.dark .entry-content ul,body.dark .entry-content ol,body.dark .comments .body p,body.dark .float-content,body.dark .post-list p,body.dark .link-title{color:#bebebe !important;}body.dark .entry-title a,body.dark .post-list-thumb .post-title,body.dark .post-list-thumb,body.dark .art-content #archives .al_mon_list .al_mon,body.dark .art-content #archives .al_mon_list span,body.dark .art .art-content #archives a,body.dark .menhera-container .emoji-item{color:#bebebe;}body.dark .logolink a,body.dark .post-content a:hover,body.dark .comment .body p a{color:rgba(61,174,233,0.8) !important;}body.dark .lower li ul,body.dark .header-user-avatar:hover .header-user-menu{background:#31363b;}body.dark .header-user-menu::before,body.dark .lower li ul::before{border-color:transparent transparent #31363b transparent;}body.dark .site-top ul li a,body.dark .header-user-menu a,body.dark #mo-nav ul li a,body.dark .site-title a,body.dark header.page-header,body.dark h1.cat-title{color:#eee;}body.dark .art .art-content #archives .al_year,body.dark .comment-respond input,body.dark .comment-respond textarea,body.dark .siren-checkbox-label{color:#eee;}body.dark input[type=color]:focus,body.dark input[type=date]:focus,body.dark input[type=datetime-local]:focus,body.dark input[type=datetime]:focus,body.dark input[type=email]:focus,body.dark input[type=month]:focus,body.dark input[type=number]:focus,body.dark input[type=password]:focus,body.dark input[type=range]:focus,body.dark input[type=search]:focus,body.dark input[type=tel]:focus,body.dark input[type=text]:focus,body.dark input[type=time]:focus,body.dark input[type=url]:focus,body.dark input[type=week]:focus,body.dark textarea:focus,body.dark #mo-nav .m-search form{color:#eee;background-color:#31363b;}body.dark .post-date,body.dark .post-list-thumb a,body.dark .post-meta{color:#888;}body.dark img,body.dark .cd-top,body.dark .highlight-wrap,body.dark iframe,body.dark .entry-content .aplayer{filter:brightness(0.8);}body.dark .post-list-thumb{box-shadow:0 1px 35px -8px rgba(0,0,0,0.8);}body.dark .centerbg{background-blend-mode:hard-light;background-color:#31363b;}body.dark .notice{color:#EFF0F1;background:#232629;border:none;}body.dark h1.fes-title,body.dark h1.main-title{border-bottom:1px dashed #ababab;}body.dark .scrollbar,body.dark .butterBar p.butterBar-message{background:#3daee9 !important;}body.dark .entry-content p code{color:#eee !important;background:rgba(61,174,233,0.5) !important;}body.dark .notification,body.dark #moblieGoTop,body.dark #moblieDarkLight{color:#eee;background-color:#232629;}body.dark #moblieGoTop:hover,body.dark #moblieDarkLight:hover{background-color:#232629;opacity:.8;}body.dark .widget-area{background-color:rgba(35,38,41,0.8);}body.dark .skin-menu,body.dark .menu-list li,body.dark .widget-area .heading,body.dark .widget-area .show-hide svg,body.dark #aplayer-float,body.dark .aplayer.aplayer-fixed .aplayer-body,body.dark .aplayer .aplayer-miniswitcher,body.dark .aplayer .aplayer-pic{color:#eee;background-color:#232629 !important;}body.dark .skin-menu::after{border-color:#232629 transparent transparent transparent;}body.dark .aplayer .aplayer-list ol li .aplayer-list-author{color:#eee;}body.dark #aplayer-float .aplayer-lrc-current{color:transparent !important;}body.dark .aplayer.aplayer-fixed .aplayer-lrc{text-shadow:-1px -1px 0 #989898;}body.dark .aplayer .aplayer-list ol li.aplayer-list-light{background:rgba(61,174,233,0.5) !important;}body.dark .aplayer .aplayer-list ol li:hover{background:rgba(61,174,233,0.8) !important;}body.dark .aplayer.aplayer-fixed .aplayer-list{border:none !important;}body.dark .aplayer.aplayer-fixed .aplayer-info,body.dark .aplayer .aplayer-list ol li{border-top:none !important;}body.dark .aplayer .aplayer-info .aplayer-controller .aplayer-time .aplayer-icon:hover path,body.dark .widget-area .show-hide svg path{fill:#fafafa;}body.dark,body.dark button,body.dark input,body.dark select,body.dark textarea{color:#eee;}body.dark button,body.dark input[type=button],body.dark input[type=reset],body.dark input[type=submit]{box-shadow:none;}body.dark .entry-content th{background-color:#3daee9;}body.dark .author-profile i,body.dark .post-like a,body.dark .post-share .show-share,body.dark .sub-text,body.dark .we-info a,body.dark span.sitename,body.dark .post-more i:hover,body.dark #pagination a:hover,body.dark .post-content a:hover,body.dark .float-content i:hover{color:#3daee9;}body.dark .feature i,body.dark .feature-title span,body.dark .download,body.dark .navigator i:hover,body.dark .links ul li:before,body.dark .ar-time i,body.dark span.ar-circle,body.dark .object,body.dark .comment .comment-reply-link,body.dark .siren-checkbox-radio:checked + .siren-checkbox-radioInput:after{background:#3daee9;}body.dark .download,body.dark .navigator i:hover,body.dark .link-title,body.dark .links ul li:hover,body.dark #pagination a:hover,body.dark .comment-respond input[type='submit']:hover{border-color:#3daee9;}body.dark .site-info a:hover,body.dark .comment h4 a,body.dark #comments-navi a.prev,body.dark #comments-navi a.next,body.dark .comment h4 a:hover,body.dark .site-top ul li a:hover,body.dark .entry-title a:hover,body.dark #archives-temp h3,body.dark span.page-numbers.current,body.dark .sorry li a:hover,body.dark .site-title a:hover,body.dark .js-toggle-search.iconsearch:hover,body.dark .comment-respond input[type='submit']:hover,body.dark blockquote:before,body.dark blockquote:after,body.dark .entry-content a{color:#3daee9 !important;}body.dark .entry-content a:hover{color:#3daee9;text-decoration:underline !important;}body.dark .is-active-link::before,body.dark .commentbody:not(:placeholder-shown) ~ .input-label,body.dark .commentbody:focus ~ .input-label{background-color:#3daee9 !important;}body.dark .commentbody:focus{border-color:#3daee9 !important;}body.dark .insert-image-tips:hover,body.dark .insert-image-tips-hover{color:#3daee9;border:1px solid #3daee9;}body.dark .site-top ul li a:after,body.dark .menu-list li:hover,body.dark .font-family-controls button.selected{background-color:#3daee9 !important;}body.dark #nprogress .spinner-icon{border-top-color:#3daee9;border-left-color:#3daee9;}body.dark #nprogress .bar{background:#3daee9;}body.dark .comment .info,body.dark .comment-respond .logged-in-as,body.dark .notification,body.dark .comment-respond .logged-in-as a,body.dark .comment-respond .logged-in-as a:hover{color:#9499a8;}body.dark .sm{color:#377292 !important;}html,#main-container,.pattern-center:after,#mo-nav,.headertop-bar::after,.site-content,.comments,.site-footer,.pattern-center-blank,.yya,.blank,.changeSkin-gear,.toc,.search-form input{transition:background 1s;}.entry-content p,.entry-content ul,.entry-content ol,.comments .body p,.float-content,.post-list p,.link-title{transition:color 1s;}body.dark::-webkit-scrollbar-thumb,body.dark::selection{background:#377292;color:#ccc;}
</style>
<?php }
add_action('wp_head', 'customizer_css');
