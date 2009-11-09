$(document).ready(function() {
	$(".selector").change(function() {
	    $("#project-listing").load("/quotas/projects/index/" + $(this).val());
	});
});