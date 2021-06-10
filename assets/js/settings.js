(function ($) {

	var customCSSFields = document.querySelectorAll('.udb-custom-css');
	var colorFields = document.querySelectorAll('.udb-color-field');

	if (customCSSFields.length) {
		var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};

		editorSettings.codemirror = _.extend(
			{},
			editorSettings.codemirror,
			{
				indentUnit: 4,
				tabSize: 4,
				mode: 'css',
			}
		);

		[].slice.call(customCSSFields).forEach(function (el) {
			wp.codeEditor.initialize(el, editorSettings);
		});
	}

	if (colorFields.length) {
		[].slice.call(colorFields).forEach(function (el) {
			var opts = {
				defaultColor: el.dataset.default,
				change: function (event, ui) {},
				clear: function () { },
				hide: true,
				palettes: true
			};

			$(el).wpColorPicker(opts);
		});
	}

})(jQuery);
