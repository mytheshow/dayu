<?php

define( '_DaYu_VERSION', 1.0 );
//注册"外观"->"小工具"
require_once(TEMPLATEPATH . '/inc/widgets.php');

require_once(TEMPLATEPATH . '/inc/theme-options.php');
if (of_get_option('disable_google_font')) {
    require_once(TEMPLATEPATH . '/inc/disable-google-fonts.php');
}
//加载脚本
function dayu_theme_scripts() {
    //global $pagenow;
    if(!is_admin()){
        $dir = get_template_directory_uri();
        wp_enqueue_script( 'jquerylib', $dir . '/js/jquery.min.js' , array(), '1.11.0');
        wp_enqueue_script( 'bootstrap', $dir . '/inc/bootstrap-3.3.4/js/bootstrap.min.js', array(), '3.2.0');
		wp_enqueue_script( 'lazyload', $dir . '/js/jquery.lazyload.js', array(), '1.19');
        wp_enqueue_script( 'dayu', $dir . '/js/dayu.js', array(), _DaYu_VERSION);
        wp_enqueue_style( 'bootstrap-style', $dir . '/inc/bootstrap-3.3.4/css/bootstrap.min.css', array(), '3.2.0');
		//一些图标样式，比如顶部搜索的放大镜样式，注释掉下面的一行就会失效
        wp_enqueue_style( 'awesome-style', $dir . '/inc/font-awesome/css/font-awesome.min.css', array(), '4.1.0');
        wp_enqueue_style( 'DaYu-style', get_stylesheet_uri(), array(), _DaYu_VERSION);
        //if(is_page_template('page-comment-tj.php')){
        //    wp_enqueue_script( 'highcharts', 'http://code.highcharts.com/highcharts.js', array(), '3.0.10',true);
        //}
		if(!of_get_option('disable_fixed_header')){
			wp_enqueue_script( 'fixed-top', $dir . '/js/fixed-top.js', array(), _DaYu_VERSION);
		}
    }
} 
add_action('wp_enqueue_scripts', 'dayu_theme_scripts');
//特色图片支持，显示文章缩略图
add_theme_support( 'post-thumbnails' );
//状态（status） - 简短更新，通常最多 140 个字符。类似于微博 Twitter 状态消息。
//https://www.dayuzy.com/wordpress-%E4%B8%ADadd_theme_support%E7%9A%84%E7%94%A8%E6%B3%95/【wordpress 中add_theme_support的用法】
add_theme_support( 'post-formats', array('status'));
//在后台添加链接管理功能
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

//注册菜单选项功能，并且添加显示位置，如果没有此选项，外观下面没有菜单那个按钮
//https://www.dayuzy.com/wordpress%E4%B8%ADregister_nav_menus%E7%9A%84%E7%94%A8%E6%B3%95/【wordpress中register_nav_menus的用法】
if(!function_exists('dayu_register_nav_menu')){
	function dayu_register_nav_menu() {
		register_nav_menus(
			array(
				'primary'	=>	'头部主菜单', // 菜单显示位置，如果没有该值，就没有显示位置
			)
		);
	}
}
add_action( 'after_setup_theme', 'dayu_register_nav_menu' );

