(function ($) {
	/**
	 * Call main functions.
	 */
	function init() {
		setupMetaboxes();
		setupChainingFields();
		setupIconPicker();
		setupWidgetRoles();
		setupCodeMirror();
		setupContentType();
	}

	/**
	 * Find an HTML element by selector.
	 *
	 * @param {string} selector The selector.
	 * @returns {HTMLElement | null} The element or null if not found.
	 */
	function findHtmlEl(selector) {
		const el = document.querySelector(selector);

		if (el instanceof HTMLElement) {
			return el;
		}

		return null;
	}

	/**
	 * Find HTML elements by selector.
	 *
	 * @param {string} selector The selector.
	 * @returns {HTMLElement[]} The HTML elements.
	 */
	function findHtmlEls(selector) {
		const nodes = document.querySelectorAll(selector);
		const els = [];

		for (let i = 0; i < nodes.length; i++) {
			const el = nodes[i];

			if (el instanceof HTMLElement) {
				els.push(el);
			}
		}

		return els;
	}

	/**
	 * Setup metaboxes
	 */
	function setupMetaboxes() {
		const postboxContainers = findHtmlEls(".postbox-container");

		if (postboxContainers.length) {
			postboxContainers.forEach(function (postboxContainer) {
				if (postboxContainer instanceof HTMLElement) {
					postboxContainer.classList.add("heatbox-wrap");
				}
			});
		}

		const postboxes = findHtmlEls(".postbox");

		if (postboxes.length) {
			postboxes.forEach(function (postbox) {
				const postboxContent = postbox.querySelector(
					".postbox-content.has-lines"
				);
				if (!postboxContent) return;

				postboxContent.parentElement?.parentElement?.classList.add("has-lines");

				postboxContent.classList.remove("has-lines");
			});
		}
	}

	/**
	 * Setup fields chaining/ dependency.
	 */
	function setupChainingFields() {
		const children = findHtmlEls("[data-show-if-field]");
		if (!children.length) return;

		children.forEach(function (child) {
			setupChainingEvent(child);
		});
	}

	/**
	 * Setup fields chaining event.
	 *
	 * @param {HTMLElement} child The children element.
	 */
	function setupChainingEvent(child) {
		const parentName = child.dataset.showIfField;
		const parentField = findHtmlEl("#" + parentName);

		checkChainingState(child, parentField);

		parentField?.addEventListener("change", function (_e) {
			checkChainingState(child, parentField);
		});
	}

	/**
	 * Check the children state: shown or hidden.
	 *
	 * @param {Element|null} child The children element.
	 * @param {Element|null} parent The parent/ dependency element.
	 */
	function checkChainingState(child, parent) {
		if (!(child instanceof HTMLElement)) {
			return;
		}

		const wantedValue = child.dataset.showIfValue;
		let parentValue;

		if (parent instanceof HTMLSelectElement) {
			parentValue = parent.options[parent.selectedIndex].value;
		} else if (parent instanceof HTMLInputElement) {
			parentValue = parent.value;
		}

		if (parentValue === wantedValue) {
			child.style.display = "block";
		} else {
			child.style.display = "none";
		}
	}

	/**
	 * Setup icon picker (Dashicons & FontAwesome)
	 */
	function setupIconPicker() {
		const $iconSelect = $('[name="udb_menu_icon"]');

		previewIcon(String($iconSelect.val()));

		$iconSelect.on("change", function (e) {
			previewIcon(String($iconSelect.val()));
		});

		window.addEventListener("load", function () {
			previewIcon(String($iconSelect.val()));
		});
	}

	/**
	 * Preview menu icon inside of the icon selector metabox.
	 *
	 * @param {string} content The icon output.
	 */
	function previewIcon(content) {
		const iconPreview = findHtmlEl(".icon-preview");

		if (iconPreview && wp.escapeHtml) {
			iconPreview.innerHTML =
				'<i class="' + wp.escapeHtml.escapeAttribute(content) + '"></i>';
		}
	}

	/**
	 * Setup widget roles.
	 */
	function setupWidgetRoles() {
		var fields = findHtmlEls(".udb-widget-roles-field");
		if (!fields.length) return;

		fields.forEach(function (field) {
			setupWidgetRole(field);
		});
	}

	/**
	 * Setup widget role.
	 *
	 * @param {Element} field - The widget role's select box.
	 */
	function setupWidgetRole(field) {
		var $field = $(field);

		$field.select2();

		$field.on("select2:select", function (e) {
			const selections = $field.select2("data");

			/** @type {string[]} values */
			const values = [];

			if (e.params.data.id === "all") {
				$field.val("all");
				$field.trigger("change");
			} else {
				if (selections.length) {
					selections.forEach(function (role) {
						if (role.id !== "all") {
							values.push(role.id);
						}
					});

					$field.val(values);
					$field.trigger("change");
				}
			}
		});
	}

	/**
	 * Setup CodeMirror fields.
	 */
	function setupCodeMirror() {
		/**
		 * Compatibility if the free version is updated first.
		 * Because UDB Pro version <=3.8.0 doesn't have the `udb-codemirror` class.
		 */
		const jsFields = findHtmlEls(".udb-custom-js");

		jsFields.forEach(function (field) {
			field.classList.add("udb-codemirror");
			field.setAttribute("data-content-mode", "js");
		});

		const fields = findHtmlEls(".udb-codemirror");
		if (!fields.length) return;

		fields.forEach(function (field) {
			let contentMode = "html";

			if (field.getAttribute("data-content-mode")) {
				contentMode = field.getAttribute("data-content-mode") ?? contentMode;
			}

			/** @type {Record<string, unknown>} */
			const editorSettings =
				wp.codeEditor && wp.codeEditor.defaultSettings
					? _.clone(wp.codeEditor.defaultSettings)
					: {};

			editorSettings.codemirror = _.extend({}, editorSettings.codemirror, {
				indentUnit: 4,
				tabSize: 4,
				mode: contentMode,
			});

			if (field instanceof HTMLTextAreaElement) {
				wp.codeEditor.initialize(field, editorSettings);
			}
		});

		if (wp && wp.data && wp.data.subscribe) {
			// @see https://github.com/WordPress/gutenberg/issues/13645
			wp.data.subscribe(function () {
				fields.forEach(function (field) {
					// @ts-ignore
					const cm = $(field).next(".CodeMirror")?.get(0)?.CodeMirror;
					if (cm) cm.save();
				});
			});
		}
	}

	/**
	 * Setup content type.
	 */
	function setupContentType() {
		const select = findHtmlEl('[name="udb_content_type"]');
		if (!(select instanceof HTMLSelectElement)) return;

		contentTypeSwitch(select.options[select.selectedIndex].value);

		var timeouts = [500, 1000, 1500, 2000, 2500, 3000];

		timeouts.forEach(function (timeout) {
			setTimeout(function () {
				contentTypeSwitch(select.options[select.selectedIndex].value);
			}, timeout);
		});

		select.addEventListener("change", function () {
			contentTypeSwitch(this.options[this.selectedIndex].value);
		});
	}

	/**
	 * Content type switch.
	 *
	 * @param {string} value The content type value.
	 */
	function contentTypeSwitch(value) {
		const htmlEditor = findHtmlEl("#udb-html-metabox");
		const elementorSwitch = findHtmlEl("#elementor-switch-mode");
		var elementorEditor = findHtmlEl("#elementor-editor");
		var brizyButtons = findHtmlEl("#post-body-content > .brizy-buttons");
		var beaverTabs = findHtmlEl("#post-body-content > .fl-builder-admin");
		var diviButtons = findHtmlEl(
			"#post-body-content > .et_pb_toggle_builder_wrapper"
		);
		var diviEditor = findHtmlEl("#et_pb_layout");
		var oxygenEditor = findHtmlEl("#ct_views_cpt");
		var normalEditor = findHtmlEl("#postdivrich");

		if (value === "html") {
			document.body.classList.add("udb-use-html-editor");
			document.body.classList.remove("udb-use-default-editor");

			if (htmlEditor) htmlEditor.style.display = "block";

			if (elementorSwitch) elementorSwitch.style.display = "none";
			if (!document.body.classList.contains("elementor-editor-inactive")) {
				if (elementorEditor) elementorEditor.style.display = "none";
			}
			if (brizyButtons) brizyButtons.style.display = "none";
			if (beaverTabs) beaverTabs.style.display = "none";
			if (diviButtons) diviButtons.style.display = "none";
			if (diviEditor) diviEditor.style.display = "none";
			if (oxygenEditor) oxygenEditor.style.display = "none";

			if (!document.body.classList.contains("fl-builder-enabled")) {
				if (
					normalEditor &&
					!normalEditor.parentElement?.classList.contains(
						"et_pb_post_body_hidden"
					)
				) {
					if (normalEditor) normalEditor.style.display = "none";
				}
			}
		} else {
			document.body.classList.remove("udb-use-html-editor");
			document.body.classList.add("udb-use-default-editor");

			if (htmlEditor) htmlEditor.style.display = "none";

			if (elementorSwitch) elementorSwitch.style.display = "block";

			if (!document.body.classList.contains("elementor-editor-inactive")) {
				if (elementorEditor) elementorEditor.style.display = "block";
			}

			if (brizyButtons) brizyButtons.style.display = "block";
			if (beaverTabs) beaverTabs.style.display = "block";
			if (diviButtons) diviButtons.style.display = "block";
			if (diviEditor) diviEditor.style.display = "block";
			if (oxygenEditor) oxygenEditor.style.display = "block";

			if (!document.body.classList.contains("fl-builder-enabled")) {
				if (
					normalEditor &&
					!normalEditor.parentElement?.classList.contains(
						"et_pb_post_body_hidden"
					)
				) {
					if (normalEditor) normalEditor.style.display = "block";
				}
			}
		}
	}

	init();
})(jQuery);
