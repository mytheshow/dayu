<!DOCTYPE html>
	<html>
	<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <!--第三个参数发现没有作用，不管写不写分隔符都是在右边【https://www.dayuzy.com/wordpress%E4%B8%ADwp_title%E7%9A%84%E7%94%A8%E6%B3%95/】-->
    <title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?> </title>
    <meta charset="<?php bloginfo('charset'); ?>">
    <!--下面这句话太难理解，放弃了，直接复制过来用-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
	
	//网站图标,如果是https，在网站的根目录发一张favicon.ico，像素大小16*16即可
	//网站图标,如果是http功能可以使用下面方法
	
	$icon = of_get_option('icon');
	if(!empty($icon)){
		echo '<link rel = "Shortcut Icon" href="'.$icon.'"> ';
	} 

    //为了seo，出现的站点、分类等“关键字”
    function keywords(){
        if( is_home() || is_front_page() ){ echo of_get_option('site_keywords'); }
        elseif( is_category() ){ single_cat_title(); }
        elseif( is_single() ){
            echo trim(wp_title('',FALSE)).',';
            if ( has_tag() ) {foreach((get_the_tags()) as $tag ) { echo $tag->name.','; } }//循环所有标签
            foreach((get_the_category()) as $category) { echo $category->cat_name.','; } //循环所有分类目录
        }
        elseif( is_search() ){ the_search_query(); }
        else{ echo trim(wp_title('',FALSE)); }
    }
    //为了seo，出现的站点、分类等“关键字的描述”
    function description(){
        if( is_home() || is_front_page() ){ echo trim(of_get_option('site_description')); }
        elseif( is_category() ){ $description = strip_tags(category_description());echo trim($description);}
        elseif( is_single() ){ 
		if(get_the_excerpt()){
			echo get_the_excerpt();
		}else{
			global $post;
                        $description = trim( str_replace( array( "\r\n", "\r", "\n", "　", " "), " ", str_replace( "\"", "'", strip_tags( $post->post_content ) ) ) );
                        echo mb_substr( $description, 0, 220, 'utf-8' );
		}
	}	
        elseif( is_search() ){ echo '“';the_search_query();echo '”为您找到结果 ';global $wp_query;echo $wp_query->found_posts;echo ' 个'; }
        elseif( is_tag() ){  $description = strip_tags(tag_description());echo trim($description); }
        else{ $description = strip_tags(term_description());echo trim($description); }
    }
    ?>
    <meta name="description" content="<?php description();?>">
    <meta name="keywords" content="<?php keywords();?>">
	    <!--告诉IE浏览器，IE8/9及以后的版本都会以最高版本IE来渲染页面-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if(is_404()){ ?>
        <!--表示5秒刷新一下，跳转到bloginfo('url')-->
        <meta http-equiv='refresh' content=5;URL="<?php bloginfo('url'); ?>">
    <?php } ?>
	<!--这个函数会执行wp_enqueue_scripts钩子，而这个钩子又挂载了dayu_theme_scripts函数
	【https://www.dayuzy.com/wordpress%E4%B8%ADwp_head%E7%9A%84%E7%94%A8%E6%B3%95/】
	-->
	<?php wp_head(); ?>
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>
      <script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js"></script>
    <![endif]-->
	</head>
	<body>
	<!--百度主动提交-->
	<?php include_once("baidu_js_push.php") ?>
