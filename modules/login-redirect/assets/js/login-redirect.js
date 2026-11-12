/**
 * Login Redirect page: "Redirect After Login" rows.
 *
 * Picking a role in the dropdown adds a row with its URL field; the role is then disabled in the
 * dropdown until its row is removed. PRO's multisite page has two of these (blueprint and subsites).
 */
(function () {
	"use strict";

	document.querySelectorAll(".udb-login-redirect--role-selector").forEach(function (select) {
		const wrapper = select.closest(".udb-login-redirect--wrapper");
		const repeater = wrapper.querySelector(".udb-login-redirect--repeater");

		select.addEventListener("change", function () {
			const option = select.selectedOptions[0];

			if (!select.value || !option) return;

			const row = createRow(select, select.value, option.textContent.trim());

			repeater.append(row);
			option.disabled = true;
			select.value = "";
			row.querySelector("input").focus();
		});

		wrapper.addEventListener("click", function (e) {
			const button = e.target.closest(".udb-login-redirect--remove-field");

			if (!button) return;

			const row = button.closest(".udb-login-redirect--repeater-item");
			const option = select.querySelector('option[value="' + CSS.escape(row.dataset.udbRoleKey) + '"]');

			if (option) option.disabled = false;

			row.remove();
			select.focus();
		});
	});

	/**
	 * Build a row like the one the PHP template prints.
	 *
	 * @param {HTMLSelectElement} select The role dropdown (holds the site type and URL prefix).
	 * @param {string} roleKey The role key.
	 * @param {string} roleName The role name.
	 * @returns {HTMLElement}
	 */
	function createRow(select, roleKey, roleName) {
		const siteType = select.dataset.udbSiteType || "blueprint";
		const namePrefix = "subsites" === siteType ? "subsites_" : "";
		const fieldId = "udb-login-redirect--" + siteType + "-" + roleKey;

		const row = element("div", "udb-login-redirect--repeater-item");
		row.dataset.udbRoleKey = roleKey;
		row.dataset.udbRoleName = roleName;

		const label = element("label", "udb-login-redirect--field-label", roleName);
		label.htmlFor = fieldId;

		const group = element("div", "heatbox-input-group");
		const prefix = element("span", "heatbox-input-prefix", select.dataset.udbFieldPrefix);
		prefix.title = select.dataset.udbFieldPrefix;

		const input = element("input");
		input.type = "text";
		input.id = fieldId;
		input.name = "udb_login_redirect[" + namePrefix + "login_redirect_slugs][" + roleKey + "]";
		input.placeholder = "wp-admin/";

		const button = element("button", "button-link udb-login-redirect--remove-field");
		button.type = "button";
		button.append(element("span", "dashicons dashicons-no-alt"), element("span", "screen-reader-text", (select.dataset.udbRemoveLabel || "%s").replace("%s", roleName)));
		button.firstChild.setAttribute("aria-hidden", "true");

		group.append(prefix, input);
		row.append(label, group, button);

		return row;
	}

	/**
	 * Create an element.
	 *
	 * @param {string} tag The tag name.
	 * @param {string} [className] The class name.
	 * @param {string} [text] The text content.
	 * @returns {HTMLElement}
	 */
	function element(tag, className, text) {
		const el = document.createElement(tag);

		if (className) el.className = className;
		if (text) el.textContent = text;

		return el;
	}
})();
