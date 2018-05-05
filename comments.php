<?php
/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">
	<?php if ( have_comments() ) { ?>
		<h2 class="comments-title">
			<?php
				printf( '本文共 %1$s 个回复',
					number_format_i18n( get_comments_number() ));
			?>
		</h2>
		<div id="commentshow">
			<ul class="commentlist list-unstyled">
				<!--自定义回掉函数【http://www.shouce.ren/api/view/a/10553】【http://www.511yj.com/wp-ist-comments.html】-->
				<!--理解WordPress的PingBack和TrackBack【http://www.maixj.net/wz/lijie-pingback-trackback-572】-->
				<!--wp_list_comments函数中的short_ping参数【http://www.maixj.net/wz/wp-list-comments-short-ping-9028】-->
				<?php wp_list_comments( 'type=comment&callback=dayu_comment&avatar_size=50&max_depth=10000' ); ?>
			</ul>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
				<p class="commentnav text-center" data-post-id="<?php echo $post->ID?>">
					<?php paginate_comments_links('prev_text=«&next_text=»');?>
				</p>
			<?php } ?>
		</div>
	<?php } ?>
	<!--评论功能是否开启,这里面有两个控制开启评论的地方，一个是"写文章"页面，一个是"设置->讨论"-->
	<!--权限大小："写文章"大于"设置->讨论"-->
	<!--下面这个comments_open控制的是文章页的那个-->
	<?php if ( !comments_open() ) { ?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert"
						aria-hidden="true">
					&times;
				</button>
				Sorry！评论功能已经关闭。
			</div>
	<?php }else{ ?>
		
		<?php //include(TEMPLATEPATH . '/smiley.php');//引入表情?>
		<?php
		$args = array(
			'title_reply'       => '发表评论',
			'title_reply_to'    => '回复 %s',
			'cancel_reply_link' => '取消',
			'label_submit'      => '发表评论',
			'fields' => apply_filters( 'comment_form_default_fields', array(
					'author' =>
						'<div class="comment-form-author form-group has-feedback">'.
						'<div class="input-group">'.
						'<div class="input-group-addon"><i class="fa fa-user"></i></div>'.
						'<input class="form-control" placeholder="昵称" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
						'" size="30"' . $aria_req .
						( $req ? ' required /><span class="form-control-feedback required">*</span>' : ' />' ) .
						'</span></div></div>',
					'email' =>
						'<div class="comment-form-email form-group has-feedback">'.
						'<div class="input-group">'.
						'<div class="input-group-addon"><i class="fa fa-envelope-o"></i></div>'.
						'<input class="form-control" placeholder="邮箱" id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
						'" size="30"' . $aria_req .
						( $req ? ' required /><span class="form-control-feedback required">*</span>' : ' />' ) .
						'</span></div></div>',
					//评论的url，默认是评论者的url，你可以写自己的网站如'www.baidu.com'
					//				'url' =>
					//					'<div class="comment-form-url form-group has-feedback">'.
					//					'<div class="input-group">'.
					//					'<div class="input-group-addon"><i class="fa fa-link"></i></div>'.
					//					'<input class="form-control" placeholder="网址" id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
					//					'" size="30" />' .
					//					'</div></div>'
				)
			),
			'comment_field' =>  '<div class="comment-form-comment">'.
				'<textarea class="form-control" id="comment" placeholder="评论写的diao一点，人生才会完美~" name="comment" rows="5" aria-required="true" required  onkeydown="if(event.ctrlKey){if(event.keyCode==13){document.getElementById(\'submit\').click();return false}};">' .
				'</textarea><p>'.$smilies.'</p></div>',
			'comment_notes_before' => '',
			'comment_notes_after' => ''
		);
		comment_form($args);
		?>
	<?php }//关闭else ?>
</div>
