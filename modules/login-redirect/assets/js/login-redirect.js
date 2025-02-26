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
	const roleSelectors = document.querySelectorAll(
		".udb-login-redirect--role-selector"
	);

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
			if (!(roleSelector instanceof HTMLSelectElement)) return;
			const $roleSelector = $(roleSelector);

			$roleSelector.select2({
				placeholder: roleSelector.dataset.placeholder,
			});

			$roleSelector.on("select2:select", onRoleSelected);
		});

		$(document).on(
			"click",
			".udb-login-redirect--remove-field",
			onDeleteButtonClick
		);
	}

	/**
	 * Event handler to run when a role (inside select2) is selected.
	 *
	 * @param {Select2.Event<HTMLSelectElement, Select2.DataParams>} e The event object.
	 * @this {HTMLElement}
	 */
	function onRoleSelected(e) {
		const data = e.params.data;
		const defaultValue = data.element.dataset.udbDefaultSlug;

		data.element.disabled = true;
		data.element.selected = false;

		$(this).trigger("change");

		const siteType =
			"subsites" === data.element.parentElement?.dataset.udbSiteType
				? "subsites_"
				: "";

		const markup =
			'\
		<div class="udb-login-redirect--repeater-item" data-udb-role-key="' +
			data.id +
			'" data-udb-role-name="' +
			data.text.trim() +
			'">\
			<label class="udb-login-redirect--field-label">\
				' +
			data.text.trim() +
			'\
			</label>\
			<div class="udb-login-redirect--field-control">\
				<div class="udb-url-prefix-suffix-field">\
					<div class="udb-url-prefix-field">\
						<code>\
							' +
			this.dataset.udbFieldPrefix +
			'\
						</code>\
					</div>\
					<input type="text" name="udb_login_redirect[' +
			siteType +
			"login_redirect_slugs][" +
			data.id +
			']" value="' +
			defaultValue +
			'" placeholder="wp-admin/">\
					<div class="udb-url-suffix-field">\
						<button type="button" class="udb-login-redirect--remove-field">\
							<span class="udb-login-redirect--close-icon"></span>\
						</button>\
					</div>\
				</div>\
			</div>\
		</div>\
		';

		$(this).parent().find(".udb-login-redirect--repeater").append(markup);
	}

	/**
	 * Event handler to run when a delete buttotn is clicked.
	 *
	 * It will then un-select the connected select2 item
	 * which will remove the field.
	 *
	 * @param {JQuery.ClickEvent} e The event object.
	 * @this {HTMLElement}
	 */
	function onDeleteButtonClick(e) {
		const wrapper = getClosest(this, 6);
		const roleKey = getClosest(this, 4)?.dataset.udbRoleKey;
		const element = wrapper?.querySelector(
			'.udb-login-redirect--role-selector option[value="' + roleKey + '"]'
		);
		if (element instanceof HTMLOptionElement) element.disabled = false;

		const repeaterItem = wrapper?.querySelector(
			'.udb-login-redirect--repeater-item[data-udb-role-key="' + roleKey + '"]'
		);

		repeaterItem?.parentElement?.removeChild(repeaterItem);

		$(this).trigger("change");
	}

	/**
	 * Get parent element of an element with depth level.
	 *
	 * @param {HTMLElement} el The element to get the parent node from.
	 * @param {number} depth The depth level.
	 *
	 * @returns {HTMLElement|null} The parent node.
	 */
	function getClosest(el, depth) {
		if (!depth) {
			return el.parentElement;
		}

		/** @type {HTMLElement|null} */
		let parentEl = el;
		let i = 1;

		for (; i <= depth; i++) {
			parentEl = parentEl?.parentElement ?? null;
		}

		return parentEl;
	}

	return {};
})(jQuery);
