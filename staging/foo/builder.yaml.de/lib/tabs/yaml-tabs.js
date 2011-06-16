/**
 * YAML Tabs - jQuery plugin for accessible, unobtrusive tabs
 * Made to seemlessly work with the CCS-Framework YAML (yaml.de)
 * @requires jQuery v1.0.3
 *
 * http://blog.ginader.de/dev/yamltabs/
 *
 * Copyright (c) 2007 Dirk Ginader (ginader.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * Version: 1.0
 */
(function($) {
	$.fn.yamltabs = function(settings) {
		settings = $.extend($.extend({}, arguments.callee.defaults), settings || {});		
		$(this).each(function() {
			this.yamltabsSettings = settings;
			buildYamlTabsList(this);
		});
		return this;
	};	
	$.fn.yamltabs.defaults = {
		wrapperClass: "content", // Classname to apply to the div that is wrapped around the original Markup
		tabhead: "h3", // Tag or valid Query Selector of the Elements to Transform the Tabs-Navigation of (original are removed)
		tabbody: "form", // Tag or valid Query Selector of the Elements to be treated as the Tab Body
		fx:"show", // can be "fadeIn", "slideDown", "show"
		fxspeed: null // speed (String|Number): "slow", "normal", or "fast") or the number of milliseconds to run the animation
	};	
	buildYamlTabsList = function(o){
		var s = o.yamltabsSettings;
		var list = "";
		$(o).html("<div class=\""+s.wrapperClass+"\">"+$(o).html()+"</div>");
		$(o).find(s.tabhead).each(function(){
			list+= "<li><a href=\"#\">"+$(this).text()+"</a></li>\n";
			$(this).hide();
		});
		$(o).prepend("<ul>"+list+"</ul>");
		$(o).find(s.tabbody).hide();
		$(o).find(s.tabbody+":first").show();
		$(o).find("ul>li:first").addClass("current");		
		$(o).find("ul>li>a").each(function(i){
			$(this).click(function(event){
				$(o).find("ul>li.current").removeClass("current");
				$(this).blur().parent().addClass("current");
				$(o).find(s.tabbody+":visible").hide();
				$(o).find(s.tabbody).eq(i)[s.fx](s.fxspeed);
				event.preventDefault();
			});
		});
	};
	$.fn.yamltabsDestroy = function (o){
		var s = o.yamltabsSettings;
		
		
	};
})(jQuery);