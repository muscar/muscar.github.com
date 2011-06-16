// JavaScript Document

(function($) {
	$.fn.syncViewport = function(settings) {
		var body_height = 0;
		var browser_id = 0;
		var property = [['min-height','0px'],
						['height','1%']];

		// check for IE6 ...
		if($.browser.msie && $.browser.version < 7){
			browser_id = 1;
		}
		
		// get maximum element height ...
		body_height=$('html')[0].clientHeight;
		
		// set synchronized element height ...
 		$(this).each(function() {
  			$(this).css(property[browser_id][0],body_height+'px');
		});
		return this;
	};	
})(jQuery);
