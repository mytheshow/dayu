<?php
//注册侧边栏小工具
function dayu_widgets_init() {
    register_sidebar( array(
        //如果'dayu'语言包有"Home Sidebar"的翻译，就返回翻译，没有就返回"Home Sidebar"
        //__()的第二个参数可以不写,默认是'default'，如果写必须和主题和插件的名字一致
        //【http://blog.wpjam.com/article/localizing-a-wordpress-plugin-using-poedit/】或看张聪的主题开发教程(我的网站"自学教程"模块下载)
        'name' => __( 'Home Sidebar', 'dayu' ),
        'id' => 'sidebar_home',
        'description' => __( '首页默认边栏,“dayu-文章”小工具需要放在最下面', 'dayu' ),
        //下面"%1$s"不知道如何进行替换的
        'before_widget' => '<aside id="%1$s" class="widget %2$s panel panel-dayu clearfix">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="panel-heading"><h2>',
        'after_title' => '</h2></div>',
    ) );

    register_sidebar( array(
        'name' => __( 'Single Page', 'dayu' ),
        'id' => 'sidebar_single',
        'description' => __( '文章及页面边栏,“dayu-文章”小工具需要放在最下面', 'dayu' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s panel panel-dayu clearfix">',
        'after_widget' => '</aside>',
        'before_title' => '<div class="panel-heading"><h2>',
        'after_title' => '</h2></div>',
    ) );
}
add_action( 'widgets_init', 'dayu_widgets_init' );
//移除WordPress自带的小工具
function remove_default_widget() {
//	unregister_widget('WP_Widget_Recent_Posts');//移除近期文章
	unregister_widget('WP_Widget_Recent_Comments');//移除近期评论
//	unregister_widget('WP_Widget_Meta');//移除站点功能
//	unregister_widget('WP_Widget_Tag_Cloud');//移除标签云
	unregister_widget('WP_Widget_Text');//移除文本框
//	unregister_widget('WP_Widget_Archives');//移除文章归档
	unregister_widget('WP_Widget_RSS');//移除RSS
//	unregister_widget('WP_Nav_Menu_Widget');//移除菜单
//	unregister_widget('WP_Widget_Pages');//移除页面
	unregister_widget('WP_Widget_Calendar');//移除日历
//    unregister_widget('WP_Widget_Categories');//移除分类目录
    unregister_widget('WP_Widget_Search');//移除搜索
}
add_action( 'widgets_init', 'remove_default_widget' );

class dayu_widget_ad extends WP_Widget {
	// 设定小工具信息
	function dayu_widget_ad() {
		$widget_ops = array(
			'name'        => 'dayu-广告组件',
			'description' => 'dayu 广告组件'
		);
        //第一个参数一般为false或类名
        //第二个参数是小工具名，但是如果第三个参数的数组中指定了'name'，该参数会无效
        //第三个参数一般是指定标签的描述，但是也可以指定name
		parent::WP_Widget( false, false, $widget_ops );
	}

	// 该函数是在前台输出的，如果该函数没有任何内容，就算后台使用了该小工具前台不会有任何输出
    //$instance是下面form提交过来的键值对数组
	function widget( $args, $instance ) {
		extract( $args );
		$aurl = $instance['aurl'] ? $instance['aurl'] : '';
		$imgurl = $instance['imgurl'] ? $instance['imgurl'] : '';
        //$before_widget和$after_widget的中间html代码是输出到前台的内容
		echo $before_widget;
		?>
		<div class="ad">
			<a href="<?php echo $aurl; ?>" target="_blank"><img class="img-responsive" src="<?php echo $imgurl; ?>" /></a>
		</div>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) {
		@$aurl = esc_attr( $instance['aurl'] );
		@$imgurl = esc_attr( $instance['imgurl'] );
		?>
			<p>
				<label for="<?php echo $this->get_field_id( 'aurl' ); ?>">
					指向链接：
					<input class="widefat" id="<?php echo $this->get_field_id( 'aurl' ); ?>" name="<?php echo $this->get_field_name( 'aurl' ); ?>" type="text" value="<?php echo $aurl; ?>" />
				</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'imgurl' ); ?>">
					广告图路径：
					<input class="widefat" id="<?php echo $this->get_field_id( 'imgurl' ); ?>" name="<?php echo $this->get_field_name( 'imgurl' ); ?>" type="text" value="<?php echo $imgurl; ?>" />
				</label>
			</p>
		<?php
	}
}