//首页幻灯片,使用位置:dayu1.0\index.php
function dayu_slide(){
    //如果没有开启缓存滑动图画
	if( !$output = wp_cache_get('dayu_slides') ){
		$output = '';
        //是否开启了滑动
		$dayu_slide_on = of_get_option("show_slide") ? of_get_option("show_slide") : 0;
        //如果开启了，就把所有的轮番图名字和url存入数组
		if($dayu_slide_on){
			for($i=1; $i<6; $i++){
				$dayu_slide{$i} = of_get_option("dayu_slide{$i}") ? of_get_option("dayu_slide{$i}") : "";
				$dayu_slide_url{$i} = of_get_option("dayu_slide_url{$i}") ? of_get_option("dayu_slide_url{$i}") : "";
				if($dayu_slide{$i} ){
					$slides[] = $dayu_slide{$i};
					$slides_url[] = $dayu_slide_url{$i};
				}
			}
			$count = count($slides);
			//print_r($slides);print_r($slides_url);
            //轮播图功能【http://www.runoob.com/bootstrap/bootstrap-carousel-plugin.html】
            //data-ride="carousel" 属性用于标记轮播在页面加载时就开始动画播放
			$output .= '<div id="slide" class="carousel slide" data-ride="carousel">';
			$output .= '<ol class="carousel-indicators">';
            //轮播（Carousel）指标,即轮播图下面的几个点，点哪个点切换(指)哪个，可以运行菜鸟实例去掉该功能看看效果
			for($i=0; $i<$count; $i++){
				$output .= '<li data-target="#slide" data-slide-to="'.$i.'"';
				if($i==0) $output .= 'class="active"';
				$output .= '></li>';
			};
			$output .='</ol>';
            //轮播（Carousel）项目
			$output .= '<div class="carousel-inner" role="listbox">';
			for($i=0;$i<$count;$i++){
				$output .= '<div class="item';
				if($i==0) $output .= ' active';
				$output .= '">';
                //如果没有链接就只显示图片
				if(!empty($slides_url[$i])){
					$output .= '<a href="'.$slides_url[$i].'"><img src="'.$slides[$i].'"/></a>';
				}else{
					$output .= '<img src="'.$slides[$i].'"/>';
				}
				$output .= "</div>";
			};
			$output .= '</div>';
            // 轮播（Carousel）导航，即前一张图，后一张图(手动左右翻图)
			$output .= '<a class="left carousel-control" href="#slide" role="button" data-slide="prev">';
			$output .= '<span class="glyphicon glyphicon-chevron-left"></span>';
			$output .= '<span class="sr-only">Previous</span></a>';
			$output .= '<a class="right carousel-control" href="#slide" role="button" data-slide="next">';
			$output .= '<span class="glyphicon glyphicon-chevron-right"></span>';
			$output .= '<span class="sr-only">Next</span></a></div>';
            //设置上缓存
			wp_cache_set('dayu_slides', $output);
		}
	}
	echo $output;
}

function clear_slides(){
	wp_cache_delete('dayu_slides'); // 清空 dayu_slides
}
add_action( 'optionsframework_after_validate', 'clear_slides' );

/**
 *该函数判断几分前、几小时前、几天前，超过一个月就用具体的"年/月/日",使用位置:inc\post-formats\content.php
 */
function past_date() {
    global $post;
    $suffix = '前';
    $day = ' 天';
    $hour = ' 小时';
    $minute = ' 分钟';
    $second = ' 秒';
    $m = 60;
    $h = 3600;
    $d = 86400;
    $post_time = get_post_time('G', true, $post);
    $past_time = time() - $post_time;
    if ($past_time < $m) {
        $past_date = $past_time . $second;
    } else if ($past_time < $h) {
        $past_date = $past_time / $m;
        $past_date = floor($past_date);
        $past_date .= $minute;
    } else if ($past_time < $d) {
        $past_date = $past_time / $h;
        $past_date = floor($past_date);
        $past_date .= $hour;
    } else if ($past_time < $d * 30) {
        $past_date = $past_time / $d;
        $past_date = floor($past_date);
        $past_date .= $day;
    } else {
        the_time('Y/m/d');
        return;
    }
    echo $past_date . $suffix;
}
add_filter('past_date', 'past_date');

//该函数获取文章的观看数量，使用位置：inc\post-formats\content.php
function dayu_get_post_views($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
	//如果为空说明是一篇新文章，还没有该属性
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0";
    }
	//不为空，就返回具体数量
    return $count;
}
//获取文章的内容，用正则匹配出里面是否有图片，如果有就把第一章图片传给"timthumb.php"进行缩略图处理显示在博客首页文章列表那个小图
function _dayu_post_thumbnail( $width = 255,$height = 130 ){
    global $post;
    $content = $post->post_content;
    preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
    $n = count($strResult[1]);
    if($n > 0){
		//这里是惰性加载
        return '<img class="thumb pull-left hidden-xs" src="'.get_bloginfo('template_directory').'/images/lazy_loading.gif" data-original="'.get_bloginfo("template_url").'/timthumb.php?w='.$width.'&amp;h='.$height.'&amp;src='.$strResult[1][0].'" title="'.get_the_title().'" alt="'.get_the_title().'"/>';
    }
}

/**
 *
 * 列表分页,使用位置：dayu1.0\index.php
 */
