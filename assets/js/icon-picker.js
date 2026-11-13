/**
 * Icon picker (same picker as Better Admin Bar's): a button showing the icon; clicking it opens a
 * searchable grid of icons below it: the Lucide icons, then WordPress' Dashicons.
 *
 * Markup:
 * <div class="udb-icon-field">
 *   <button type="button" class="icon-picker"><svg …></svg> or <i class="dashicons dashicons-menu"></i></button>
 *   <input type="hidden" name="…" value="gauge">
 * </div>
 *
 * Stored values: a Lucide icon name ("gauge") or Dashicons classes ("dashicons dashicons-menu").
 * Picking an icon sets the hidden input (and fires "change" on it) and the button's icon.
 * The icons are loaded when a picker is opened the first time.
 *
 * Options on .udb-icon-field (used by the admin menu & admin bar editors):
 * - data-icon-set="dashicons": only offer Dashicons.
 * - data-value="name": store the short name ("dashicons-menu") instead of the classes.
 * - data-sync: the button's icon is set from the input (value, or its data-default), also for
 *   fields added later by a script. A .icon-picker-clear button inside empties the input.
 *
 * Used global object: udbIconPicker { lucideUrl, tagsUrl, dashiconsUrl, labels }.
 */
(function () {
	"use strict";

	const settings = window.udbIconPicker || { labels: {} };
	const labels = settings.labels || {};
	const requests = {};
	const data = { lucide: null, tags: {}, dashicons: null };

	// Lucide icons shown first, the rest follows in alphabetical order.
	const popular = [
		"gauge", "layout-dashboard", "square-pen", "pencil", "paintbrush", "palette", "file-plus", "file-text",
		"images", "image", "plug", "settings", "log-out", "house", "users", "user", "message-square", "mail",
		"shopping-cart", "store", "chart-column", "search", "link", "external-link", "globe", "layout-template",
		"folder", "calendar", "bell", "star", "heart", "lock", "shield", "wrench", "database", "code", "zap", "rocket",
	];

	/**
	 * Fetch a JSON file (once).
	 *
	 * @param {string} key The name of the request.
	 * @param {string} url The URL.
	 * @returns {Promise<*>}
	 */
	function fetchJson(key, url) {
		if (!requests[key]) {
			requests[key] = fetch(url)
				.then(function (response) {
					if (!response.ok) throw new Error(response.statusText);
					return response.json();
				})
				.catch(function (error) {
					// Try again next time.
					delete requests[key];
					throw error;
				});
		}

		return requests[key];
	}

	/**
	 * Load the icons a picker needs.
	 *
	 * @param {boolean} dashiconsOnly Whether only the Dashicons are needed.
	 * @returns {Promise}
	 */
	function load(dashiconsOnly) {
		const loading = [
			fetchJson("dashicons", settings.dashiconsUrl).then((list) => (data.dashicons = list)),
		];

		if (!dashiconsOnly) {
			loading.push(
				fetchJson("lucide", settings.lucideUrl).then((icons) => (data.lucide = icons)),
				fetchJson("tags", settings.tagsUrl).then((tags) => (data.tags = tags || {}))
			);
		}

		return Promise.all(loading);
	}

	/**
	 * Whether a stored value is a Dashicon.
	 *
	 * @param {string} value The stored value.
	 * @returns {boolean}
	 */
	function isDashicon(value) {
		return 0 === value.indexOf("dashicons");
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
	 * Get the element showing an icon.
	 *
	 * @param {string} icon A Lucide name or Dashicons classes ("dashicons dashicons-menu").
	 * @returns {Element}
	 */
	function iconElement(icon) {
		if (isDashicon(icon)) {
			const i = element("i", icon);
			i.setAttribute("aria-hidden", "true");
			return i;
		}

		const template = document.createElement("template");

		template.innerHTML =
			'<svg class="udb-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' +
			(data.lucide && data.lucide[icon] ? data.lucide[icon] : "") +
			"</svg>";

		const svg = template.content.firstChild;
		svg.setAttribute("data-icon", icon);
		return svg;
	}

	/**
	 * Show an icon on the picker button.
	 *
	 * @param {HTMLButtonElement} button The .icon-picker button.
	 * @param {string} icon A Lucide name or Dashicons classes; empty for none.
	 */
	function setPreview(button, icon) {
		const current = button.querySelector("i, svg");
		const preview = icon ? iconElement(icon) : element("i", "");

		preview.setAttribute("aria-hidden", "true");

		if (current) {
			current.replaceWith(preview);
		} else {
			button.append(preview);
		}
	}

	/**
	 * The value to store for an icon.
	 *
	 * @param {HTMLElement|null} field The .udb-icon-field.
	 * @param {string} icon A Lucide name or Dashicons classes.
	 * @returns {string}
	 */
	function toValue(field, icon) {
		return field && "name" === field.dataset.value && isDashicon(icon) ? icon.split(" ").pop() : icon;
	}

	/**
	 * The icon for a stored value.
	 *
	 * @param {HTMLElement|null} field The .udb-icon-field.
	 * @param {string} value The stored value.
	 * @returns {string}
	 */
	function toIcon(field, value) {
		if (!value || !field || "name" !== field.dataset.value) return value;

		return value.split("-")[0] + " " + value;
	}

	/**
	 * Show the input's icon on the button (and the clear button only when there's something to clear).
	 * Used by the menu editors, which only offer Dashicons.
	 *
	 * @param {HTMLElement} field The .udb-icon-field.
	 */
	function sync(field) {
		const input = field.querySelector('input[type="hidden"]');
		const button = field.querySelector(".icon-picker");
		const clear = field.querySelector(".icon-picker-clear");

		if (!input) return;

		const value = input.value || input.dataset.default || "";

		if (button) setPreview(button, toIcon(field, value));
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
	 * Get the icons of a picker, in order.
	 *
	 * @param {boolean} dashiconsOnly Whether to only offer Dashicons.
	 * @returns {Array<{icon: string, name: string, search: string, group: string}>}
	 */
	function iconList(dashiconsOnly) {
		const list = [];

		if (!dashiconsOnly && data.lucide) {
			const first = popular.filter((name) => data.lucide.hasOwnProperty(name));
			const names = first.concat(
				Object.keys(data.lucide)
					.sort()
					.filter((name) => first.indexOf(name) === -1)
			);

			names.forEach(function (name) {
				list.push({
					icon: name,
					name: name,
					search: (name.replace(/-/g, " ") + " " + (data.tags[name] || "")).toLowerCase(),
					group: "lucide",
				});
			});
		}

		(data.dashicons || []).forEach(function (icon) {
			const name = icon.split(" ").pop();

			list.push({
				icon: icon,
				name: name,
				search: "dashicons " + name.replace(/^dashicons-/, "").replace(/-/g, " "),
				group: "dashicons",
			});
		});

		return list;
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
		const dashiconsOnly = !!field && "dashicons" === field.dataset.iconSet;
		const popup = element("div", "icon-picker-container");
		const control = element("div", "icon-picker-control");
		const search = element("input", "icon-picker-search");
		const message = element("p", "icon-picker-message");
		const list = element("ul", "icon-picker-list");
		let loaded = false;

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

		load(dashiconsOnly).then(
			function () {
				if (!popup.isConnected) return;

				const current = input ? input.value || input.dataset.default || "" : "";
				const fragment = document.createDocumentFragment();
				let group = "";

				iconList(dashiconsOnly).forEach(function (item) {
					// A heading above each set, when both are offered.
					if (!dashiconsOnly && item.group !== group) {
						const heading = element("li", "icon-picker-group");

						heading.textContent = labels[item.group] || "";
						fragment.append(heading);
						group = item.group;
					}

					const li = element("li", "");
					const choice = element(
						"button",
						"icon-picker-item" + (toValue(field, item.icon) === current ? " is-current" : "")
					);

					li.dataset.search = item.search;
					choice.type = "button";
					choice.dataset.icon = item.icon;
					choice.title = item.name;
					choice.setAttribute("aria-label", item.name);
					choice.append(iconElement(item.icon));
					li.append(choice);
					fragment.append(li);
				});

				list.append(fragment);
				loaded = true;
				message.textContent = "";
				filter();
			},
			function () {
				if (popup.isConnected) message.textContent = labels.error || "";
			}
		);

		/**
		 * Show the icons that match the search (the set headings only without a search).
		 */
		function filter() {
			const words = search.value.toLowerCase().trim().split(/\s+/).filter(Boolean);
			let found = 0;

			Array.from(list.children).forEach(function (li) {
				if (li.classList.contains("icon-picker-group")) {
					li.hidden = words.length > 0;
					return;
				}

				const match = words.every((word) => li.dataset.search.indexOf(word) !== -1);

				li.hidden = !match;
				if (match) found++;
			});

			if (loaded) message.textContent = found ? "" : labels.none || "";
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

			setPreview(button, icon);
			if (field) field.classList.remove("is-empty");

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
