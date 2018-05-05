    </section>
</div>
    <!--wp_list_bookmarks的用法【https://www.dayuzy.com/wordpress%E4%B8%ADwp_list_bookmarks%E7%9A%84%E7%94%A8%E6%B3%95/】-->
    <!--为什么要用两个"!"，空字符串算真，空字符串一个"!"变为1，再一个"!"变为0，这里是强制转换的意思-->
	<!--所有的链接们，注意是复数，所以category参数指定的是复数-->
    <!--of_get_option('links_id')获取的是所有分类的ids(复数)，所以下面的代码会显示所有的链接-->
<?php $linkIds = of_get_option('links_id'); if(!!wp_list_bookmarks('echo=0&category='.$linkIds)){ ?>
    <div class="main-footer" id="main-footer">
        <div class="container">
            <h3>友情链接</h3>
            <ul class="list-unstyled list-inline">
                <?php wp_list_bookmarks('title_li=&categorize=0&show_images=0&category_before=&category_after=&category='.$linkIds); ?>
            </ul>
        </div>
    </div>
<?php } ?>
<footer id="body-footer">
    <div  class="container clearfix bottomcp">
        Copyright © 2018 <?php bloginfo('name'); ?> |
        <!--110公安ICP备案-->
        <?php if(get_option('zh_cn_l10n_icp_num')){ echo get_option('zh_cn_l10n_icp_num') . ' | '; } ?>
		<!--百度统计-->
        <?php $site_analytics = of_get_option('site_analytics', false); if($site_analytics){ echo (strpos($site_analytics, '<script') === false) ? '<script>'.$site_analytics.'</script> | ' : $site_analytics . ' | '; } ?>
        Theme By <a href="http://dayuzy.com" title="dayuzy' Bolg" target="_blank">dayu</a>
    </div>
    <ul id="jump" class="visible-lg">
        <li><a id="top" href="#top" title="返回顶部" style="display:none;"><i class="fa fa-arrow-circle-up"></i></a></li>
    </ul>
</footer>
    <!--参考wp_head()的用法-->
<?php wp_footer(); ?>

</body>
</html>