function dayu_pages($range = 5){
    //https://www.dayuzy.com/wordpress%E4%B8%AD%E7%9A%84%E5%85%A8%E5%B1%80%E5%8F%98%E9%87%8F/【wordpress中的全局变量】
    global $paged, $wp_query;
    //用is_set比较好
    if ( !$max_page ) {
        $max_page = $wp_query->max_num_pages;
    }
    //如果是首页，就把页面设置为1
    if($max_page > 1){if(!$paged){$paged = 1;}
	echo "<ul class='pagination pull-right'>";
        //如果不是第一页就出现"<<“按钮，点击后回到第一页，即首页
        if($paged != 1){
            echo "<li><a href='" . get_pagenum_link(1) . "' class='extend' title='首页'>&laquo;</a></li>";
        }
        if($paged>1) echo '<li><a href="' . get_pagenum_link($paged-1) .'" class="prev" title="上一页">&lt;</a></li>';
        if($max_page > $range){
            //当前页小于5
            if($paged < $range){
                //输出6页(6个页面按钮)，后面几个elseif不分析了，绕的头晕
                for($i = 1; $i <= ($range + 1); $i++){
                    echo "<li"; if($i==$paged)echo " class='active'";echo "><a href='" . get_pagenum_link($i) ."'>$i</a></li>";
                }
            }
            elseif($paged >= ($max_page - ceil(($range/2)))){
                for($i = $max_page - $range; $i <= $max_page; $i++){
                    echo "<li";
                    if($i==$paged)
                        echo " class='active'";echo "><a href='" . get_pagenum_link($i) ."'>$i</a></li>";
                }
            }
            elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){
                for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){
                    echo "<li";
                    if($i==$paged)echo " class='active'";
                    echo "><a href='" . get_pagenum_link($i) ."'>$i</a></li>";
                }
            }
        }
        else{
            for($i = 1; $i <= $max_page; $i++){
                echo "<li";
                if($i==$paged)echo " class='active'";
                echo "><a href='" . get_pagenum_link($i) ."'>$i</a></li>";
            }
        }
        if($paged<$max_page) echo '<li><a href="' . get_pagenum_link($paged+1) .'" class="next" title="下一页">&gt;</a></li>';
        if($paged != $max_page){
            echo "<li><a href='" . get_pagenum_link($max_page) . "' class='extend' title='尾页'>&raquo;</a></li>";
        }
        echo "</ul>";
	}
}
/**
 *文章的浏览量+1，使用位置dayu/single.php in line 18.
 */
function dayu_set_post_views($postID) {
	$count_key = 'post_views_count';
	$count = dayu_get_post_views($postID);
    $count++;
    update_post_meta($postID, $count_key, $count);
}

