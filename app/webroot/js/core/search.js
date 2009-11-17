$(document).ready(function() {
	function init() {
	}
	
	function load() {
		$('head');
	}
	
	function addSearch() {
		$("#search-term").autocomplete("/quotas/search/autosense/", {
			matchContains:true,
	        minChars:2, 
	        autoFill:false,
	        mustMatch:false,
	        max:10,
	        width: 225,
	        selectFirst: false,
	        formatResult: function formatResult(row) {
	    		return row[0].split(" ")[0];
	    	}
		}).result(function(event,item) {
			window.location = "/quotas/projects/details/" + item[1];
			//$("#search-form").submit();
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
	}
});