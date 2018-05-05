function specs_change_background_mode(mode){
	switch(mode){
		case 'image':
			jQuery('.background_image').removeClass('hidden');
			jQuery('.background_pattern, .background_color').addClass('hidden');
		break;
		case 'pattern':
			jQuery('.background_pattern').removeClass('hidden');
			jQuery('.background_color, .background_image').addClass('hidden');
		break;
		case 'color':
			jQuery('.background_color').removeClass('hidden');
			jQuery('.background_pattern, .background_image').addClass('hidden');
		break;
	}
}
jQuery(document).ready(function() {
	//点击的时候换背景
	jQuery('input[name="dayu[background_mode]"]').click(function() {
		specs_change_background_mode(jQuery(this).val());
	});
	//默认进来后台时选中的背景
	specs_change_background_mode(jQuery('input[name="dayu[background_mode]"]:checked').val());
})
