$(document).ready(function() {
	$(".selector").change(function() {
	    $("#project-listing").load(QT.WEBROOT + "/projects/index/" + $(this).val(), null, bindTrackers);
	});
	
	bindTrackers = function() {
		$(".favorite").bind('click', function() {
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
						$(_this).attr("href", (url.replace("remove", "add"))).removeClass('star').addClass('estar');
					}
					else {
						$(_this).attr("href", (url.replace("add", "remove"))).removeClass('estar').addClass('star');
					}
				}
			}
			
			return false;
		});
	};
	
	bindTrackers();
});