//点赞功能后台处理，如果禁用cookie可以一直点赞【亲测】
add_action('wp_ajax_nopriv_dayu_zan', 'dayu_zan');
add_action('wp_ajax_dayu_zan', 'dayu_zan');
function dayu_zan(){
    global $wpdb,$post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    //获取文章id
    $resId = $wpdb->get_var( $wpdb->prepare("SELECT ID FROM $wpdb->posts
            WHERE ID = %s and (post_type = 'post' or post_type = 'page') LIMIT 1", $id) );
    //如果为空说明该文章不存在
    if (empty($resId)) return;
    if ( $action == 'ding'){
        $dayu_raters = get_post_meta($id,'dayu_zan',true);
        $expire = time() + 99999999;
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost
        //给该ip指定过期时间，呵呵，如果禁用cookie可以一直点赞，修改该bug可以使用redis或session
        setcookie('dayu_zan_'.$id,$id,$expire,'/',$domain,false);
        if (!$dayu_raters || !is_numeric($dayu_raters)) {
            update_post_meta($id, 'dayu_zan', 1);
        }
        else {
            update_post_meta($id, 'dayu_zan', ($dayu_raters + 1));
        }
        $zan = get_post_meta($id,'dayu_zan',true);
        if ($zan) {
            die(json_encode(array('status' => 200, 'data' => $zan)));
        }
    }
    die(json_encode(array('status' => 0, 'data' => 0)));
}


/**
 * 相关文章查询功能
 */

function dayu_relatedpost($post_num = 5) {
	global $post;
    echo '<ul>';
    $exclude_id = $post->ID;
    //获取所有相关的标签
    $posttags = get_the_tags(); $i = 0;
    //如果有标签，添加上相关标签查询的结果
    if ( $posttags ) {
        //所有相关标签的id
        $tags = ''; foreach ( $posttags as $tag ) $tags .= $tag->term_id . ',';
        $args = array(
            'post_status' => 'publish',
            'tag__in' => explode(',', $tags),
            //不包括本文章
            'post__not_in' => explode(',', $exclude_id),
            //忽视简介
            'ignore_sticky_posts' => 1,
            'orderby' => 'comment_date',
            //指定相关文章的数量
            'posts_per_page' => $post_num
        );
        query_posts($args);
        while( have_posts() ) { the_post(); ?>
            <li>
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" target="_blank"><?php the_title(); ?></a>
            </li>
            <?php
            $exclude_id .= ',' . $post->ID; $i ++;
        }
        //https://www.dayuzy.com/wordpress%E4%B8%ADwp_reset_query%E7%9A%84%E7%94%A8%E6%B3%95/【wordpress中wp_reset_query的用法】
		wp_reset_query();
    }
    if ( $i < $post_num ) {
        $cats = ''; foreach ( get_the_category() as $cat ) $cats .= $cat->cat_ID . ',';
        $args = array(
            'category__in' => explode(',', $cats),
            'post__not_in' => explode(',', $exclude_id),
            'ignore_sticky_posts' => 1,
            'orderby' => 'comment_date',
            'posts_per_page' => $post_num - $i
        );
        query_posts($args);
        while( have_posts() ) { the_post(); ?>
            <li>
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" target="_blank"><?php the_title(); ?></a>
            </li>
            <?php $i++;
        }
		wp_reset_query();
    }
    if ( $i  == 0 )  echo '<li>没有相关文章!</li>';
    echo '</ul>';
}

/**
 * 
 * 自定义评论列表，被comments.php的wp_list_comments调用
 *
 */
function dayu_comment($comment, $args, $depth) {

	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);


?>
<!--comment_class添加一些wordpress默认的评论类，就是不使用自定义评论时那些-->
<!--既然我们是自定义，可以传递一些可选参数添加我们自己的类，用开发者工具查看-->
<!--<li class="comment even thread-even depth-1 parent" id="comment-7"如果是回复评论就没有'parent'这个类-->
	<li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
    <div class="comment-wrap" id="comment-<?php comment_ID() ?>">
        <div class="comment-author pull-left">
        <?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
        </div>
        <div class="comment-body">
            <h4>
                <!--%1$s %2$s是占位符，分别是第二和第三个参数-->
                <?php printf( '<cite class="fn">%1$s %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === get_queried_object()->post_author ) ? '<small class="label label-primary">博主</small>' : ''
					); ?>
                <span class="comment-date">
                    <?php printf( __('%1$s'), get_comment_date("Y/m/d H:i") ); ?>
                </span>
            </h4>
            <?php if ( $comment->comment_approved == '0' ) ://评论批准 ?>
                <p class="comment-awaiting-moderation text-danger"><?php echo "您的评论正在等待审核"; ?></p>
            <?php endif; ?>
            <p>
                <?php if($comment->comment_parent){// 如果存在父级评论
                $comment_parent_href = get_comment_ID( $comment->comment_parent );
                $comment_parent = get_comment($comment->comment_parent);
                ?>
                    <!--@它的父级评论作者名，注意：父级评论不一定是顶级评论-->
                <span class="comment-to plr">@</span>
                <span class="reply-comment-author">
                    <!--apply_filters('the_content', $comment_parent->comment_content)，好吧，关于过滤器apply_filters的第二个参数会自动传递给add_filter添加的过滤钩子函数-->
                    <!--如果一个过滤钩子函数也没有，就相当于不对内容进行任何处理，也就是返回$comment_parent->comment_content-->
                    <!--strip_tags是PHP的原生函数【http://www.w3school.com.cn/php/func_string_strip_tags.asp】-->
                    <a href="#comment-<?php echo $comment_parent_href;?>" title="<?php echo dayu_string_cut(strip_tags(apply_filters('the_content', $comment_parent->comment_content)), 100); ?>">
                        <?php echo $comment_parent->comment_author;?>
                    </a>
                </span>
                <?php }?>
                <?php echo convert_smilies(get_comment_text()); ?>
            </p>

            <div class="reply clearfix">
                <!--https://www.dayuzy.com/wordpress%E4%B8%ADcomment_reply_link%E7%9A%84%E7%94%A8%E6%B3%95/【wordpress中comment_reply_link的用法】-->
                <?php comment_reply_link( array_merge( $args, array( 'reply_text' => '<div class="label label-danger pull-right">回复</div>','depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </div>

        </div>
    </div>
<?php
}

/**
 * 字符串截取函数
 * @ param sublen 截取的长度
 * @ param $start 开始位置
 */
function dayu_string_cut($string, $sublen, $start = 0, $code = 'UTF-8') {
    /*
       F:\wordpress\wp-content\themes\dayu1.0\functions.php:395:
       array (size=1)
        0 =>
        array (size=11)
        0 => string '萨' (length=3)
        1 => string '达' (length=3)
        2 => string '萨' (length=3)
        3 => string '达' (length=3)
        4 => string '反' (length=3)
        5 => string '对' (length=3)
        6 => string '法' (length=3)
        7 => string 'v' (length=1)
        8 => string '发' (length=3)
        9 => string 'v' (length=1)
        10 => string '在' (length=3)
     */
     if($code == 'UTF-8') {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);
        if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen)) . "...";
        return join('', array_slice($t_string[0], $start, $sublen));
    } else {
        $start = $start * 2;
        $sublen = $sublen * 2;
         // strlen()获取的是字符串的字节数,gbk是2个字节
        $strlen = strlen($string);
        $tmpstr = '';

        for($i = 0; $i < $strlen; $i++) {
            //$i大于等于开始的位置，小于截取的长度
            if($i >= $start && $i < ($start + $sublen)) {
                //substr(string,start,length)
                //ord返回开头字符的ASCII值，129以下占用一个字节
                if(ord(substr($string, $i, 1)) > 129) $tmpstr .= substr($string, $i, 2);
                else $tmpstr .= substr($string, $i, 1);
            }
            //如果占两个字节，需要再往后移动一个位置
            if(ord(substr($string, $i, 1)) > 129) $i++;
        }
        //如果截取的小于总长度，多余的用"..."代替
            if(strlen($tmpstr) < $strlen ) $tmpstr .= "...";
            return $tmpstr;
    }
}

/**
 * 获取最新文章评论内容
 */
function dayu_latest_comments_list($list_number=5, $avatar_size=32, $cut_length=20) {
	global $wpdb;
	global $admin_email;
	//print_r($admin_email);
	$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url,comment_author_email, comment_content AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND user_id != '1' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT $list_number" ;
	//echo $sql;
	$comments = $wpdb->get_results($sql);

    $output = '';
	foreach ($comments as $comment) {
	  $output .= "\n<a class=\"list-group-item\" href=\"" . get_the_permalink($comment->comment_post_ID) . "#comments\" title=\"发表在 " .$comment->post_title . "\">".get_avatar( $comment, $avatar_size )." " . convert_smilies(dayu_string_cut(strip_tags($comment->com_excerpt), $cut_length))."&nbsp;</a></span></a>";
	}

	//$output = convert_smilies($output);

	return $output;
}

/**
 * 获取评论最多的文章，在widgets.phpd的文章小工具(最热文章)中有使用
 */
function most_comm_posts($days=30, $nums=5) { //$days参数限制时间值，单位为‘天’，默认是30天；$nums是要显示文章数量
	global $wpdb;
    date_default_timezone_set("PRC");
	$today = date("Y-m-d H:i:s"); //获取今天日期时间
	$daysago = date( "Y-m-d H:i:s", strtotime($today) - ($days * 24 * 60 * 60) );  //Today - $days
	$result = $wpdb->get_results("SELECT comment_count, ID, post_title, post_date FROM $wpdb->posts WHERE post_date BETWEEN '$daysago' AND '$today' and post_type='post' and post_status='publish' ORDER BY comment_count DESC LIMIT 0 , $nums");
	$output = '';
	if(empty($result)) {
		$output = '<li>None data.</li>';
	} else {
		foreach ($result as $topten) {
			$postid = $topten->ID;
			$title = $topten->post_title;
			$commentcount = $topten->comment_count;
			if ($commentcount >= 0) {
				//$output .= '<li><a href="'.get_permalink($postid).'" title="'.$title.'">'.$title.'</a> ('.$commentcount.')</li>';

                $output .= '<a class="list-group-item visible-lg" title="'. $title .'" href="'.get_permalink($postid).'" rel="bookmark">';
                    $output .= dayu_string_cut(strip_tags($title), 18);
                    $output .= '<i class="fa fa-comment badge"> '.$commentcount.'</i>';
                $output .= '</a>';
                $output .= '<a class="list-group-item visible-md" title="'. $title .'" href="'.get_permalink($postid).'" rel="bookmark">';
                    $output .= dayu_string_cut(strip_tags($title), 12);
                    $output .= '<i class="fa fa-comment badge"> '.$commentcount.'</i>';
                $output .= '</a>';
			}
		}
	}
	echo $output;
}

//图片延迟加载
add_filter ('the_content', 'lazyload');
function lazyload($content) {
	global $post;
	$loadimg_url=get_bloginfo('template_directory').'/images/lazy_loading.gif';
	if(!is_page()) {
		$content=preg_replace('/<img(.+)src=[\'"]([^\'"]+)[\'"](.*)>/i',"<img\$1data-original=\"\$2\" src=\"$loadimg_url\"\$3>",$content);
	}
	return $content;
}

//关于【wp_nav_menu()中walker参数作用】https://www.dayuzy.com/wp_nav_menu%E4%B8%ADwalker%E5%8F%82%E6%95%B0%E4%BD%9C%E7%94%A8/
//自定义下拉菜单
class Bootstrap_Walker extends Walker_Nav_Menu
{

/*
 <li id="menu-item-54" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item-has-children dropdown dropdown active"><a href="http://www.gohosts.com/" class="dropdown-toggle" data-toggle="dropdown" data-original-title="" title=""><i class="fa fa-home"></i>首页<b class="caret"></b><i class="icon-angle-down"></i></a>
    <ul class="dropdown-menu aaaaaa">【顶级】
        <li id="menu-item-57" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-has-children dropdown dropdown dropdown-submenu"><a href="http://www.gohosts.com/archives/category/article" data-original-title="" title="">技术文章<b class="caret"></b></a>
            <ul class="dropdown-menu aaaaaa">【一级】
                <li id="menu-item-160" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-has-children dropdown dropdown"><a href="http://www.gohosts.com/archives/category/fenlei1" data-original-title="" title="">分类目录一<b class="caret"></b></a>
                    <ul>
                    <li id="menu-item-161" class="menu-item menu-item-type-taxonomy menu-item-object-category"><a href="http://www.gohosts.com/archives/category/fenlei2" data-original-title="" title="">分类目录二</a></li>
                    </ul>
                </li>
            </ul>
        </li><!--.dropdown-->
    </ul>
</li>
*/
    /**
     * 对<ul>进行处理
     */
    function start_lvl( &$output, $depth = 0, $args = array() )
    {
        //"\t"是tab的意思，重复输出多少个"\t"
        $tabs = str_repeat("\t", $depth);
        //bootstrap下拉菜单【http://www.runoob.com/bootstrap/bootstrap-dropdowns.html】
        //如果是顶级或者一级菜单就出现下拉显示的效果(添加dropdown-menu)，可以if ($depth == 0)这样写看看效果
        if ($depth == 0 || $depth == 1) {
            $output .= "\n{$tabs}<ul class=\"dropdown-menu\">\n";
        } else {
            //如果大于一级就直接显示"<ul>",它的意思是没有下拉隐藏效果(直接显示)，只有分级效果
            $output .= "\n{$tabs}<ul>\n";
        }
    }
    function end_lvl( &$output, $depth = 0, $args = array() )
    {
        if ($depth == 0) {
            //如果是顶级菜单我们顶级菜单结束，一级菜单开始的地方加个"<!--.dropdown-->"注释
            $output .= '<!--.dropdown-->';
        }
        $tabs = str_repeat("\t", $depth);
        $output .= "\n{$tabs}</ul>\n";
    }

    /**
     * <li>进行处理
     */
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 )
    {
        global $wp_query;
        //缩进，根据深度进行"tab"缩进
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        //获取该$item的类"class"有哪些
        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        /* $item是该菜单里面的分类，一个菜单里面好几个分类，有的分类下面还有二级分类(一级二级三级菜单)*/
        if ($item->hasChildren) {
            //如果有二级菜单(或二级分类)，就给该item加上bootstrap中的'dropdown'类，有了该类会出现下拉效果
            $classes[] = 'dropdown';//改行可以注释掉，默认wordpress会加一个'dropdown'类
            // 一级菜单加个'dropdown-submenu'，该类的作用【https://v2.bootcss.com/components.html】
            if($depth == 1) {
                $classes[] = 'dropdown-submenu';
            }
        }

        /* 对下拉出现的<li>添加类和属性 */
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class',$classes, $item ) );
        $class_names = ' class="' . esc_attr( $class_names ) . '"';
        $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $class_names .'>';
        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
        $item_output = $args->before;

        /* 如果是顶级菜单，添加bootstrap的下拉类 */
        if ($item->hasChildren && $depth == 0) {
            $item_output .= '<a'. $attributes .' class="dropdown-toggle" data-toggle="dropdown">';
        } else {
            $item_output .= '<a'. $attributes .'>';
        }

        $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

        /* Output the actual caret for the user to click on to toggle the menu */
        if ($item->hasChildren && $depth == 0) {
            $item_output .= '<i class="icon-angle-down"></i></a>';
        } else {
            $item_output .= '</a>';
        }

        $item_output .= $args->after;
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        return;
    }

    /* Close the <li>
     * Note: the <a> is already closed
     * Note 2: $depth is "correct" at this level
     */
    function end_el ( &$output, $item, $depth = 0, $args = array() )
    {
        $output .= '</li>';
        return;
    }

    /* 给$item对象添加一个'hasChildren'属性
     * 添加以后可以在上面的start_el()函数中使用$item->hasChildren;进行判断有没有子元素
     */
    function display_element ($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
    {
        $element->hasChildren = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]);
        return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}

