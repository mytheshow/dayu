<!--文章列表输出的模板，可以参考dayu首页那些文章列表，该模板分为header,div,footer三部分-->
<!--.well(Bootstrap Well)【http://www.runoob.com/bootstrap/bootstrap-wells.html】-->
<!--.clearfix清理浮动，辅助类【http://www.runoob.com/bootstrap/bootstrap-helper-classes.html】或李炎恢视频辅助类章节-->
<!--
引入bootstrap框架后，用下面代码测试看看效果
<div class="container-fluid">
	<div class="pull-left" style="height:100px;width:50px;background:red;">red</div>
	<div class="clearfix"></div>
	<div class="clearfix" style="height:100px;width:50px;background:green;">green</div>
	<div style="height:100px;width:50px;background:blue;">blue</div>
</div>
-->
<article class="well clearfix">
	<!--每篇文章的标题头,.entry-header没有该css,js样式，可能是作者预留-->
	<header class="entry-header">
		<!--文章头上面的大缩略图(特色图片)，个人感觉巨捞，考虑去掉-->
		<?php if ( has_post_thumbnail() ) {?>
			<a href="<?php the_permalink() ?>" class="entry-cover">
				<!--thumbnail, medium, large, full-->
			<?php echo get_the_post_thumbnail($post_id, 'full');?>
			</a>
		<?php }?>
		<h1 class="entry-title">
			<a href="<?php the_permalink() ?>" title="<?php the_title();?>">
				<?php the_title(); ?>
				<!--检测文章是否是顶置文章并且是博客首页，出现顶置标记-->
				<?php if( is_sticky() && is_home()) echo '<span class="label label-primary entry-tag">置顶</span>';?>
				<!--该博客是按照月份进行归档的，不过下面两条语句还是感觉别扭-->
				<!--如果该文章在"归档文章中"评论数大于归档热度，出现HOT标记-->
				<?php $id=$post->ID; $comments = get_post($id)->comment_count;$hots = of_get_option("archives_hot") ? of_get_option("archives_hot") : 30;if($comments>=$hots) echo '<span class="label label-danger entry-tag">HOT</span>';?>
				<!--如果该文章在"归档文章"中3天内创建的，出现NEW标记-->
				<?php $time = get_post_time(); $days = of_get_option("archives_new") ? of_get_option("archives_new") : 3; if(time()-$time < 24*3600*$days) echo '<span class="label label-new entry-tag">New</span>';?>
			</a>
		</h1>
		<div class="clearfix entry-meta">
			<!--bootstrap样式类，左浮动-->
			<span class="pull-left">
				<!--.entry-date没设置任何样式，可能是预留-->
				<time class="entry-date fa fa-calendar" datetime="<?php the_time("Y/m/d H:i:s");?>">
					&nbsp;<?php past_date() ?>
				</time>
				<span class="dot">|</span>
				<!--.categories-links没设置任何样式，可能是预留-->
				<span class="categories-links fa fa-folder-o"> <?php the_category(','); ?></span>
				<span class="dot">|</span>
				<span class="fa fa-user"> <?php the_author_posts_link(); ?></span>
			</span>
			<span class="visible-lg visible-md visible-sm pull-left">
				<span class="dot">|</span>
				<!--该文章的评论数量，当是0条评论时显示的文本"暂无评论"，当是1条评论时显示文本"1 条评论"，当是多条评论时显示"% 条评论","%"是占位符-->
				<!--https://www.dayuzy.com/wordpress%E4%B8%ADcomments_number%E3%80%81the_permalink%E7%9A%84%E7%94%A8%E6%B3%95/【wordpress中comments_number、the_permalink的用法】-->
				<span class="fa fa-comments-o comments-link"> <a href="<?php the_permalink() ?>#comments"><?php comments_number('暂无评论', '1 条评论', '% 条评论'); ?></a></span>
				<span class="dot">|</span>
				<!--查看数量，dayu是作者的笔名，我如果创建函数可以是dayu-->
				<span class="fa fa-eye"> <?php echo dayu_get_post_views(get_the_ID());?> views</span>
			</span>
		</div>

	</header>
	<div class="entry-summary entry-content clearfix">
		<?php
		//文章开启了缩略图功能，但是该文章没有设置与之匹配的缩略图，并且设置了摘要信息，就显示一个自定义的
		//has_post_thumbnail(),有缩略图返回true，没有返回false
			if (of_get_option('show_thumb') && !has_post_thumbnail() && of_get_option('enable_excerpt')) {
				echo '<a href="'.get_permalink().'">' . _dayu_post_thumbnail(220, 120) . '</a>';
			}
			//是否有摘要信息
			if(of_get_option('enable_excerpt')){
				the_excerpt();
			}else{
				//第一个参数默认是"..more"，在这里设置为空了,因为下面的footer标签设置了"阅读全文"
				//https://www.dayuzy.com/wordpress%E4%B8%ADthe_content%E3%80%81get_the_content%E7%9A%84%E7%94%A8%E6%B3%95/【wordpress中the_content、get_the_content的用法】
				the_content(''); 
			}
		?>
	</div>
	<footer class="entry-footer clearfix visible-lg visible-md visible-sm">
		<div class="pull-left footer-tag">
			<!--
			the_tags的第一个参数是输出一些文本，比如"tags"，或者"文章所属标签"
			第二个参数多个标签以什么分割，可以是逗号,空格等等
			第三个参数也是输出文本，只不过是在所有标签输出后才输出的文本可以是<br/>,空格等等
			-->
			<?php if ( get_the_tags() ) { the_tags('', ' ', ''); } else{ echo '<p class="label label-dayu">本文暂无标签</p>';  } ?>
		</div>
		<a class="pull-right more-link" href="<?php the_permalink() ?>" title="阅读全文">阅读全文&raquo;</a>
	</footer>
</article>
