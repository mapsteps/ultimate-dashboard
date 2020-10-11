// Let's quickly check if this doesn't contain unnecessary stuff from somewhere else.

(function ($) {
	/**
	 * Call main functions.
	 */
	function init() {
		setupMetaboxes();
		setupChainingFields();
		setupIconPicker();
		setupWidgetRoles();
		setupCSSEditor();
		setupJSEditor();
		setupHTMLEditor();
		setupContentType();
	}

	/**
	 * Setup metaboxes
	 */
	function setupMetaboxes() {
		var postboxes = document.querySelectorAll('.postbox');
		if (!postboxes) return;

		[].slice.call(postboxes).forEach(function (postbox) {
			var postboxContent = postbox.querySelector('.postbox-content.has-lines');
			if (!postboxContent) return;

			postboxContent.parentNode.parentNode.classList.add('has-lines');
			postboxContent.classList.remove('has-lines');
		});
	}

	/**
	 * Setup fields chaining/ dependency.
	 */
	function setupChainingFields() {
		var children = document.querySelectorAll('[data-show-if-field]');
		if (!children.length) return;

		[].slice.call(children).forEach(function (child) {
			setupChainingEvent(child);
		});
	}

	/**
	 * Setup fields chaining event.
	 *
	 * @param {HTMLElement} child The children element.
	 */
	function setupChainingEvent(child) {
		var parentName = child.dataset.showIfField;
		var parentField = document.querySelector('#' + parentName);

		checkChainingState(child, parentField);

		parentField.addEventListener('change', function (e) {
			checkChainingState(child, parentField);
		});
	}

	/**
	 * Check the children state: shown or hidden.
	 * 
	 * @param {HTMLElement} child The children element.
	 * @param {HTMLElement} parent The parent/ dependency element.
	 */
	function checkChainingState(child, parent) {
		var wantedValue = child.dataset.showIfValue;
		var parentValue;

		if (parent.tagName.toLocaleLowerCase() === 'select') {
			parentValue = parent.options[parent.selectedIndex].value;
		} else {
			parentValue = parent.value;
		}

		if (parentValue === wantedValue) {
			child.style.display = 'block';
		} else {
			child.style.display = 'none';
		}
	}

	/**
	 * Setup icon picker (Dashicons & FontAwesome)
	 */
	function setupIconPicker() {
		var $iconPreview = $(".icon-preview");
		var $iconSelect = $('[name="udb_menu_icon"]');

		$iconPreview.html('<i class="' + $iconSelect.val() + '"></i>');

		$iconSelect.on("change", function (e) {
			$iconPreview.html('<i class="' + $iconSelect.val() + '"></i>');
		});

		window.addEventListener('load', function () {
			$iconPreview.html('<i class="' + $iconSelect.val() + '"></i>');
		});
	}

	/**
	 * Setup widget roles.
	 */
	function setupWidgetRoles() {
		var fields = document.querySelectorAll('.udb-widget-roles-field');
		if (!fields.length) return;

		fields.forEach(function (field) {
			setupWidgetRole(field);
		});
	}

	/**
	 * Setup widget role.
	 *
	 * @param HTMLElement field The widget role's select box.
	 */
	function setupWidgetRole(field) {
		var $field = $(field);

		$field.select2();

		$field.on('select2:select', function (e) {
			var selections = $field.select2('data');
			var values = [];

			if (e.params.data.id === 'all') {
				$field.val('all');
				$field.trigger('change');
			} else {
				if (selections.length) {
					selections.forEach(function (role) {
						if (role.id !== 'all') {
							values.push(role.id);
						}
					});

					$field.val(values);
					$field.trigger('change');
				}
			}
		});
	}

	/**
	 * Setup CSS fields.
	 */
	function setupCSSEditor() {
		var fields = document.querySelectorAll('.udb-custom-css');
		if (!fields.length) return;

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

		[].slice.call(fields).forEach(function (field) {
			wp.codeEditor.initialize(field, editorSettings);
		});
	}

	/**
	 * Setup JS fields.
	 */
	function setupJSEditor() {
		var fields = document.querySelectorAll('.udb-custom-js');
		if (!fields.length) return;

		var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};

		editorSettings.codemirror = _.extend(
			{},
			editorSettings.codemirror,
			{
				indentUnit: 4,
				tabSize: 4,
				mode: 'js',
			}
		);

		[].slice.call(fields).forEach(function (field) {
			wp.codeEditor.initialize(field, editorSettings);
		});
	}

	/**
	 * Setup HTML fields.
	 */
	function setupHTMLEditor() {
		var fields = document.querySelectorAll('.udb-html-editor');
		if (!fields.length) return;

		var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};

		editorSettings.codemirror = _.extend(
			{},
			editorSettings.codemirror,
			{
				indentUnit: 4,
				tabSize: 4,
				mode: 'html',
			}
		);

		[].slice.call(fields).forEach(function (field) {
			wp.codeEditor.initialize(field, editorSettings);
		});
	}

	/**
	 * Setup content type.
	 */
	function setupContentType() {
		var select = document.querySelector('[name="udb_content_type"]');
		if (!select) return;

		contentTypeSwitch(select.options[select.selectedIndex].value);

		select.addEventListener('change', function () {
			contentTypeSwitch(this.options[this.selectedIndex].value);
		});
	}

	/**
	 * Content type switch.
	 *
	 * @param {string} value The content type value.
	 */
	function contentTypeSwitch(value) {
		var htmlEditor = document.querySelector('#udb-html-metabox');
		var elementorSwitch = document.querySelector('#elementor-switch-mode');
		var elementorEditor = document.querySelector('#elementor-editor');
		var brizyButtons = document.querySelector('#post-body-content > .brizy-buttons');
		var beaverTabs = document.querySelector('#post-body-content > .fl-builder-admin');
		var diviButtons = document.querySelector('#post-body-content > .et_pb_toggle_builder_wrapper');
		var diviEditor = document.querySelector('#et_pb_layout');
		var normalEditor = document.querySelector('#postdivrich');

		switch (value) {
			case 'html':
				htmlEditor.style.display = 'block';

				if (elementorSwitch) elementorSwitch.style.display = 'none';
				if (!document.body.classList.contains('elementor-editor-inactive')) {
					if (elementorEditor) elementorEditor.style.display = 'none';
				}
				if (brizyButtons) brizyButtons.style.display = 'none';
				if (beaverTabs) beaverTabs.style.display = 'none';
				if (diviButtons) diviButtons.style.display = 'none';
				if (diviEditor) diviEditor.style.display = 'none';
				if (
					!document.body.classList.contains('fl-builder-enabled') &&
					!document.querySelector('#postdivrich').parentNode.classList.contains('et_pb_post_body_hidden')
				) {
					normalEditor.style.display = 'none';
				}
				break;

			default:
				htmlEditor.style.display = 'none';

				if (elementorSwitch) elementorSwitch.style.display = 'block';
				if (!document.body.classList.contains('elementor-editor-inactive')) {
					if (elementorEditor) elementorEditor.style.display = 'block';
				}
				if (brizyButtons) brizyButtons.style.display = 'block';
				if (beaverTabs) beaverTabs.style.display = 'block';
				if (diviButtons) diviButtons.style.display = 'block';
				if (diviEditor) diviEditor.style.display = 'block';
				if (
					!document.body.classList.contains('fl-builder-enabled') &&
					!document.querySelector('#postdivrich').parentNode.classList.contains('et_pb_post_body_hidden')
				) {
					normalEditor.style.display = 'block';
				}
		}
	}

	init();
})(jQuery);