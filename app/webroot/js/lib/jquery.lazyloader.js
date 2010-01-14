$.fn.lazyloader = function(options) {
	var $this = $(this);
	$.get(options.url, false, function(data) {
		$this.empty().hide().append(data).fadeIn();
	});
};