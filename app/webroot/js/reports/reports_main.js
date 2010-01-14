$("#server-stats").ready(function() {
	$("#server-stats").lazyloader({url:QT.WEBROOT + '/reports/server_stats'});
	$("#movers-increase tbody").lazyloader({url:QT.WEBROOT + '/reports/movers/increase'});
	$("#movers-decrease tbody").lazyloader({url:QT.WEBROOT + '/reports/movers/decrease'});
});