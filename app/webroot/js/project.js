var PQ = function() {
	return {
		update: function(id, dur) {
			flashMovie.setSettings("<settings><data_sets><data_set did='0'><file_name>/quotas/projects/projectData/" + id + ".csv?period=" + dur + "</file_name></data_set></data_sets></settings>", true);
			$.getJSON('/quotas/projects/update/' + id + '/period:' + dur, function(data) {
				console.log(data);
			});
		}
	}
}();

$(document).ready(function() {
	$(".stats-controls li a").click(function() {
		$(".stats-controls li a").removeClass('selected');
		$(this).addClass("selected");
		PQ.update(69, $(this).text());
		return false;
	});
});


var flashMovie;

function amChartInited(chart_id){
    flashMovie = document.getElementById(chart_id);
}