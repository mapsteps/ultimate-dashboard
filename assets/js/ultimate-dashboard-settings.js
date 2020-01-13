(function ($) {
	var customCSSFields = document.querySelectorAll('.udb-custom-css');

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
})(jQuery);