/**
 * This module is intended to handle the settings page.
 *
 * @param {Object} $ jQuery object.
 * @return {Object}
 */
(function ($) {
	/** @type {CodeMirrorEditor[]} */
	const codeMirrorInstances = [];

	// Run the module.
	init();

	/**
	 * Initialize the module, call the main functions.
	 *
	 * This function is the only function that should be called on top level scope.
	 * Other functions are called / hooked from this function.
	 */
	function init() {
		setupCssFields();
		setupColorFields();
		setupTabsNavigation();
		setupRemoveAllWidgets();
		setupWelcomePanelReset();
	}

	/**
	 * "Remove All Widgets" makes the individual widget boxes irrelevant: grey them out while it's checked.
	 * The boxes keep their values, so unchecking "Remove All" brings the previous choice back.
	 */
	function setupRemoveAllWidgets() {
		const removeAll = document.querySelector('[name="udb_settings[remove-all]"]');
		const individualRow = document
			.querySelector('[name="udb_settings[welcome_panel]"]')
			?.closest("tr");

		if (!(removeAll instanceof HTMLInputElement) || !individualRow) return;

		function update() {
			individualRow.classList.toggle("row-disabled", removeAll.checked);
			individualRow.toggleAttribute("inert", removeAll.checked);
		}

		removeAll.addEventListener("change", update);
		update();
	}

	/**
	 * The welcome panel editor starts with WordPress' default panel.
	 *
	 * The hidden "is default" field tells the save whether it's still WordPress' panel (then nothing is saved,
	 * so the dashboard keeps showing WordPress' own, current panel) or edited content.
	 * "Reset to WordPress default" puts WordPress' panel back in the editor.
	 */
	function setupWelcomePanelReset() {
		const button = document.querySelector(".udb-welcome-panel-reset");
		const isDefault = document.getElementById("udb-welcome-panel-is-default");
		const defaultContent = document.getElementById("udb-welcome-panel-default");
		if (!(button instanceof HTMLElement)) return;
		if (!(isDefault instanceof HTMLInputElement)) return;
		if (!(defaultContent instanceof HTMLTextAreaElement)) return;

		const editorId = button.dataset.editor || "";
		const textarea = document.getElementById(editorId);
		const getEditor = () => (window.tinymce ? window.tinymce.get(editorId) : null);

		// Edits in the Text tab.
		if (textarea instanceof HTMLTextAreaElement) {
			textarea.addEventListener("input", function () {
				isDefault.value = "0";
			});
		}

		// Edits in the Visual tab. Capture phase: runs before TinyMCE's own submit handler clears "dirty".
		if (textarea instanceof HTMLTextAreaElement && textarea.form) {
			textarea.form.addEventListener(
				"submit",
				function () {
					const editor = getEditor();
					if (editor && editor.isDirty()) isDefault.value = "0";
				},
				true
			);
		}

		button.addEventListener("click", function () {
			const editor = getEditor();

			if (editor) {
				editor.setContent(defaultContent.value);
				editor.setDirty(false);
			}

			if (textarea instanceof HTMLTextAreaElement) {
				textarea.value = editor ? editor.getContent() : defaultContent.value;
			}

			isDefault.value = "1";
		});
	}

	/**
	 * Setup the CSS fields using CodeMirror.
	 */
	function setupCssFields() {
		const customCSSFields = document.querySelectorAll(".udb-custom-css");
		if (!customCSSFields.length) return;

		/** @type {Record<string, unknown>} */
		const editorSettings = wp.codeEditor.defaultSettings
			? _.clone(wp.codeEditor.defaultSettings)
			: {};

		editorSettings.codemirror = _.extend({}, editorSettings.codemirror, {
			indentUnit: 4,
			tabSize: 4,
			mode: "css",
		});

		[].slice.call(customCSSFields).forEach(function (el) {
			var codeEditor = wp.codeEditor.initialize(el, editorSettings);
			codeMirrorInstances.push(codeEditor.codemirror);
		});
	}

	/**
	 * CodeMirror can't measure itself inside a hidden tab: refresh it when its tab is shown.
	 */
	function refreshCssFields() {
		codeMirrorInstances.forEach(function (codeMirror) {
			codeMirror.refresh();
		});
	}

	/**
	 * Setup color picker fields.
	 */
	function setupColorFields() {
		const colorFields = document.querySelectorAll(".udb-color-field");
		if (!colorFields.length) return;

		colorFields.forEach(function (el) {
			if (!(el instanceof HTMLInputElement)) return;

			$(el).wpColorPicker({
				defaultColor: el.dataset.default,
				change: function (event, ui) {},
				clear: function () {},
				hide: true,
				palettes: true,
			});
		});
	}

	/**
	 * Setup the tabs navigation for settings page.
	 */
	function setupTabsNavigation() {
		$(".heatbox-tab-nav-item").on("click", function () {
			const link = this.querySelector("a");
			showPanel(link?.href.substring(link.href.indexOf("#") + 1) ?? "");
		});

		window.addEventListener("load", function () {
			showPanel(window.location.hash.substr(1) || "widgets");
		});
	}

	/**
	 * Show a tab's panel.
	 *
	 * @param {string} hashValue The tab (widgets, general, custom-css …).
	 */
	function showPanel(hashValue) {
		setRefererValue(hashValue);

		$(".heatbox-tab-nav-item").removeClass("active");
		$(".heatbox-tab-nav-item." + hashValue + "-panel").addClass("active");

		$(".udb-settings-form .heatbox-admin-panel").css("display", "none");
		$(".udb-settings-form .udb-" + hashValue + "-panel").css("display", "block");

		refreshCssFields();
	}

	/**
	 * Set referer value for the tabs navigation of settings page.
	 * This is being used to preserve the active tab after saving the settings page.
	 *
	 * @param {string} hashValue The hash value.
	 */
	function setRefererValue(hashValue) {
		const refererField = document.querySelector('[name="_wp_http_referer"]');
		if (
			!(refererField instanceof HTMLInputElement) &&
			!(refererField instanceof HTMLTextAreaElement)
		) {
			return;
		}

		let url;

		if (refererField.value.includes("#")) {
			url = refererField.value.split("#");
			url = url[0];

			refererField.value = url + "#" + hashValue;
		} else {
			refererField.value = refererField.value + "#" + hashValue;
		}
	}

	return {};
})(jQuery);
