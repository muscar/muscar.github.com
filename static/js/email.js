(function($) {
	jQuery.fn.mailto = function() {
		return this.each(function() {
			var email_add = $(this).attr("href").replace(/\(.+?\)/g, "@").replace(/\[.+?\]/g, ".");
			var email_text = $(this).text();
			$(this).before('<a href="mailto:' + email_add + '" rel="nofollow">' + email_text + '</a>').remove();
		});
	};

})(jQuery);