/**
 * This module is intended to handle the loading redirect settings page.
 * 
 * Global object used:
 * - udbLoginRedirect
 *
 * @param {Object} $ jQuery object.
 * @return {Object}
 */
(function ($) {

	var $repeater = $('.udb-login-redirect--repeater');
	var $roleSelector = $('.udb-login-redirect--role-selector');
	var expectedOrder = [];

	// Run the module.
	init();

	/**
	 * Initialize the module, call the main functions.
	 *
	 * This function is the only function that should be called on top level scope.
	 * Other functions are called / hooked from this function.
	 */
	function init() {

		setupRoleSelector();

	}

	/**
	 * Setup the role selector that functioning like a repeater.
	 */
	function setupRoleSelector() {

		if (!$roleSelector.length) return;

		$roleSelector.select2({
			placeholder: $roleSelector[0].dataset.placeholder
		});

		$roleSelector.on('select2:select', onRoleSelected);
		$roleSelector.on('select2:unselect', onRoleUnselected);

		$(document).on('click', '.udb-login-redirect--remove-field', onDeleteButtonClick);
		window.addEventListener('load', function () {
			maintainSelectedOrder('ready', {});
		});

	}

	/**
	 * Event handler to run when a role (inside select2) is selected.
	 * @param {Event} e The event object.
	 */
	function onRoleSelected(e) {

		var data = e.params.data;
		var defaultValue = data.element.dataset.udbDefaultValue;

		maintainSelectedOrder('select', data);

		var markup = '\
		<div class="udb-login-redirect--repeater-item" data-udb-role-key="' + data.id + '" data-udb-role-name="' + data.text.trim() + '">\
			<label class="udb-login-redirect--field-label">\
				' + data.text.trim() + '\
			</label>\
			<div class="udb-login-redirect--field-control">\
				<input type="text" name="udb_settings[login_redirect_urls][' + data.id + ']" value="' + defaultValue + '" placeholder="' + udbLoginRedirect.adminUrl + '">\
				<button type="button" class="udb-login-redirect--remove-field">\
					<i class="fas fa-minus"></i>\
				</button>\
			</div>\
		</div>\
		';

		$repeater.append(markup);

	}

	/**
	 * Event handler to run when a role (inside select2) is un-selected.
	 * @param {Event} e The event object.
	 */
	function onRoleUnselected(e) {

		var data = e.params.data;

		maintainSelectedOrder('unselect', data);

		$repeater.find('.udb-login-redirect--repeater-item[data-udb-role-key="' + data.id + '"]').remove();

	}

	/**
	 * Event handler to run when a delete buttotn is clicked.
	 *
	 * It will then un-select the connected select2 item
	 * which will remove the field.
	 *
	 * @param {Event} e The event object.
	 */
	function onDeleteButtonClick(e) {

		var roleKey = this.parentNode.parentNode.dataset.udbRoleKey;
		var roleName = this.parentNode.parentNode.dataset.udbRoleName;
		var values = $roleSelector.val();
		var index = values.indexOf(roleKey);

		if (index > -1) {
			values.splice(index, 1);
			$repeater.find('.udb-login-redirect--repeater-item[data-udb-role-key="' + roleKey + '"]').remove();
			$roleSelector.val(values).trigger('change');
			maintainSelectedOrder('unselect', { id: roleKey, text: roleName });
		}

	}

	/**
	 * Maintain the selected select2 values order based on when they were selected.
	 *
	 * The solution here doesn't seem to be perfect: https://github.com/select2/select2/issues/3106
	 * So let's write our own patch.
	 * 
	 * @param {string} action Accepts 'select', 'unselect', or 'ready'.
	 * @param {Object} data Select2 selection data.
	 */
	function maintainSelectedOrder(action, data) {

		if ('select' === action) {
			expectedOrder.push(data.text.trim());
		} else if ('unselect' === action) {
			expectedOrder.some(function (orderText, index) {
				if (data.text.trim() === orderText) {
					expectedOrder.splice(index, 1);
					return true;
				}

				return false;
			});
		} else if ('ready' === action) {
			expectedOrder = $roleSelector[0].dataset.udbSelectionOrder.split(',');
		}

		var oldContainer = $roleSelector[0].parentNode.querySelector('.select2-container .select2-selection__rendered');
		var selectedItems = oldContainer.querySelectorAll('.select2-selection__rendered .select2-selection__choice');

		var currentOrder = [];
		var diffItems = [];

		selectedItems.forEach(function (item) {
			var textContainer = item.querySelector('.select2-selection__choice__display');
			currentOrder.push(textContainer.innerText.trim());
		});

		var clonedContainer = oldContainer.cloneNode(false);

		expectedOrder.forEach(function (orderText) {
			[].slice.call(selectedItems).some(function (item) {
				var textContainer = item.querySelector('.select2-selection__choice__display');

				if (textContainer.innerText.trim() === orderText) {
					clonedContainer.appendChild(item);
					return true;
				}

				return false;
			});
		});

		diffItems = arrayDiff(expectedOrder, currentOrder);

		if (diffItems.length) {
			diffItems.forEach(function (diffItem) {
				clonedContainer.appendChild(diffItem);
			});
		}

		oldContainer.parentNode.replaceChild(clonedContainer, oldContainer);

	}

	/**
	 * Find the difference between array1 and array2.
	 *
	 * @see https://stackoverflow.com/questions/1187518/how-to-get-the-difference-between-two-arrays-in-javascript#answer-4026828
	 */
	function arrayDiff(array1, array2) {

		return array1.filter(function (i) { return array2.indexOf(i) < 0; });

	}

	return {};

})(jQuery);
