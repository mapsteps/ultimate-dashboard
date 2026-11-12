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

				const current = input ? input.value : "";

				icons.forEach(function (icon) {
					const item = element("li", "");
					const choice = element("button", "icon-picker-item" + (icon === current ? " is-current" : ""));
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
				input.value = icon;
				input.dispatchEvent(new Event("change", { bubbles: true }));
			}

			close();
			button.focus();
		});
	}

	document.addEventListener("click", function (e) {
		const button = e.target.closest(".icon-picker");

		if (!button) return;

		const isOpen = !!button.parentNode.querySelector(".icon-picker-container");

		if (isOpen) {
			close();
		} else {
			open(button);
		}
	});
})();
