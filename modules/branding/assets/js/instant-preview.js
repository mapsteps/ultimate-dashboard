(function ($) {
	var colorFields = document.querySelectorAll('.udb-color-field');

	function init() {
		if (!colorFields.length) return;

		colorFields.forEach(function (el) {
			var opts = {
				defaultColor: el.dataset.default,
				change: function (event, ui) {
					onTriggerChange(el);
				},
				clear: function () { },
				hide: true,
				palettes: true
			};

			$(el).wpColorPicker(opts);
		});
	}

	function onTriggerChange(el) {
		console.log(el.dataset.udbTriggerName);
	}

	init();

})(jQuery);
