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
		$(document).on('click', '.udb-login-redirect--remove-field', onDeleteButtonClick);

	}

	/**
	 * Event handler to run when a role (inside select2) is selected.
	 * @param {Event} e The event object.
	 */
	function onRoleSelected(e) {

		var data = e.params.data;
		var defaultValue = data.element.dataset.udbDefaultUrl;

		data.element.disabled = true;
		data.element.selected = false;
		$roleSelector.trigger('change');

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
	 * Event handler to run when a delete buttotn is clicked.
	 *
	 * It will then un-select the connected select2 item
	 * which will remove the field.
	 *
	 * @param {Event} e The event object.
	 */
	function onDeleteButtonClick(e) {

		var roleKey = this.parentNode.parentNode.dataset.udbRoleKey;
		var element = $roleSelector[0].querySelector('option[value="' + roleKey + '"]');
		if (element) element.disabled = false;
		$repeater.find('.udb-login-redirect--repeater-item[data-udb-role-key="' + roleKey + '"]').remove();
		$roleSelector.trigger('change');

	}

	return {};

})(jQuery);
