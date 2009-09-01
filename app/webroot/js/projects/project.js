$(document).ready(function() {
	
	var pid = parseInt($('.project').attr('project_record'));
	
	if(pid && typeof pid == "number") {
		
		$('.title').editable("/quotas/projects/updateTitle/", {
			tooltip: 				"Click to edit project title",
			name:					"data[Project][title]",
			submitdata:				{
										"data[Project][id]":	pid
									},
			style:					"display: inline"
		});
		
		$('.edit').hover(
			function() {
				$(this).css('backgroundColor', '#FF0000');
			},
			function() {
				$(this).css('backgroundColor', '#FFFFFF');
			}
		);
		
	}
	
});