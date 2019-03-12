(function($) {

	$('.postbox-container .inside').each(function() {

		if($(this).children().hasClass('udb-content-wrapper')) {
			$(this).parent().addClass('udb-content');

			var widgetHeight = $(this).children().attr('data-udb-content-height');
			$(this).children().height(widgetHeight);

		}

	});

})( jQuery );