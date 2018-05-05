<?php
$layout = of_get_option('side_bar');
$layout = (empty($layout)) ? 'right_side' : $layout;

get_header(); ?>

<!--下面的代码不是注释，是用来判断IE版本的【https://www.dayuzy.com/if-lt-ie-8%E6%9D%A1%E4%BB%B6%E6%B3%A8%E9%87%8A%E5%88%A4%E6%96%AD%E6%B5%8F%E8%A7%88%E5%99%A8%E7%89%88%E6%9C%AC/】-->
<!--[if lt IE 8]>
<div id="ie-warning" class="alert alert-danger fade in visible-lg">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<i class="fa fa-warning"></i> 请注意，本博客不支持低于IE8的浏览器，为了获得最佳效果，请下载最新的浏览器，推荐下载 <a href="http://www.google.cn/intl/zh-CN/chrome/" target="_blank"><i class="fa fa-compass"></i> Chrome</a>
</div>
<![endif]-->

<!--左侧边栏-->
<?php if($layout == 'left_side'){ ?>
<aside class="col-md-4 hidden-xs hidden-sm">
	<div id="sidebar">
	    <!--调用自定义的侧边栏sidebar_home-->
		<?php dynamic_sidebar( 'sidebar_home'); ?>
	</div>
</aside>
<?php } ?>

<section id='main' class='<?php echo ($layout == 'single') ? 'col-md-12' : 'col-md-8'; ?>' >
<!--首页幻灯片-->
<?php
	if(is_home()){
		dayu_slide();
	}elseif(is_category()){
?>
		<header class="archive-header well">
			<h1 class="archive-title">
				分类目录：<?php echo single_cat_title( '', false );?>
			</h1>
			<div class="archive-meta">
			<?php if ( category_description() ) : // Show an optional category description ?>
				<?php echo category_description(); ?>
			<?php else: //有描述就显示描述的内容，没有就输出"以下是分类某某的下的。。。"?>
				以下是分类 <?php echo single_cat_title( '', false );?> 下的所有文章
			<?php endif;?>
			</div>
		</header>
<?php
	}elseif(is_author()){
?>
		<header class="author-header well clearfix">
			<div class="pull-left author-avatar">
				<!--创建一个"twentytwelve_author_bio_avatar_size"过滤器-->
				<!--apply_filters的第二个参数是要过滤掉的字符串或数值，如果没有就返回该字符串-->
				<!--get_avatar第一个参数是id或email，第二个参数指定头像大小，在这可不必用过滤器-->
				<!--例如： get_avatar( get_the_author_meta( 'user_email' ), 50)-->
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentytwelve_author_bio_avatar_size', 50 ) ); ?>
			</div>
			<div class="author-meta">
				<h1 class="author-title">
					作者：<?php echo get_the_author();?>
				</h1>
				<?php if ( get_the_author_meta( 'description' ) ) : ?>
					<div class="archive-meta"><?php the_author_meta( 'description' ); ?></div>
				<?php endif; ?>
			</div>
		</header>
<?php
	}elseif(is_tag()){
?>
	<header class="archive-header well">
				<h1 class="archive-title">
					标签目录：<?php echo single_cat_title( '', false );?>
				</h1>
				<?php if ( category_description() ) : // Show an optional category description ?>
					<div class="archive-meta"><?php echo category_description(); ?></div>
				<?php else: ?>
					以下是与标签 “<?php echo single_cat_title( '', false );?>” 相关联的文章
				<?php endif;?>
			</header>
	<?php
		}elseif(is_search()){
	?>
			<header class="archive-header well">
				<h1 class="archive-title">
					搜索结果：<?php the_search_query(); ?>
				</h1>
				<div class="navbar navbar-default">
					<form class="navbar-form" role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
						<div class="input-group">
							<input type="text" class="form-control" value="<?php the_search_query(); ?>" name="s" id="s" >
							<span class="input-group-btn">
							<button type="submit" class="btn btn-danger" id="searchsubmit"> 搜 索 </button>
							</span>
						</div>
					</form>
				</div>
			</header>
	<?php
		}
	?>
			<!--首页文章列表模块-->
		<?php
			if ( have_posts() ) {
				while ( have_posts() ){
					the_post();
					//根据当前文章的类型去获取使用的模板 https://www.dayuzy.com/wordpress%E4%B8%ADget_template_part%E3%80%81get_post_format%E7%9A%84%E7%94%A8%E6%B3%95/【wordpress中get_template_part、get_post_format的用法】
					get_template_part( 'inc/post-format/content', get_post_format() );
					//下面这几行的作用不知道是什么，可能与广告有关，注意经过框架源码分析of_get_option获取的是dayu
					//字段下面的键值对，不是数据库中ads_show_pos的值，每个主题有各自自定义的键值对，不要打开数据库
					//发现没有ads_show_pos的记录就说没有ads_show_pos的值，应该看dayu这条记录，提示var_dump('dayu记录')
					//详细说明请看of_get_option的用法
					$ads_show_pos = of_get_option('ads_show_pos', false);
					$ads = of_get_option('ads_index_list', false);
					$ads_pos = of_get_option('ads_index_list_pos',1);
					if(isset($ads_show_pos['index']) && $ads){
						if ($wp_query->current_post == $ads_pos ){
							echo '<div class="ads_index_list">' . $ads . '</div>';
						}
					}
				}
			}else{
		?>
		<article class="alert alert-warning"><?php _e('非常抱歉，没有相关文章。'); ?></article>
		<?php } ?>
		<!--首页文章列表模块-->
		<!--分页，dayu是作者的笔名，我如果创建函数可以是dayu-->
		<?php dayu_pages(3);?>
	</section>
	<!--侧边栏，与上面的左侧边栏道理一样-->
	<?php if($layout == 'right_side'){ ?>
	<aside class="col-md-4 hidden-xs hidden-sm">
		<div id="sidebar">
			<?php dynamic_sidebar( 'sidebar_home'); ?>
		</div>
	</aside>
	<?php } ?>
<?php get_footer(); ?>