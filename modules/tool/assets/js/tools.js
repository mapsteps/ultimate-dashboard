(function ($) {
	var selectAllButton = document.querySelector(".udb-select-all-modules");
	var moduleCheckboxes = document.querySelectorAll(".udb-module-checkbox");
	var checkboxes = [];
	var isAllChecked = false;

	function isChecked(el) {
		return el.checked;
	}

	function init() {
		if (!selectAllButton || !moduleCheckboxes.length) return;
		checkboxes = [].slice.call(moduleCheckboxes);

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

	function onSelectAllButtonClick(e) {
		e.preventDefault();

		if (isAllChecked) {
			isAllChecked = false;
			selectAllButton.innerHTML = "Select All";
		} else {
			isAllChecked = true;
			selectAllButton.innerHTML = "Unselect All";
		}

		checkboxes.forEach(function (el) {
			if (isAllChecked) {
				el.checked = true;
			} else {
				el.checked = false;
			}
		});
	}

	function onCheckboxChange(e) {
		if (checkboxes.every(isChecked)) {
			isAllChecked = true;
			selectAllButton.innerHTML = "Unselect All";
		} else {
			isAllChecked = false;
			selectAllButton.innerHTML = "Select All";
		}
	}

	init();
})(jQuery);
