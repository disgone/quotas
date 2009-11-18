$(document).ready(function() {
	$(".selector").change(function() {
	    $("#project-listing").load(QT.WEBROOT + "/projects/index/" + $(this).val());
	});
});