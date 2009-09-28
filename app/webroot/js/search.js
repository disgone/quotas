$(document).ready(function() {
	$("#search-term").focus(function() {
		$(this).removeClass('em light').val('');
	});
	
	$("#search-term").blur(function() {
		if($(this).val().trim().length < 1) {
			$(this).val('Project name or number').addClass('em light');
		}
	});
	
	$("#search-form").submit(function() {
		if($("#search-term").val() == 'Project name or number')
			$("#search-term").val('');
	});
});