/**
 * Icon picker (same picker as Better Admin Bar's): a button showing the icon; clicking it opens a
 * searchable grid of Dashicons & Font Awesome icons below it.
 *
 * Markup:
 * <div class="udb-icon-field">
 *   <button type="button" class="icon-picker"><i class="dashicons dashicons-menu"></i></button>
 *   <input type="hidden" name="…" value="dashicons dashicons-menu">
 * </div>
 *
 * Picking an icon sets the hidden input (and fires "change" on it) and the button's icon.
 * The icon lists are loaded when a picker is opened the first time.
 *
 * Options on .udb-icon-field (used by the admin menu & admin bar editors):
 * - data-icon-set="dashicons": only offer Dashicons.
 * - data-value="name": store the short name ("dashicons-menu") instead of the classes.
 * - data-sync: the button's icon is set from the input (value, or its data-default), also for
 *   fields added later by a script. A .icon-picker-clear button inside empties the input.
 *
 * Used global object: udbIconPicker { urls, labels }.
 */
(function () {
	"use strict";

	const settings = window.udbIconPicker || { urls: [], labels: {} };
	const labels = settings.labels || {};
	let request = null;
	let icons = null;

	/**
	 * Load the icon lists (once).
	 *
	 * @returns {Promise<string[]>} The icon classes, e.g. "dashicons dashicons-menu".
	 */
	function load() {
		if (!request) {
			request = Promise.all(
				settings.urls.map((url) =>
					fetch(url).then((response) => {
						if (!response.ok) throw new Error(response.statusText);
						return response.json();
					})
				)
			)
				.then(function (lists) {
					icons = [].concat(...lists);
					return icons;
				})
				.catch(function (error) {
					// Try again next time.
					request = null;
					throw error;
				});
		}

		return request;
	}

	/**
	 * The words an icon is found by: "dashicons dashicons-admin-home" > "admin home".
	 *
	 * @param {string} icon The icon classes.
	 * @returns {string}
	 */
	function searchWords(icon) {
		return icon
			.split(" ")
			.pop()
			.replace(/^(dashicons|fa)-/, "")
			.replace(/-/g, " ");
	}

	/**
	 * The value to store for an icon.
	 *
	 * @param {HTMLElement|null} field The .udb-icon-field.
	 * @param {string} icon The icon classes, e.g. "dashicons dashicons-menu".
	 * @returns {string}
	 */
	function toValue(field, icon) {
		return field && "name" === field.dataset.value ? icon.split(" ").pop() : icon;
	}

	/**
	 * The icon classes for a stored value.
	 *
	 * @param {HTMLElement|null} field The .udb-icon-field.
	 * @param {string} value The stored value.
	 * @returns {string}
	 */
	function toClasses(field, value) {
		if (!value || !field || "name" !== field.dataset.value) return value;

		return value.split("-")[0] + " " + value;
	}

	/**
	 * Show the input's icon on the button (and the clear button only when there's something to clear).
	 *
	 * @param {HTMLElement} field The .udb-icon-field.
	 */
	function sync(field) {
		const input = field.querySelector('input[type="hidden"]');
		const preview = field.querySelector(".icon-picker i");
		const clear = field.querySelector(".icon-picker-clear");

		if (!input) return;

		const value = input.value || input.dataset.default || "";

		if (preview) preview.className = toClasses(field, value);
		field.classList.toggle("is-empty", !value);
		if (clear) clear.hidden = !input.value || input.disabled;
	}

	/**
	 * Sync the fields inside an element (and the element itself).
	 *
	 * @param {Element} root The element.
	 */
	function syncWithin(root) {
		if (root.matches(".udb-icon-field[data-sync]")) sync(root);
		root.querySelectorAll(".udb-icon-field[data-sync]").forEach(sync);
	}

	/**
	 * Close every open picker.
	 */
	function close() {
		document.querySelectorAll(".icon-picker-container").forEach((popup) => popup.remove());
		document.removeEventListener("mouseup", onOutsideClick);
	}

	/**
	 * Close the picker when clicking outside of it.
	 *
	 * @param {MouseEvent} e The event.
	 */
	function onOutsideClick(e) {
		const popup = document.querySelector(".icon-picker-container");

		if (popup && !popup.contains(e.target) && !e.target.closest(".icon-picker")) {
			close();
		}
	}

	/**
	 * Create an element.
	 *
	 * @param {string} tag The tag name.
	 * @param {string} className The class name.
	 * @returns {HTMLElement}
	 */
	function element(tag, className) {
		const el = document.createElement(tag);
		el.className = className;
		return el;
	}

	/**
	 * Open the picker below a button.
	 *
	 * @param {HTMLButtonElement} button The button that opens the picker.
	 */
	function open(button) {
		close();

		const field = button.closest(".udb-icon-field");
		const input = button.parentNode.querySelector('input[type="hidden"]');
		const popup = element("div", "icon-picker-container");
		const control = element("div", "icon-picker-control");
		const search = element("input", "icon-picker-search");
		const message = element("p", "icon-picker-message");
		const list = element("ul", "icon-picker-list");

		search.type = "search";
		search.autocomplete = "off";
		search.placeholder = labels.search || "";
		search.setAttribute("aria-label", labels.search || "");
		message.setAttribute("role", "status");
		message.textContent = labels.loading || "";

		control.append(search);
		popup.append(control, message, list);
		button.parentNode.append(popup);
		search.focus();

		document.addEventListener("mouseup", onOutsideClick);

		load().then(
			function () {
				if (!popup.isConnected) return;

				const current = input ? input.value || input.dataset.default || "" : "";
				const set = field ? field.dataset.iconSet : "";

				icons.forEach(function (icon) {
					if (set && 0 !== icon.indexOf(set + " ")) return;

					const item = element("li", "");
					const choice = element(
						"button",
						"icon-picker-item" + (toValue(field, icon) === current ? " is-current" : "")
					);
					const name = icon.split(" ").pop();

					item.dataset.search = searchWords(icon);
					choice.type = "button";
					choice.dataset.icon = icon;
					choice.title = name;
					choice.setAttribute("aria-label", name);
					choice.append(element("i", icon));
					item.append(choice);
					list.append(item);
				});

				message.textContent = "";
				filter();
			},
			function () {
				message.textContent = labels.error || "";
			}
		);

		/**
		 * Show the icons that match the search.
		 */
		function filter() {
			const words = search.value.toLowerCase().trim().split(/\s+/).filter(Boolean);
			let found = 0;

			Array.from(list.children).forEach(function (item) {
				const match = words.every((word) => item.dataset.search.indexOf(word) !== -1);

				item.hidden = !match;
				if (match) found++;
			});

			if (icons) message.textContent = found ? "" : labels.none || "";
		}

		search.addEventListener("input", filter);

		popup.addEventListener("keydown", function (e) {
			if ("Escape" === e.key) {
				e.preventDefault();
				e.stopPropagation();
				close();
				button.focus();
			}
		});

		list.addEventListener("click", function (e) {
			const choice = e.target.closest(".icon-picker-item");

			if (!choice) return;

			const icon = choice.dataset.icon;
			const preview = button.querySelector("i");

			if (preview) preview.className = icon;

			if (input) {
				input.value = toValue(field, icon);
				input.dispatchEvent(new Event("change", { bubbles: true }));
			}

			if (field && field.hasAttribute("data-sync")) sync(field);

			close();
			button.focus();
		});
	}

	document.addEventListener("click", function (e) {
		const clear = e.target.closest(".icon-picker-clear");

		if (clear) {
			const field = clear.closest(".udb-icon-field");
			const input = field && field.querySelector('input[type="hidden"]');

			if (input) {
				input.value = "";
				input.dispatchEvent(new Event("change", { bubbles: true }));
				sync(field);
			}

			return;
		}

		const button = e.target.closest(".icon-picker");

		if (!button) return;

		const isOpen = !!button.parentNode.querySelector(".icon-picker-container");

		if (isOpen) {
			close();
		} else {
			open(button);
		}
	});

	// Fields with data-sync, also the ones the menu editors add later.
	syncWithin(document.body);

	new MutationObserver(function (mutations) {
		mutations.forEach(function (mutation) {
			mutation.addedNodes.forEach(function (node) {
				if (1 === node.nodeType) syncWithin(node);
			});
		});
	}).observe(document.body, { childList: true, subtree: true });
})();
