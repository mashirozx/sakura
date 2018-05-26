<?php 

	/**
	 * sharelike
	 */

?>

<?php if ( akina_option('post_like') == 'yes') { ?>
<div class="post-like">
<a href="javascript:;" data-action="ding" data-id="<?php the_ID(); ?>" class="specsZan <?php if(isset($_COOKIE['specs_zan_'.get_the_ID()])) echo 'done';?>">
	<i class="iconfont icon-like"></i> <span class="count">
		<?php if( get_post_meta(get_the_ID(),'specs_zan',true) ){
			echo get_post_meta(get_the_ID(),'specs_zan',true);
		} else {
			echo '0';
		}?></span>
	</a>
</div>
<?php } ?>
<?php if ( akina_option('post_share') == 'yes') { ?>		
<div class="post-share">
<ul class="sharehidden">
	<li><a href="http://www.jiathis.com/send/?webid=weixin&url=<?php the_permalink(); ?>&title=<?php the_title(''); ?>" onclick="window.open(this.href, 'renren-share', 'width=490,height=700');return false;" class="s-weixin"><img src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.3.1/img/Sakura/images/sns/wechat.png"/></a></li>
	<li><a href="http://www.jiathis.com/send/?webid=qzone&url=<?php the_permalink(); ?>&title=<?php the_title(''); ?>" onclick="window.open(this.href, 'weibo-share', 'width=730,height=500');return false;" class="s-qq"><img src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.3.1/img/Sakura/images/sns/qzone.png"/></a></li>
	<li><a href="http://www.jiathis.com/send/?webid=tsina&url=<?php the_permalink(); ?>&title=<?php the_title(''); ?>" onclick="window.open(this.href, 'weibo-share', 'width=550,height=235');return false;" class="s-sina"><img src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.3.1/img/Sakura/images/sns/sina.png"/></a></li>
	<li><a href="http://www.jiathis.com/send/?webid=douban&url=<?php the_permalink(); ?>&title=<?php the_title(''); ?>" onclick="window.open(this.href, 'renren-share', 'width=490,height=600');return false;" class="s-douban"><img src="https://cdn.jsdelivr.net/gh/moezx/cdn@3.3.1/img/Sakura/images/sns/douban.png"/></a></li>
	</ul>
	<i class="iconfont show-share icon-forward"></i>
</div>
<?php } ?>