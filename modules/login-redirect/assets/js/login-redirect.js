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

	var roleSelectors = document.querySelectorAll('.udb-login-redirect--role-selector');

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

		if (!roleSelectors.length) return;

		roleSelectors.forEach(function (roleSelector) {
			var $roleSelector = $(roleSelector);

			$roleSelector.select2({
				placeholder: roleSelector.dataset.placeholder
			});

			$roleSelector.on('select2:select', onRoleSelected);
		});


		$(document).on('click', '.udb-login-redirect--remove-field', onDeleteButtonClick);

	}

	/**
	 * Event handler to run when a role (inside select2) is selected.
	 * @param {Event} e The event object.
	 */
	function onRoleSelected(e) {

		var data = e.params.data;
		var defaultValue = data.element.dataset.udbDefaultSlug;

		data.element.disabled = true;
		data.element.selected = false;
		$(this).trigger('change');

		var siteType = 'subsites' === data.element.parentNode.dataset.udbSiteType ? 'subsites_' : '';

		var markup = '\
		<div class="udb-login-redirect--repeater-item" data-udb-role-key="' + data.id + '" data-udb-role-name="' + data.text.trim() + '">\
			<label class="udb-login-redirect--field-label">\
				' + data.text.trim() + '\
			</label>\
			<div class="udb-login-redirect--field-control">\
				<div class="udb-url-prefix-suffix-field">\
					<div class="udb-url-prefix-field">\
						<code>\
							' + this.dataset.udbFieldPrefix + '\
						</code>\
					</div>\
					<input type="text" name="udb_login_redirect[' + siteType + 'login_redirect_slugs][' + data.id + ']" value="' + defaultValue + '" placeholder="wp-admin/">\
					<div class="udb-url-suffix-field">\
						<button type="button" class="udb-login-redirect--remove-field">\
							<span class="udb-login-redirect--close-icon"></span>\
						</button>\
					</div>\
				</div>\
			</div>\
		</div>\
		';

		$(this).parent().find('.udb-login-redirect--repeater').append(markup);

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

		var wrapper = getParentNode(this, 6);
		var roleKey = getParentNode(this, 4).dataset.udbRoleKey;
		var element = wrapper.querySelector('.udb-login-redirect--role-selector option[value="' + roleKey + '"]');
		if (element) element.disabled = false;

		var repeaterItem = wrapper.querySelector('.udb-login-redirect--repeater-item[data-udb-role-key="' + roleKey + '"]');
		if (repeaterItem) repeaterItem.parentNode.removeChild(repeaterItem);
		$(this).trigger('change');

	}

	/**
	 * Get parent node of an element with depth level.
	 * 
	 * @param {HTMLElement} el The element to get the parent node from.
	 * @param {int} depth The depth level.
	 */
	function getParentNode(el, depth) {

		if (!depth) {
			return el.parentNode;
		}

		var parentNode = el;
		var i = 1;

		for (; i <= depth; i++) {
			parentNode = parentNode.parentNode;
		}

		return parentNode;

	}

	return {};

})(jQuery);
