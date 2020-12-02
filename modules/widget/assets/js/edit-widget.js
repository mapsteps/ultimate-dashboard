(function($) {
  function init() {
    setupWidgetType();
		setupIconPicker();
		setupCodeEditor();
  }

  function setupWidgetType() {
    var $type = $('[name="udb_widget_type');
    var $fields = $(".udb-main-metabox .widget-fields");
    var value = $type.val();

    $fields.find('[data-type="' + value + '"]').addClass("is-active");

    $type.on("change", function(e) {
      value = $(this).val();

      $fields.find("[data-type]").removeClass("is-active");
      $fields.find('[data-type="' + value + '"]').addClass("is-active");
    });
  }

  function setupIconPicker() {
    var $iconPreview = $(".icon-preview");
    var $iconSelect = $('[name="udb_icon"]');

    $iconPreview.html('<i class="' + $iconSelect.val() + '"></i>');

    $iconSelect.on("change", function(e) {
      $iconPreview.html('<i class="' + $iconSelect.val() + '"></i>');
    });

		window.addEventListener('load', function () {
			$iconPreview.html('<i class="' + $iconSelect.val() + '"></i>');
		});
	}
	
	function setupCodeEditor() {
		if ($('[name="udb_html"]').length) {
			var editorSettings = wp.codeEditor.defaultSettings
				? _.clone(wp.codeEditor.defaultSettings)
				: {};
			editorSettings.codemirror = _.extend({}, editorSettings.codemirror, {
				indentUnit: 4,
				tabSize: 4,
				mode: "html"
			});
			var editor = wp.codeEditor.initialize(
				$('[name="udb_html"]'),
				editorSettings
			);
		}
	}

  init();
})(jQuery);