//边栏公告
class dayu_widget_notice extends WP_Widget {
    function __construct(){
        $widget_ops = array(
            'description' => '边栏公告'
        );
        parent::__construct('dayu_widget_notice' ,'dayu-边栏公告', $widget_ops);
    }
    function widget($args, $instance){
        extract($args);
        $result = '';
        $content = (!empty($instance['content'])) ? $instance['content'] : '';
        $type = (!empty($instance['type'])) ? $instance['type'] : 'success';
        $result .= '<div  class="widget widget-notice alert alert-'.$type.' fade in">';
        $result .= '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        $result .= $content;
        $result .= '</div>';
        echo $result;
    }
    function update($new_instance, $old_instance){
        if (!isset($new_instance['submit'])) {
            return false;
        }
        $instance = $old_instance;
        $instance['content'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['content']) ) );
        $instance['type'] = esc_attr($new_instance['type']);
        return $instance;
    }
    function form($instance){
        global $wpdb;
        $instance = wp_parse_args((array) $instance, array('content'=>'','type'=>'success'));
        $content =  esc_textarea($instance['content']);
        $type =  esc_attr($instance['type']);
        //echo $content . $type ."111";
        ?>
        <p>
            <label for='<?php echo $this->get_field_id("content");?>'>内容：
            <textarea  class='widefat' name='<?php echo $this->get_field_name("content");?>' id='<?php echo $this->get_field_id("content");?>'><?php echo $content;?></textarea>
            </label>
        </p>
        <p>
            <label for='<?php echo $this->get_field_id("type");?>'>类型：
                <select name="<?php echo $this->get_field_name("type");?>" id='<?php echo $this->get_field_id("type");?>'>
                    <option value="success" <?php echo ($type == 'success') ? 'selected' : ''; ?>>Success</option>
                    <option value="info" <?php echo ($type == 'info') ? 'selected' : ''; ?>>Info</option>
                    <option value="warning" <?php echo ($type == 'warning') ? 'selected' : ''; ?>>Warning</option>
                    <option value="danger" <?php echo ($type == 'danger') ? 'selected' : ''; ?>>Danger</option>
                </select>
            </label>
        </p>
        <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
        <?php
    }
}
//最近评论小工具
class dayu_widget_recent_comments extends WP_Widget {
    function __construct(){
        $widget_ops = array(
            'description' => '最新评论'
        );
        parent::__construct('dayu_widget_recent_comments' ,'dayu-最新评论', $widget_ops);
    }

    function widget($args, $instance){
        extract($args);
        $result = '';
        $title = $instance['title'] ? esc_attr($instance['title']) : '';
        $title = apply_filters('widget_title',$title);
        //echo $title;
        $number = (!empty($instance['number'])) ? intval($instance['number']) : 5;

        $result .= $before_widget;
        if($title) $result .= $before_title . '<i class="fa fa-comments-o"></i> ' .$title . $after_title;
        $result .= '<ul class="list-group visible-lg">';
        $result .= dayu_latest_comments_list($number, 40, 16);
        $result .= '</ul>';
        $result .= '<ul class="list-group visible-md">';
        $result .= dayu_latest_comments_list($number, 40, 12);
        $result .= '</ul>';
        $result .= $after_widget;
        echo $result;
    }

    function update($new_instance, $old_instance){
        if (!isset($new_instance['submit'])) {
            return false;
        }
        $instance = $old_instance;
        $instance['title'] = esc_attr($new_instance['title']);
        $instance['number'] = intval($new_instance['number']);
        return $instance;
    }

    function form($instance){
        global $wpdb;
        $instance = wp_parse_args((array) $instance, array('title'=>'最新评论','number'=>'5'));
        $title =  esc_attr($instance['title']);
        $number = intval($instance['number']);
        ?>
        <p>
            <label for='<?php echo $this->get_field_id("title");?>'>标题：<input type='text' class='widefat' name='<?php echo $this->get_field_name("title");?>' id='<?php echo $this->get_field_id("title");?>' value="<?php echo $title;?>"/></label>
        </p>
        <p>
            <label for='<?php echo $this->get_field_id("number");?>'>显示数量(默认显示5条)：<input type='text' name='<?php echo $this->get_field_name("number");?>' id='<?php echo $this->get_field_id("number");?>' value="<?php echo $number;?>"/></label>
        </p>
        <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
        <?php
    }
}
//标签小工具，该小工具比wordpress默认的'标签云'帅多了！！
class dayu_widget_tags extends WP_Widget {
    function __construct(){
        $widget_ops = array(
            'description' => '标签'
        );
        parent::__construct('dayu_widget_tags' ,'dayu-标签', $widget_ops);
    }

