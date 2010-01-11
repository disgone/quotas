$(document).ready(function() {
	$('.editable').editInPlace({
		url: QT.WEBROOT + '/projects/updateTitle/' + $('.project').attr('project_record'),
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
		var _this = this;
		
		$.getJSON(
			url + ".json",
			null,
			completed
		);
		
		function completed(data) {
			if(data.success == true) {
				if(url.indexOf("remove") >= 0) {
					$(_this).text("Add to My Projects").attr("href", (url.replace("remove", "add"))).removeClass('star').addClass('estar');
				}
				else {
					$(_this).text("Remove My Projects").attr("href", (url.replace("add", "remove"))).removeClass('estar').addClass('star');
				}
			}
		}
		
		return false;
	});
	
});