//修改拥有下级菜单的菜单项<li>,添加一个<b class="caret"></b>
add_filter( 'wp_nav_menu_objects', 'add_menu_parent_class' );
function add_menu_parent_class( $items ) {
	$parents = array();
    //$items是所有的<li>对象们
	foreach ( $items as $item ) {
		//if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
        //判断当前<li>有没有父级<li>
        if ( $item->menu_item_parent) {
			$parents[] = $item->menu_item_parent;
		}
	}
	foreach ( $items as $item ) {
		if ( in_array( $item->ID, $parents ) ) {
			$item->classes[] = 'dropdown';
			$item->title = $item->title.'<b class="caret"></b>';
			$item->a_class = 'dropdown-toggle';
			//$item->data_toggle = 'dropdown';
		}
	}
	return $items;
}
//添加active为当前激活的菜单CSS类
function current_menu_class( $classes ) {
     if ( in_array('current-menu-item', $classes ) OR in_array( 'current-menu-ancestor', $classes ) )
          $classes[] = 'active';
     return $classes;
}
add_filter( 'nav_menu_css_class', 'current_menu_class' );


/*优化加速开始 【https://www.dayuzy.com/wordpress%E7%A6%81%E7%94%A8%E8%B0%B7%E6%AD%8C%E5%AD%97%E4%BD%93-%E7%A6%81%E7%94%A8%E8%A1%A8%E6%83%85-html%E4%BB%A3%E7%A0%81%E5%8E%8B%E7%BC%A9-%E7%BC%93%E5%AD%98%E6%8F%92%E4%BB%B6/】*/