    function widget($args, $instance){
        extract($args);
        $result = '';

        $title = $instance['title'] ? esc_attr($instance['title']) : '';
        $title = apply_filters('widget_title',$title);
        //echo $title;
        $number = (!empty($instance['number'])) ? intval($instance['number']) : 50;
		$orderby = (!empty($instance['orderby'])) ? esc_attr($instance['orderby']) : 'count';
		$order = (!empty($instance['order'])) ? esc_attr($instance['order']) : 'DESC';

		$tags = wp_tag_cloud( array(
                    'unit' => 'px',
                    'smallest' => 14,
                    'largest' => 14,
                    'number' => $number,
                    'format' => 'flat',
                    'orderby' => $orderby,
                    'order' => $order,
					'echo' => FALSE
                )
            );

        $result .= $before_widget;
        if($title) $result .= $before_title . '<i class="fa fa-tags"></i> ' .$title . $after_title;
        $result .= '<div class="tag_clouds">';
        $result .= $tags;
        $result .= '</div>';
        $result .= $after_widget;
        echo $result;
    }

    function update($new_instance, $old_instance){
        if (!isset($new_instance['submit'])) {
            return false;
        }
        $instance = $old_instance;
		$instance['title'] = esc_attr($new_instance['title']);
		$instance['number'] = intval($new_instance['number']);
        $instance['orderby'] = esc_attr($new_instance['orderby']);
		$instance['order'] = esc_attr($new_instance['order']);
        return $instance;
    }

    function form($instance){
        global $wpdb;
        $instance = wp_parse_args((array) $instance, array('title'=>'标签云','number'=>'50','orderby'=>'count','order'=>'DESC'));
		$title =  esc_attr($instance['title']);
        $number = intval($instance['number']);
        $orderby =  esc_attr($instance['orderby']);
		$order =  esc_attr($instance['order']);
        //echo $content . $type ."111";
        ?>
		<p>
            <label for='<?php echo $this->get_field_id("title");?>'>标题：<input type='text' class='widefat' name='<?php echo $this->get_field_name("title");?>' id='<?php echo $this->get_field_id("title");?>' value="<?php echo $title;?>"/></label>
        </p>
        <p>
            <label for='<?php echo $this->get_field_id("number");?>'>显示数量(默认显示50条)：<input type='text' name='<?php echo $this->get_field_name("number");?>' id='<?php echo $this->get_field_id("number");?>' value="<?php echo $number;?>"/></label>
        </p>
        <p>
            <label for='<?php echo $this->get_field_id("orderby");?>'>排序：
                <select name="<?php echo $this->get_field_name("orderby");?>" id='<?php echo $this->get_field_id("orderby");?>'>
                    <option value="count" <?php echo ($orderby == 'count') ? 'selected' : ''; ?>>数量</option>
                    <option value="name" <?php echo ($orderby == 'name') ? 'selected' : ''; ?>>名字</option>
                </select>
            </label>
        </p>
		<p>
            <label for='<?php echo $this->get_field_id("order");?>'>排序方式：
                <select name="<?php echo $this->get_field_name("order");?>" id='<?php echo $this->get_field_id("order");?>'>
                    <option value="DESC" <?php echo ($order == 'DESC') ? 'selected' : ''; ?>>降序</option>
                    <option value="ASC" <?php echo ($order == 'ASC') ? 'selected' : ''; ?>>升序</option>
					<option value="RAND" <?php echo ($order == 'RAND') ? 'selected' : ''; ?>>随机</option>
                </select>
            </label>
        </p>
        <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
        <?php
    }
}

