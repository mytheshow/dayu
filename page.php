<?php
/*
Template Name: 默认模版
*/
$layout = of_get_option('side_bar');
$layout = (empty($layout)) ? 'right_side' : $layout;
get_header(); ?>
	<?php if($layout == 'left_side'){ ?>
		<aside class="col-md-4 hidden-xs hidden-sm">
			<div id="sidebar">
				<?php dynamic_sidebar( 'sidebar_single'); ?>
			</div>
		</aside>
	<?php } ?>
	<section id='main' class='<?php echo ($layout == 'single') ? 'col-md-12' : 'col-md-8'; ?>' >
	<!-- https://www.dayuzy.com/wordpress%E4%B8%ADhave_posts%E3%80%81the_post%E7%9A%84%E7%94%A8%E6%B3%95/【wordpress中have_posts、the_post的用法】-->
		<?php while ( have_posts() ) : the_post(); ?>
			<article class="well clearfix page" id="post">
				<!--.entry-header没有这个样式-->
				<header class="entry-header">
					<h1 class="entry-title">
						<?php the_title(); ?>
					</h1>
				</header>
				<div class="page-content">
					<?php the_content(); ?>
				</div>
				<footer class="entry-footer">
					<!--评论模块-->
					<?php comments_template(); ?>
				</footer>
			</article>

		<?php endwhile; // end of the loop. ?>

	</section>
	 <!--侧边栏-->
	<?php if($layout == 'right_side'){ ?>
		<aside class="col-md-4 hidden-xs hidden-sm">
			<div id="sidebar">
				<?php dynamic_sidebar( 'sidebar_single'); ?>
			</div>
		</aside>
	<?php } ?>
<!--底部-->
<?php get_footer(); ?>