//删除emoji脚本
remove_action( 'admin_print_scripts',   'print_emoji_detection_script');
remove_action( 'admin_print_styles',    'print_emoji_styles');
remove_action( 'wp_head',       'print_emoji_detection_script', 7);
remove_action( 'wp_print_styles',   'print_emoji_styles');
remove_filter( 'the_content_feed',  'wp_staticize_emoji');
remove_filter( 'comment_text_rss',  'wp_staticize_emoji');
remove_filter( 'wp_mail',       'wp_staticize_emoji_for_email');
//移除wp-json链接
add_filter('rest_enabled', '_return_false');
add_filter('rest_jsonp_enabled', '_return_false');
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
//禁用embeds功能
function disable_embeds_init() {
    /* @var WP $wp */
    global $wp;
    $wp->public_query_vars = array_diff( $wp->public_query_vars, array(
        'embed',
    ) );
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );
    add_filter( 'embed_oembed_discover', '__return_false' );
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );
    add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
}
add_action( 'init', 'disable_embeds_init', 9999 );
function disable_embeds_tiny_mce_plugin( $plugins ) {
    return array_diff( $plugins, array( 'wpembed' ) );
}
function disable_embeds_rewrites( $rules ) {
    foreach ( $rules as $rule => $rewrite ) {
        if ( false !== strpos( $rewrite, 'embed=true' ) ) {
            unset( $rules[ $rule ] );
        }
    }
    return $rules;
}
function disable_embeds_remove_rewrite_rules() {
    add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'disable_embeds_remove_rewrite_rules' );