<?php
// 背景图片还是图案，图案分为自定义图案和自带的几个图案
switch (of_get_option('background_mode')) {
	case 'image':
		if(of_get_option('background_image')){
			echo '<div class="dayu_background">'.(of_get_option('show_stripe') ? '<div id="stripe"></div>' : ''). '<img src="'.of_get_option('background_image').'"></div>';
		}
	break;
	case 'pattern':
		if( of_get_option('background_pattern_custom') ){
			echo '<div style="background-image: url(\''.of_get_option('background_pattern_custom').'\')" id="background_pattern"></div>';
		}elseif (of_get_option('background_pattern')) {
			echo '<div style="background-image: url(\''.get_template_directory_uri() . '/inc/theme-options/images/pattern/large/'.of_get_option('background_pattern').'\')" id="background_pattern"></div>';
		}
	break;
}
?>
<header class="metabar">
<!--这是nav导航栏上面的栏目，可以参考dayu官网或欲思博客上面的大蓝块-->
    <div id="masthead" role="banner" class="hidden-xs">
		<div class="top-banner">
			<div class="container">
				<?php
				$site_logo = of_get_option('site_logo');
				if ( !empty( $site_logo ) ) { ?>
				<!--rel="nofollow"代表goole搜索引擎不要索引-->
					<a class="brand brand-image" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<!--esc_attr过滤作用，get_bloginfo的第二个参数也是过滤作用-->
						<img src="<?php echo $site_logo; ?>" width="200px" height="50px" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
						<!--当屏幕很小时就隐藏-->
						<h1 class="hidden-xs"><?php if(of_get_option('show_blogdescription')){ ?>
							<small><?php bloginfo( 'description' ); ?></small>
							<?php } ?>
						</h1>
					</a>
				<?php }else{ ?>
					<a class="brand brand-text" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
						<h1>
							<?php bloginfo( 'name' ); //如：58同城 ?>
							<!--一个神奇的网站-->
							<?php if(of_get_option('site_description')){ ?>
								<small><?php bloginfo( 'description' ); ?></small>
							<?php } ?>
						</h1>
					</a>
				<?php } ?>
				<!--本人的微博，github等等可以参考dayu官网-->
				<div class="top-social pull-right hidden-xs">
					<?php echo (!of_get_option('social_sina')) 	? '' : '<a id="s_sina_weibo" title="新浪微博" target="_blank" href="' . of_get_option('social_sina') . '" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-weibo"></i></a>'; ?>
					<?php echo (!of_get_option('social_tencent')) 	? '' : '<a id="s_tencent_weibo" title="腾讯微博" target="_blank" href="' . of_get_option('social_tencent') . '" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-tencent-weibo"></i></a>'; ?>
					<?php echo (!of_get_option('social_email')) 	? '' : '<a id="s_email" title="EMAIL" target="_blank" href="mailto:' . of_get_option('social_email') . '" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-envelope-o"></i></a>'; ?>
					<?php echo (!of_get_option('social_github')) 	? '' : '<a id="s_github" title="GITHUB" target="_blank" href="' . of_get_option('social_github') . '" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-github"></i></a>'; ?>
					<?php echo (!of_get_option('social_google_plus')) 	? '' : '<a id="s_google_plus" title="GOOGLE+" target="_blank" href="' . of_get_option('social_google_plus') . '" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-google-plus-square"></i></a>'; ?>
					<?php echo (!of_get_option('social_rss')) 	? '' : '<a id="s_rss" title="RSS" target="_blank" href="' . of_get_option('social_rss') . '" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-rss-square"></i></a>'; ?>
				</div>
			</div>
		</div>
	</div>
	<!--role是角色的意思，指定该div的角色是什么
    Eg1:<div role="button"></div> ，把div元素转换为button按钮功能使用；
    Eg2:<div role="navigation"></div>，把div元素转换为navigation导航功能使用；
    Eg3:<div role="checkbox" aria-checked="checked"></div>，把div元素转换为checkbox复选框功能使用；
    Eg4:<a role="button" class="btn btn-default" href="#" >链接</a>，把a链接元素转换为button按钮功能使用-->
	<!--.container （固定宽度）或 .container-fluid （100% 宽度）-->
	    <nav id="nav" class="navbar navbar-default container-fluid" role="navigation">
        <div class="container">
             <!-- 参考菜鸟教程"响应式的导航栏"【http://www.runoob.com/bootstrap/bootstrap-navbar.html】 -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
				<!--这一行使用了外部的css【css/font-awesome.min.css】-->
                    <span class="fa fa-bars"></span>
                </button>
				<?php $site_logo_mini = of_get_option('site_logo_mini');?>
				<a class="navbar-brand visible-xs" href="<?php echo home_url( '/' ); ?>" <?php if($site_logo_mini) echo "style='padding:2px 10px'"; ?>>
					<?php
					if ( !empty( $site_logo_mini ) ) {?>
						<img src="<?php echo $site_logo_mini; ?>" width="150px" height="50px" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
					<?php }else{
						bloginfo( 'name' );
					}?>
				</a>
            </div>
            <!-- 这个id="navbar-collapse"和上面的data-target="#navbar-collapse"对应 -->
            <div class="collapse navbar-collapse" id="navbar-collapse">
			<!--是否在顶部导航栏显示，如果不是就显示"页面"，后台->页面->所有页面-->
                <?php if ( has_nav_menu( 'primary' ) ) {
					// https://www.dayuzy.com/wordpress%E4%B8%ADwp_nav_menu%E7%9A%84%E7%94%A8%E6%B3%95/【wp_nav_menu用法】
					// https://www.dayuzy.com/wp_nav_menu%E4%B8%ADitems_wrap%E5%8F%82%E6%95%B0%E7%94%A8%E6%B3%95/【wp_nav_menu中items_wrap参数用法】
					// https://www.dayuzy.com/wp_nav_menu%E4%B8%ADwalker%E5%8F%82%E6%95%B0%E4%BD%9C%E7%94%A8/【wp_nav_menu()中walker参数作用】
                    wp_nav_menu( array('theme_location' => 'primary','container' => '','container_class' => '','container_id' => '','menu_class' => 'nav navbar-nav','items_wrap' => '<ul class="%2$s">%3$s</ul>','walker' => new Bootstrap_Walker)); //左侧主菜单
                    }else{
                    echo '<ul class="nav navbar-nav">';
					//https://www.dayuzy.com/wordpress%E4%B8%ADwp_list_pages-%E7%9A%84%E7%94%A8%E6%B3%95/【wp_list_pages用法】
                    wp_list_pages('sort_column=menu_order&title_li=');
                    echo '</ul>';
                } ?>
                <form action="<?php echo home_url( '/' ); ?>" method="get" id="searchform" class="navbar-form navbar-right visible-lg" role="search">
                    <div class="form-group">
					<!--name必须为's'才能使用wordpress的查找功能【https://www.dayuzy.com/wordpress-%E4%B8%ADget_search_form%E7%9A%84%E7%94%A8%E6%B3%95/】【wordpress 中get_search_form的用法】-->
                        <input type="text" name='s' id='s' class="form-control" placeholder="这里有你想要的" x-webkit-speech>
                        <!--下行class使用了外部的css类【css/font-awesome.min.css】-->
						<button class="btn btn-danger" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                    <!--<button type="submit" class="btn btn-default">提交</button>-->
                </form>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</header>

<!--正文开始-->
<div class="container main-contents">
	<!--h5的section标签【http://www.runoob.com/tags/tag-section.html】-->
	<!--注意.row是必不可少的，使用了它以后才能用栅栏系统每1行12列，才可以使用如：".col-xs-4"-->
   <section class="row">




















