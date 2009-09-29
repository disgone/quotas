$(document).ready(function() {
	$("#search-term").autocomplete("/quotas/search/autosense/", {
		matchContains:true,
        minChars:2, 
        autoFill:false,
        mustMatch:false,
        max:10,
        width: 250,
        selectFirst: false,
        formatResult: formatResult
	}).result(function(event,item) {
		location.href = "/quotas/projects/details/" + item[1];
	});

	
	$("#search-term").focus(function() {
		$(this).removeClass('em light');
		if($(this).val() == 'Project name or number')
			$(this).val('');
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
	
	function formatResult(row) {
		return row[0].split(" ")[0];
	}

});