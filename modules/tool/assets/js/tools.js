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
	 * Checkboxes added through the "udb_export_fields" hook (PRO 3.11: <p><label><input> Text</label></p>)
	 * get heatbox's checkbox markup and join the list, so all of them look and line up the same.
	 */
	function adoptAddedCheckboxes() {
		const list = document.querySelector(".udb-tools-page .setting-fields");

		document.querySelectorAll(".udb-export-more label").forEach(function (label) {
			const input = label.querySelector('input[type="checkbox"]');

			if (!list || !input || label.classList.contains("checkbox-label")) return;

			const wrapper = label.parentElement;
			const field = document.createElement("div");
			const indicator = document.createElement("div");

			field.className = "setting-field";
			indicator.className = "indicator";
			label.classList.add("label", "checkbox-label");
			label.append(input, indicator);
			field.append(label);
			list.append(field);

			// The now empty <p> it came in.
			if (wrapper.matches(".udb-export-more p") && !wrapper.textContent.trim()) wrapper.remove();
		});
	}
})();
