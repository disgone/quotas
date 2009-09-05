$(document).ready(function() {
	$('.editable').editInPlace({
		url: '/quotas/projects/updateTitle/' + $('.project').attr('project_record'),
		show_buttons: true,
		params: "data[Project][id]=" + $('.project').attr('project_record'),
		update_value: 'data[Project][title]',
		default_text: "<em class='placeholder'>Click to edit</em>",
		value_required: true
	});
});