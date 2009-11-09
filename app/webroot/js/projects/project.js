$(document).ready(function() {
	$('.editable').editInPlace({
		url: '/quotas/projects/updateTitle/' + $('.project').attr('project_record'),
		show_buttons: true,
		params: "data[Project][id]=" + $('.project').attr('project_record'),
		update_value: 'data[Project][title]',
		default_text: "<em class='placeholder'>Click to edit</em>",
		value_required: false,
		cancel_button: "<button class='sub_btn inplace_cancel'>Cancel</button>",
		save_button: "<input type='submit' class='sub_btn' value='Save' />"
	});
	
	$(".fav").bind('click', function() {
		var id = $(this).attr('rel');
		var url = $(this).attr('href');
		var arguments = url.split(/\/{1,2}/);
		
		$.ajax({
			'url': url
		});
		
		if(arguments[arguments.length-1] == "remove") {
			$(this).text("Add to My Projects").attr("href", (url.replace("remove", "add"))).removeClass('star').addClass('estar');
		}
		else {
			$(this).text("Remove My Projects").attr("href", (url.replace("add", "remove"))).removeClass('estar').addClass('star');
		}
		
		return false;
	});
	
});