<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Akina
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if(akina_option('patternimg') || !get_post_thumbnail_id(get_the_ID())) { ?>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<p class="entry-census"><?php echo poi_time_since(strtotime($post->post_date)); ?>&nbsp;&nbsp;<?php echo wp_statistics_pages(total,uri,get_the_ID()); ?> 次阅读</p>
		<hr>
	</header><!-- .entry-header -->
	<?php } ?>
	<!--<div class="toc-entry-content"><!-- 套嵌目录使用（主要为了支援评论）-->
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'ondemand' ),
				'after'  => '</div>',
			) );
		?>
		<p class="head-copyright">「樱花庄的白猫」原创文章：<a href="<?php the_permalink(); ?>">《<?php the_title(); ?>》</a>，转载请保留出处！<?php the_permalink(); ?></p>
		<p>Q.E.D. <i class="fa fa-meetup" aria-hidden="true" style="color:#d34836"></i></p>
		<!--<div class="oshimai"></div>-->
		<h2 style="opacity:0;max-height:0;margin:0">Comments</h2><!-- 评论跳转标记 -->
	</div><!-- .entry-content -->
	<?php the_reward(); ?>
	<footer class="post-footer">
	<div class="post-lincenses"><a href="https://creativecommons.org/licenses/by-nc-sa/4.0/deed.zh"  target="_blank" rel="nofollow"><i class="fa fa-creative-commons" aria-hidden="true"></i> 知识共享署名-非商业性使用-相同方式共享 4.0 国际许可协议</a></div>
	<div class="post-tags">
		<?php if ( has_tag() ) { echo '<i class="iconfont icon-tags"></i> '; the_tags('', ' ', ' ');}?>
	</div>
    <?php get_template_part('layouts/sharelike'); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