//热门文章、最新文章、随机文章
class dayu_widget_posts extends WP_Widget{
    function __construct(){
        $widget_ops = array('description'=>'热门文章、最新文章、随机文章');
        parent::__construct('dayu_widget_posts' ,'dayu-文章', $widget_ops);
    }
    function widget($args, $instance){
        extract($args);
        $result = '';
        //echo $title;
        $number = (!empty($instance['number'])) ? intval($instance['number']) : 5;
        ?>
        <div class="widget widget-posts">
            <ul id="myTab" class="nav nav-tabs nav-justified visible-lg">
                <li class="active"><a href="#hot" data-toggle="tab"><h2><i class="fa fa-fire"></i> 热点文章</h2></a></li>
                <li><a href="#newest" data-toggle="tab"><h2><i class="fa fa-refresh fa-spin"></i> 最新文章</h2></a></li>
                <li><a href="#rand" data-toggle="tab"><h2><i class="fa fa-random"></i> 随机文章</h2></a></li>
            </ul>
            <ul id="myTab" class="nav nav-tabs nav-justified visible-md">
                <li class="active"><a href="#hot" data-toggle="tab"><h2><i class="fa fa-fire"></i> 热点</h2></a></li>
                <li><a href="#newest" data-toggle="tab"><h2><i class="fa fa-refresh fa-spin"></i> 最新</h2></a></li>
                <li><a href="#rand" data-toggle="tab"><h2><i class="fa fa-random"></i> 随机</h2></a></li>
            </ul>
            <div id="myTabContent" class="tab-content">
                <!--最热文章-->
                <div class="tab-pane fade in active" id="hot">
                    <ul class="list-group">
                        <?php if(function_exists('most_comm_posts')) most_comm_posts(60, $number); ?>
                    </ul>
                </div>
                <!--最新文章-->
                <div class="tab-pane fade" id="newest">
                    <ul class="list-group">
                        <?php $myposts = get_posts('numberposts='.$number.' & offset=0'); foreach($myposts as $post) : ?>
                            <!--最新文章标题列表-->
                            <a class="list-group-item visible-lg" title="<?php echo $post->post_title;?>" href="<?php echo get_permalink($post->ID); ?>" rel="bookmark">
                                <?php echo dayu_string_cut(strip_tags($post->post_title), 18); ?>
                                <i class="fa fa-comment badge"> <?php echo $post->comment_count; ?></i>
                            </a>
                            <!--最新文章评论-->
                            <a class="list-group-item visible-md" title="<?php echo $post->post_title;?>" href="<?php echo get_permalink($post->ID); ?>" rel="bookmark">
                                <?php echo dayu_string_cut(strip_tags($post->post_title), 12); ?>
                                <i class="fa fa-comment badge"> <?php echo $post->comment_count; ?></i>
                            </a>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <!--随机文章-->
                <div class="tab-pane fade" id="rand">
                    <ul class="list-group">
                        <?php $myposts = get_posts('numberposts='.$number.' & offset=0 & orderby=rand');foreach($myposts as $post) :?>
                            <a class="list-group-item visible-lg" title="<?php echo $post->post_title;?>" href="<?php echo get_permalink($post->ID); ?>" rel="bookmark">
                                <?php echo dayu_string_cut(strip_tags($post->post_title), 18); ?>
                                <i class="fa fa-comment badge"> <?php echo $post->comment_count; ?></i>
                            </a>
                            <a class="list-group-item visible-md" title="<?php echo $post->post_title;?>" href="<?php echo get_permalink($post->ID); ?>" rel="bookmark">
                                <?php echo dayu_string_cut(strip_tags($post->post_title), 12); ?>
                                <i class="fa fa-comment badge"> <?php echo $post->comment_count; ?></i>
                            </a>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }
    function update($new_instance, $old_instance){
        if (!isset($new_instance['submit'])) {
            return false;
        }
        $instance = $old_instance;
        $instance['number'] = intval($new_instance['number']);
        return $instance;
    }