function disable_embeds_flush_rewrite_rules() {
    remove_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'disable_embeds_flush_rewrite_rules' );

//删除head头部多余脚本
remove_action( 'wp_head', 'feed_links_extra', 3 ); //去除评论feed
remove_action( 'wp_head', 'feed_links', 2 ); //去除文章feed
remove_action( 'wp_head', 'rsd_link' ); //针对Blog的远程离线编辑器接口
remove_action( 'wp_head', 'wlwmanifest_link' ); //Windows Live Writer接口
remove_action( 'wp_head', 'index_rel_link' ); //移除当前页面的索引
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); //移除后面文章的url
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); //移除最开始文章的url
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );//自动生成的短链接
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); ///移除相邻文章的url
remove_action( 'wp_head', 'wp_generator' ); // 移除版本号


//压缩html代码
function wp_compress_html(){
    function wp_compress_html_main ($buffer){
        $initial=strlen($buffer);
        $buffer=explode("<!--wp-compress-html-->", $buffer);
        $count=count ($buffer);
        for ($i = 0; $i <= $count; $i++){
            if (stristr($buffer[$i], '<!--wp-compress-html no compression-->')) {
                $buffer[$i]=(str_replace("<!--wp-compress-html no compression-->", " ", $buffer[$i]));
            } else {
                $buffer[$i]=(str_replace("\t", " ", $buffer[$i]));
                $buffer[$i]=(str_replace("\n\n", "\n", $buffer[$i]));
                $buffer[$i]=(str_replace("\n", "", $buffer[$i]));
                $buffer[$i]=(str_replace("\r", "", $buffer[$i]));
                while (stristr($buffer[$i], '  ')) {
                    $buffer[$i]=(str_replace("  ", " ", $buffer[$i]));
                }
            }
            $buffer_out.=$buffer[$i];
        }
        $final=strlen($buffer_out);
        $savings=($initial-$final)/$initial*100;
        $savings=round($savings, 2);
        $buffer_out.="\n<!--压缩前的大小: $initial bytes; 压缩后的大小: $final bytes; 节约：$savings% -->";
        return $buffer_out;
    }
    ob_start("wp_compress_html_main");
}

//自动在存在高亮代码的文章首尾插入免压缩注释
  function Code_Box($content) {
      $matches = array();
      //一下是匹配高亮代码的关键词，本代码适用于 Crayon Syntax Highlighter 插件，其他插件请自行分析关键词即可
      $c = "/(crayon-|<\/pre>)/i";
      if(preg_match_all($c, $content, $matches) && is_single()) {
          $content = '<!--wp-compress-html--><!--wp-compress-html no compression-->'.$content;
          $content.= '<!--wp-compress-html no compression--><!--wp-compress-html-->';
      }
      return $content;
  }
$compress_html = of_get_option('compress_html');
if(!empty($compress_html)){
	add_action('get_header', 'wp_compress_html');
	add_filter( "the_content", "Code_Box" );
} 


/*优化加速结束【https://www.dayuzy.com/wordpress%E7%A6%81%E7%94%A8%E8%B0%B7%E6%AD%8C%E5%AD%97%E4%BD%93-%E7%A6%81%E7%94%A8%E8%A1%A8%E6%83%85-html%E4%BB%A3%E7%A0%81%E5%8E%8B%E7%BC%A9-%E7%BC%93%E5%AD%98%E6%8F%92%E4%BB%B6/】*/