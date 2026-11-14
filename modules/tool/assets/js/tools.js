/**
 * Tools page: the "Select All / Unselect All" button for the export checkboxes (incl. the ones PRO adds).
 */
(function () {
	"use strict";

	adoptAddedCheckboxes();

	const button = document.querySelector(".udb-select-all-modules");
	const checkboxes = Array.from(document.querySelectorAll(".udb-module-checkbox"));

	if (!button || !checkboxes.length) return;

	const allChecked = () => checkboxes.every((checkbox) => checkbox.checked);

	/**
	 * Show "Unselect All" when everything is checked, otherwise "Select All".
	 */
	function updateButton() {
		button.textContent = allChecked() ? button.dataset.unselect : button.dataset.select;
	}

	button.addEventListener("click", function () {
		const check = !allChecked();

		checkboxes.forEach(function (checkbox) {
			checkbox.checked = check;
		});

		updateButton();
	});

	checkboxes.forEach(function (checkbox) {
		checkbox.addEventListener("change", updateButton);
	});

	updateButton();

	/**
	 * Checkboxes added through the "udb_export_fields" hook are printed right in the list.
	 * Newer PRO prints heatbox's markup (.setting-field); PRO 3.11 prints plain <p><label><input> Text</label></p>,
	 * which gets heatbox's markup here, in its place.
	 */
	function adoptAddedCheckboxes() {
		const list = document.querySelector(".udb-tools-page .setting-fields");

		if (!list) return;

		list.querySelectorAll("label").forEach(function (label) {
			const input = label.querySelector('input[type="checkbox"]');

			if (!input || label.classList.contains("checkbox-label")) return;

			const wrapper = label.parentElement;
			const field = document.createElement("div");
			const indicator = document.createElement("div");

			field.className = "setting-field";
			indicator.className = "indicator";
			label.classList.add("label", "checkbox-label");
			label.append(input, indicator);

			// In place of the <p> it came in (when that only held it), otherwise at the end of the list.
			if (wrapper !== list && wrapper.matches("p") && 1 === wrapper.children.length) {
				wrapper.replaceWith(field);
			} else {
				list.append(field);
			}

			field.append(label);
		});
	}
})();
