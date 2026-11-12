(function ($) {
	function init() {
		setupMetaboxes();
		setupWidgetType();
		setupCodeEditor();
	}

	function setupMetaboxes() {
		const postboxContainers = document.querySelectorAll(".postbox-container");

		if (postboxContainers.length) {
			postboxContainers.forEach(function (postboxContainer) {
				postboxContainer.classList.add("heatbox-wrap");
			});
		}
	}

	function setupWidgetType() {
		const $type = $('[name="udb_widget_type"]');
		const $fields = $(".udb-main-metabox .widget-fields");
		let value = $type.val();

		$fields.find('[data-type="' + value + '"]').addClass("is-active");

		$type.on("change", function (e) {
			value = $(this).val();

			$fields.find("[data-type]").removeClass("is-active");
			$fields.find('[data-type="' + value + '"]').addClass("is-active");
		});
	}

	function setupCodeEditor() {
		const textareas = document.querySelectorAll('[name="udb_html"]');

		/** @type {WPCodeEditorSettings} */
		const editorSettings = wp.codeEditor.defaultSettings
			? _.clone(wp.codeEditor.defaultSettings)
			: { codemirror: {} };

		editorSettings.codemirror = _.extend({}, editorSettings.codemirror, {
			indentUnit: 4,
			tabSize: 4,
			mode: "html",
		});

		textareas.forEach(function (textarea) {
			if (!(textarea instanceof HTMLTextAreaElement)) return;
			wp.codeEditor.initialize(textarea, editorSettings);
		});
	}

	init();
})(jQuery);
