/**
 * 文章点赞异步
 * 要求：1.single.php前端点赞代码
 * 		 2.dayu.js存在异步点赞js代码
 * 		 3.根据发送的ajax_data里面的action属性，需要在functions.php添加"wp_ajax_nopriv_action_name"和"wp_ajax_action_name"钩子
 *		 4.这个两个钩子的后缀必须和action属性名一致，两个钩子分别是判断用户是否登陆，如果未登陆执行
 *		 "wp_ajax_nopriv_dayu_zan"，否则执行"wp_ajax_dayu_zan"
 *		 5.可以参考【/wp-admin/admin-ajax.php】源码或者黄聪的wordpress插件开发的最后一讲
 */
$(function(){
	//"$.fn.函数名"是进行定义一个像"hasClass"这样的全局函数
	$.fn.postLike = function() {
		if ($(this).hasClass('done')) {
			//如果有'done'这个类，说明已经点过赞了
			return false;
		} else {
			//获取前端点赞标签"data-id"属性值
			var id = $(this).data("id"),
				action = $(this).data('action'),
				//获取点赞数量标签
				rateHolder = $(this).children('.count'),
				that = this;
			var ajax_data = {
				action: "dayu_zan",
				um_id: id,
				um_action: action
			};
			$.post("/wp-admin/admin-ajax.php", ajax_data,
				function(response) {
					if (response.status == 200) {
						$(that).addClass('done');
						$(that).attr('data-original-title', '您已赞过该文章');
						$(rateHolder).html(response.data);
					}
				},'json');
			return false;
		}
	};
	if (jQuery(window).width() > 768) {
		$("a").tooltip();
		dropDown();
	}
	//导航二级菜单
	function dropDown() {
		var dropDownLi = jQuery('li.dropdown');

		dropDownLi.mouseover(function() {
			jQuery(this).addClass('open');
		}).mouseout(function() {
			jQuery(this).removeClass('open');
		});
	}
	$("#top").click(function() {
		$('body,html').animate({
			scrollTop: 0
		},
		1000);
		return false;
	});
	$('.magnific').magnificPopup({
		type: 'image',
		gallery:{
			enabled:true
		}
		// other options
	});

	//微信二维码
    $("#weixin").mouseover(function(){
        document.getElementById("EWM").style.display = 'block';
    })
    $("#weixin").mouseout(function(){
        document.getElementById("EWM").style.display = 'none';
    })

	//警告框链接加样式
	$(".alert").children("p").children("a").addClass("alert-link");
	$(".alert").children("a").addClass("alert-link");

	//侧边栏分类目录
	$("#widget-cats li").addClass("list-group-item");

	$("select").addClass("form-control");
	$("#commentform #submit").addClass('btn btn-danger btn-block');
	if ($("#main").height() > $("#sidebar").height()) {
		var footerHeight = 0;
		if ($('#main-footer').length > 0) {
			footerHeight = $('#main-footer').outerHeight(true);
		}
		$('#sidebar').affix({
			offset: {
				top: $('#sidebar').offset().top - 65,
				bottom: $('footer#body-footer').outerHeight(true) + footerHeight
			}
		});
	}
});
//点赞功能调用
$(document).on("click", ".dayuZan", function() {
	//在异步发送过程中，网页导航栏下面会有一个请求条
	$('body').addClass('is-loading');
	$(this).postLike();
});
//返回顶部
$(window).scroll(function() {
	if ($(window).scrollTop() > 100) {
		$('#top').show();
	} else {
		$('#top').hide();
	}
});