    function form($instance){
        global $wpdb;
        $instance = wp_parse_args((array) $instance, array('number'=>'5'));
        $number = intval($instance['number']);
        ?>

        <p>
            <label for='<?php echo $this->get_field_id("number");?>'>显示数量(默认显示5条)：<input type='text' name='<?php echo $this->get_field_name("number");?>' id='<?php echo $this->get_field_id("number");?>' value="<?php echo $number;?>"/></label>
        </p>
        <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
        <?php
    }
}
//每日推荐
class dayu_recommend extends WP_Widget {
    function __construct(){
        $widget_ops = array(
			'name'        => 'dayu-每日推荐',
			'description' => 'dayu 每日推荐'
		);
		parent::WP_Widget( false, false, $widget_ops );
    }
    function widget($args, $instance){
        extract($args);
        $result = '';
        $title = $instance['title'] ? esc_attr($instance['title']) : '';
        $title = apply_filters('widget_title',$title);
		$desc = $instance['desc'] ? esc_attr($instance['desc']) : '';
        $aurl = $instance['aurl'] ? esc_attr($instance['aurl']) : '';
		$imgurl = $instance['imgurl'] ? esc_attr($instance['imgurl']) : '';

        $result .= $before_widget;
        if($title) $result .= $before_title . '<i class="fa fa-share-alt"></i> ' .$title . $after_title;
		$result .= '<div class="textwidget">';
		if($aurl) $result .= '<a href="'.$aurl.'" target="_blank">';
		if($imgurl) $result .= '<img src="'.$imgurl.'">';
		if($desc) $result .= '<h3 class="text-center">'.$desc.'</h3>';
		if($aurl) $result .= "</a>";
		$result .= '</div>';
        $result .= $after_widget;
        echo $result;
    }
    function update($new_instance, $old_instance){
        return $new_instance;
    }
    function form($instance){
        global $wpdb;
        $instance = wp_parse_args((array) $instance, array('title'=>'','desc'=>'','aurl'=>'','imgurl'=>''));
        $title =  esc_attr($instance['title']);
		$desc =  esc_attr($instance['desc']);
		$aurl = esc_attr( $instance['aurl'] );
		$imgurl = esc_attr( $instance['imgurl'] );
        ?>
        <p>
            <label for='<?php echo $this->get_field_id("title");?>'>标题：<input type='text' class='widefat' name='<?php echo $this->get_field_name("title");?>' id='<?php echo $this->get_field_id("title");?>' value="<?php echo $title;?>"/></label>
        </p>
		<p>
            <label for='<?php echo $this->get_field_id("desc");?>'>描述：<input type='text' class='widefat' name='<?php echo $this->get_field_name("desc");?>' id='<?php echo $this->get_field_id("desc");?>' value="<?php echo $desc;?>"/></label>
        </p>
        <p>
			<label for="<?php echo $this->get_field_id( 'aurl' ); ?>">
				跳转链接：
				<input class="widefat" id="<?php echo $this->get_field_id( 'aurl' ); ?>" name="<?php echo $this->get_field_name( 'aurl' ); ?>" type="text" value="<?php echo $aurl; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'imgurl' ); ?>">
				图片路径：
				<input class="widefat" id="<?php echo $this->get_field_id( 'imgurl' ); ?>" name="<?php echo $this->get_field_name( 'imgurl' ); ?>" type="text" value="<?php echo $imgurl; ?>" />
			</label>
		</p>
        <?php
    }
}
class dayu_tj extends WP_Widget {

	// 设定小工具信息，统计有多少篇文章、页面、分类等，运行多少天
	function __construct() {
		$widget_opts = array(
			  'name'        => 'dayu-站点统计',
			  'description' => 'dayu 站点统计小工具'
		);
		parent::WP_Widget( false, false, $widget_opts );
	}

	// 设定小工具结构
	function widget( $args, $instance ) {
		global $wpdb;
		extract( $args );
		$title = $instance['title'] ? esc_attr($instance['title']) : '';
		$date = $instance['date'] ? esc_attr($instance['date']) : '';
		echo $before_widget;
		if($title) echo $before_title . '<i class="fa fa-sitemap"></i> ' . $title . $after_title;
		?>
		<ul class="list-group">
			<li class="list-group-item">分类：<?php echo $count_categories = wp_count_terms('category');?>个</li>
			<li class="list-group-item">运行：<?php echo floor((time()-strtotime($date))/86400); ?>天</li>
			<li class="list-group-item">文章：<?php $count_posts = wp_count_posts();echo $published_posts = $count_posts->publish;?>个</li>
			<li class="list-group-item">页面：<?php $count_pages = wp_count_posts('page');echo $page_posts = $count_pages->publish;?>个</li>
			<li class="list-group-item">评论：<?php $count_comments = get_comment_count();echo $count_comments['approved'];?>个</li>
			<li class="list-group-item">标签：<?php echo $count_tags = wp_count_terms('post_tag');?>个</li>
		</ul>

		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) {
		global $wpdb;
        $instance = wp_parse_args((array) $instance, array('title'=>'','date'=>''));
        $title =  esc_attr($instance['title']);
		$date=  esc_attr($instance['date']);
		?>
		<p>
			<label for='<?php echo $this->get_field_id("title");?>'>标题：<input type='text' class='widefat' name='<?php echo $this->get_field_name("title");?>' id='<?php echo $this->get_field_id("title");?>' value="<?php echo $title;?>"/></label>
		</p>
		<p>
			<label for='<?php echo $this->get_field_id("date");?>'>建站日期：<input type='text' class='widefat' name='<?php echo $this->get_field_name("date");?>' id='<?php echo $this->get_field_id("date");?>' value="<?php echo $date;?>"/></label>
		</p>
		<?php
	}
}


function dayu_register_widgets(){
	register_widget('dayu_widget_ad');  //边栏广告
    register_widget('dayu_widget_notice');  //边栏公告
    register_widget('dayu_widget_recent_comments'); //最新评论
	register_widget('dayu_widget_tags'); //标签
    register_widget('dayu_widget_posts'); //热门文章、最新文章、随机文章
	register_widget('dayu_recommend');  //每日推荐
	register_widget('dayu_tj');  //站点统计
}
add_action('widgets_init','dayu_register_widgets');
?>