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
	 * "Reset to WordPress default" empties the welcome panel editor (Visual and Text tab).
	 */
	function setupWelcomePanelReset() {
		const button = document.querySelector(".udb-welcome-panel-reset");
		if (!(button instanceof HTMLElement)) return;

		button.addEventListener("click", function () {
			const editorId = button.dataset.editor || "";
			const editor = window.tinymce ? window.tinymce.get(editorId) : null;
			const textarea = document.getElementById(editorId);

			if (editor) editor.setContent("");
			if (textarea instanceof HTMLTextAreaElement) textarea.value = "";
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
