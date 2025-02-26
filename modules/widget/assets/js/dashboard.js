(function ($) {
	$(".postbox-container .inside").each(function () {
		if ($(this).children().hasClass("udb-content-wrapper")) {
			$(this).parent().addClass("udb-content");

			const widgetHeight = $(this).children().attr("data-udb-content-height");

			$(this)
				.children()
				.height(widgetHeight ?? 100);
		}
	});
})(jQuery);
