(function ($) {
	const selectAllButton = document.querySelector(".udb-select-all-modules");
	const moduleCheckboxes = document.querySelectorAll(".udb-module-checkbox");

	/**
	 * @type {HTMLInputElement[]} checkboxes - The checkboxes.
	 */
	const checkboxes = [];

	let isAllChecked = false;

	/**
	 * Check if the checkbox is checked.
	 *
	 * @param {HTMLInputElement} el The checkbox element.
	 * @returns {boolean} Whether the checkbox is checked.
	 */
	function isChecked(el) {
		return el.checked;
	}

	function init() {
		if (!selectAllButton || !moduleCheckboxes.length) return;

		for (let i = 0; i < moduleCheckboxes.length; i++) {
			const checkbox = moduleCheckboxes[i];
			if (!(checkbox instanceof HTMLInputElement)) continue;
			checkboxes.push(checkbox);
		}

		if (checkboxes.every(isChecked)) {
			isAllChecked = true;
			selectAllButton.innerHTML = "Unselect All";
		} else {
			isAllChecked = false;
			selectAllButton.innerHTML = "Select All";
		}

		selectAllButton.addEventListener("click", onSelectAllButtonClick);

		checkboxes.forEach(function (checkbox) {
			checkbox.addEventListener("change", onCheckboxChange);
		});
	}

	/**
	 * Event handler for the select all button click.
	 *
	 * @param {Event} e The event object.
	 */
	function onSelectAllButtonClick(e) {
		e.preventDefault();

		if (isAllChecked) {
			isAllChecked = false;
			if (selectAllButton) selectAllButton.innerHTML = "Select All";
		} else {
			isAllChecked = true;
			if (selectAllButton) selectAllButton.innerHTML = "Unselect All";
		}

		checkboxes.forEach(function (el) {
			if (isAllChecked) {
				el.checked = true;
			} else {
				el.checked = false;
			}
		});
	}

	/**
	 * Event handler for the checkbox change event.
	 *
	 * @param {Event} e The event object.
	 */
	function onCheckboxChange(e) {
		if (checkboxes.every(isChecked)) {
			isAllChecked = true;
			if (selectAllButton) selectAllButton.innerHTML = "Unselect All";
		} else {
			isAllChecked = false;
			if (selectAllButton) selectAllButton.innerHTML = "Select All";
		}
	}

	init();
})(jQuery);
