/**
 * @param {JQuery} $ The jQuery object.
 * @param {typeof window.udbAdminMenu} udbAdminMenu The UDB Admin Menu object.
 */
(function ($, udbAdminMenu) {
	if (!String.prototype.includes) {
		/**
		 * Polyfill for String.prototype.includes.
		 *
		 * @param {any} search The search string.
		 * @param {number} start The start index.
		 *
		 * @returns {boolean} Whether the string includes the search string.
		 */
		String.prototype.includes = function (search, start) {
			"use strict";

			if (search instanceof RegExp) {
				throw TypeError("first argument must not be a RegExp");
			}

			if (start === undefined) {
				start = 0;
			}

			return this.indexOf(search, start) !== -1;
		};
	}

	/**
	 * Find an HTML element by selector.
	 *
	 * @param {string} selector The selector.
	 * @returns {HTMLElement | null} The element or null if not found.
	 */
	function findHtmlEl(selector) {
		const el = document.querySelector(selector);

		if (el instanceof HTMLElement) {
			return el;
		}

		return null;
	}

	/**
	 * Find HTML elements by selector.
	 *
	 * @param {string} selector The selector.
	 * @returns {HTMLElement[]} The HTML elements.
	 */
	function findHtmlEls(selector) {
		const nodes = document.querySelectorAll(selector);
		const els = [];

		for (let i = 0; i < nodes.length; i++) {
			const el = nodes[i];

			if (el instanceof HTMLElement) {
				els.push(el);
			}
		}

		return els;
	}

	/** @type {number[]} */
	const savedUsers = [];

	/** @type {string[]} */
	const loadedRoleMenu = [];

	const searchBox = findHtmlEl(".udb-menu-builder-box--search-box");
	const roleTabs = findHtmlEl(".udb-menu-builder--role-tabs");
	const userTabs = findHtmlEl(".udb-menu-builder--user-tabs");
	const userTabsMenu = userTabs?.querySelector(".udb-menu-builder--user-menu");
	const userTabsContent = userTabs?.querySelector(
		".udb-menu-builder--edit-area"
	);

	let usersLoaded = false;

	/**
	 * Init the script.
	 * Call the main functions here.
	 */
	function init() {
		// Load administrator's menu as it's shown in initial load.
		getMenu("role", "administrator");

		const savedUserTabsContentItems = userTabsContent?.querySelectorAll(
			".udb-menu-builder--tab-content-item"
		);

		savedUserTabsContentItems?.forEach(function (item) {
			if (!(item instanceof HTMLElement)) return;
			savedUsers.push(parseInt(item.dataset.userId ?? "", 10));
			getMenu("user_id", item.dataset.userId ?? "");
		});

		document
			.querySelector(".udb-menu-builder--edit-form")
			?.addEventListener("submit", submitForm);

		$(document).on("click", ".udb-menu-builder--tab-menu-item", switchTab);
		$(document).on("click", ".udb-menu-builder--remove-tab", removeTab);
		$(document).on(
			"click",
			".udb-menu-builder-box--header-tab a",
			switchHeaderTab
		);
		checkHeaderTabState();

		$(document).on(
			"click",
			".udb-menu-builder--menu-actions .expand-menu",
			expandCollapseMenuItem
		);

		$(document).on(
			"click",
			".udb-menu-builder--menu-name",
			expandCollapseMenuItem
		);

		$(document).on(
			"click",
			".udb-menu-builder--menu-actions .hide-menu",
			showHideMenuItem
		);

		setupUsersSelect2();
	}

	function setupUsersSelect2() {
		if (usersLoaded) return;
		loadUsers();
	}

	/**
	 * Switch header tab.
	 *
	 * @param {JQuery.ClickEvent} e The event object.
	 */
	function switchHeaderTab(e) {
		var tabs = findHtmlEls(".udb-menu-builder-box--header-tab");
		if (!tabs.length) return;

		var tabMenuItem = e.target.parentNode;

		tabs.forEach(function (tab) {
			if (tab !== tabMenuItem) {
				tab.classList.remove("is-active");
			}
		});

		tabMenuItem.classList.add("is-active");

		if (tabMenuItem.dataset.headerTab === "users") {
			searchBox?.classList.remove("is-hidden");
			userTabs?.classList.remove("is-hidden");
			roleTabs?.classList.add("is-hidden");
		} else {
			searchBox?.classList.add("is-hidden");
			userTabs?.classList.add("is-hidden");
			roleTabs?.classList.remove("is-hidden");
		}
	}

	function checkHeaderTabState() {
		var hash = window.location.hash.substr(1);
		if (!hash) return;

		$(".udb-menu-builder-box--header-tab").removeClass("is-active");

		if (hash === "users-menu") {
			$('.udb-menu-builder-box--header-tab[data-header-tab="users"]').addClass(
				"is-active"
			);
			searchBox?.classList.remove("is-hidden");
			userTabs?.classList.remove("is-hidden");
			roleTabs?.classList.add("is-hidden");
		} else {
			$('.udb-menu-builder-box--header-tab[data-header-tab="roles"]').addClass(
				"is-active"
			);
			searchBox?.classList.add("is-hidden");
			userTabs?.classList.add("is-hidden");
			roleTabs?.classList.remove("is-hidden");
		}
	}

	/** @type {JQuery<HTMLElement>|null} */
	let usersSelect2 = null;

	/** @type {UdbSelect2Option[]} */
	let usersData = [];

	/**
	 * Load users select2 data via ajax.
	 */
	function loadUsers() {
		$.ajax({
			type: "get",
			url: window.ajaxurl,
			cache: false,
			data: {
				action: "udb_admin_menu_get_users",
				nonce: udbAdminMenu?.nonces.getUsers,
			},
		})
			.done(
				/** @param {UdbAdminMenuUserListResponse} r */
				function (r) {
					if (!r.success) return;

					var field = findHtmlEl(".udb-menu-builder--search-user");
					if (!(field instanceof HTMLSelectElement)) return;

					field.options[0].innerHTML = wp.escapeHtml.escapeEditableHTML(
						field.dataset.placeholder ?? ""
					);

					field.disabled = false;

					usersData = r.data.map((data) => {
						return {
							id: Number(data.id),
							text: data.text,
						};
					});

					usersData.forEach(function (data, index) {
						if (savedUsers.indexOf(Number(data.id)) >= 0) {
							usersData[index].disabled = true;
						}
					});

					usersSelect2 = $(field).select2({
						placeholder: field.dataset.placeholder,
						data: usersData,
					});

					$(field).on("select2:select", onUserSelected);

					usersLoaded = true;
				}
			)
			.fail(function () {
				console.log("Failed to load users");
			})
			.always(function () {
				//
			});
	}

	/**
	 * Event handler to run when a user (inside select2) is selected.
	 *
	 * @param {Select2.Event<HTMLSelectElement, Select2.DataParams>} e The event object.
	 */
	function onUserSelected(e) {
		appendUserTabsMenu(e.params.data);
		appendUserTabsContent(e.params.data);

		usersData.forEach(function (data, index) {
			if (data.id == Number(e.params.data.id)) {
				usersData[index].disabled = true;
			}
		});

		usersSelect2?.select2("destroy");
		usersSelect2?.empty();

		usersSelect2?.select2({
			placeholder: usersSelect2?.data("placeholder"),
			data: usersData,
		});

		getMenu("user_id", e.params.data.id);
	}

	/**
	 * Build user tab menu item template string and append it to user tab menu.
	 *
	 * @param {Select2.OptionData} data The id and text pair (select2 data format).
	 */
	function appendUserTabsMenu(data) {
		let template = udbAdminMenu?.templates.userTabMenu ?? "";

		template = template.replace(/{user_id}/g, data.id);
		template = template.replace(/{display_name}/g, data.text);

		userTabsMenu
			?.querySelectorAll(".udb-menu-builder--tab-menu-item")
			?.forEach(function (el) {
				el.classList.remove("is-active");
			});

		if (userTabsMenu) {
			$(userTabsMenu).append(template);
		}
	}

	/**
	 * Build user tab menu item template string and append it to user tab menu.
	 *
	 * @param {Select2.OptionData} data The id and text pair (select2 data format).
	 */
	function appendUserTabsContent(data) {
		var template = udbAdminMenu?.templates.userTabContent ?? "";

		template = template.replace(/{user_id}/g, data.id);

		document
			.querySelectorAll(
				".udb-menu-builder--user-tabs > .udb-menu-builder--tab-content > .udb-menu-builder--tab-content-item"
			)
			.forEach(function (el) {
				el.classList.remove("is-active");
			});

		if (userTabsContent) {
			$(userTabsContent).append(template);
		}
	}

	/**
	 * Switch tabs.
	 *
	 * @param {JQuery.ClickEvent} e The event object.
	 * @this {HTMLElement}
	 */
	function switchTab(e) {
		if (e.target.classList.contains("delete-icon")) return;
		const tabArea = this.parentElement?.parentElement;
		const tabId = this.dataset.udbTabContent;

		if (!(tabArea instanceof HTMLElement)) return;

		let tabHasIdByDefault = false;

		if (tabArea.id) {
			tabHasIdByDefault = true;
		} else {
			tabArea.id =
				"udb-menu-builder--tab" + Math.random().toString(36).substring(7);
		}

		var menus = findHtmlEls(
			"#" +
				tabArea.id +
				" > .udb-menu-builder--tab-menu > .udb-menu-builder--tab-menu-item"
		);
		var contents = findHtmlEls(
			"#" +
				tabArea.id +
				" > .udb-menu-builder--tab-content > .udb-menu-builder--tab-content-item"
		);

		if (!tabHasIdByDefault) tabArea.removeAttribute("id");

		menus.forEach(function (menu) {
			if (menu.dataset.udbTabContent !== tabId) {
				menu.classList.remove("is-active");
			} else {
				menu.classList.add("is-active");
			}
		});

		contents.forEach(function (content) {
			if (content.id !== tabId) {
				content.classList.remove("is-active");
			} else {
				content.classList.add("is-active");
			}
		});

		if (this.parentElement?.classList.contains("udb-menu-builder--role-menu")) {
			if (
				this.dataset.role &&
				loadedRoleMenu.indexOf(this.dataset.role) === -1
			) {
				getMenu("role", this.dataset.role);
			}
		}
	}

	/**
	 * Remove tab.
	 *
	 * @param {JQuery.ClickEvent} e The event object.
	 * @this {HTMLElement}
	 */
	function removeTab(e) {
		const menuItem = this.parentElement;
		const tabArea = menuItem?.parentElement?.parentElement;
		const menuWrapper = tabArea?.querySelector(".udb-menu-builder--tab-menu");
		const contentWrapper = tabArea?.querySelector(
			".udb-menu-builder--tab-content"
		);

		usersData.forEach(function (data, index) {
			if (data.id == Number(menuItem?.dataset.userId ?? "")) {
				usersData[index].disabled = false;
			}
		});

		usersSelect2?.select2("destroy");
		usersSelect2?.empty();

		usersSelect2?.select2({
			placeholder: usersSelect2.data("placeholder"),
			data: usersData,
		});

		if (menuItem) menuWrapper?.removeChild(menuItem);

		const tabContentKey = this.parentElement?.dataset.udbTabContent;
		const tabContent = tabArea?.querySelector("#" + tabContentKey);

		if (tabContent) tabContent.remove();

		if (
			contentWrapper?.querySelectorAll(".udb-menu-builder--tab-content-item")
				.length === 1
		) {
			document
				.querySelector("#udb-menu-builder--user-empty-edit-area")
				?.classList.add("is-active");
		}
	}

	/**
	 * Get menu & submenu either by role or user id.
	 *
	 * @param {"role"|"user_id"} by The identifier, could be "role" or "user_id".
	 * @param {string|number} value The specified role or user id.
	 */
	function getMenu(by, value) {
		/** @type {UdbAdminMenuGetMenuParams} */
		const data = {};

		data.action = "udb_admin_menu_get_menu";
		data.nonce = udbAdminMenu?.nonces.getMenu ?? "";

		if (by === "role") {
			data.role = String(value);
		} else if (by === "user_id") {
			data.user_id = Number(value);
		}

		$.ajax({
			url: window.ajaxurl,
			type: "post",
			dataType: "json",
			data: data,
		})
			.done(
				/** @param {UdbAdminMenuGetMenuResponse} r */
				function (r) {
					if (!r || !r.success) return;

					if (
						by === "role" &&
						typeof value === "string" &&
						loadedRoleMenu.indexOf(value) === -1
					) {
						loadedRoleMenu.push(value);
					}

					buildMenu(by, String(value), r.data);
				}
			)
			.always(function () {
				//
			});
	}

	/**
	 * Build menu list.
	 *
	 * @param {"role"|"user_id"} by The identifier, could be "role" or "user_id".
	 * @param {string} value The specified role or user id.
	 * @param {UdbAdminMenuItem[]} menuList The menu list returned from ajax response.
	 */
	function buildMenu(by, value, menuList) {
		const identifier = by === "role" ? value : "user-" + value;
		const editArea = findHtmlEl(
			"#udb-menu-builder--" + identifier + "-edit-area"
		);
		if (!editArea) return;

		const listArea = editArea.querySelector(".udb-menu-builder--menu-list");
		if (!(listArea instanceof HTMLElement)) return;

		let builtMenu = "";

		menuList.forEach(function (menu) {
			builtMenu += replaceMenuPlaceholders(by, value, menu);
		});

		listArea.innerHTML = builtMenu;

		setupMenuItems(listArea);

		var submenuList = listArea.querySelectorAll(
			".udb-menu-builder--submenu-list"
		);

		if (submenuList.length) {
			submenuList.forEach(function (submenu) {
				setupMenuItems(submenu, true);
			});
		}
	}

	/**
	 * Replace menu placeholders.
	 *
	 * @param {string} by Either by role or user_id.
	 * @param {string} value The role or user_id value.
	 * @param {UdbAdminMenuItem} menu The menu item.
	 */
	function replaceMenuPlaceholders(by, value, menu) {
		var template;
		var submenuTemplate;
		var icon;

		const wasAdded = Number(menu.was_added) ? true : false;
		const isHidden = Number(menu.is_hidden) ? true : false;

		if (menu.type === "separator") {
			template = udbAdminMenu?.templates.menuSeparator ?? "";
			template = template.replace(/{separator}/g, menu.url_default);

			template = template.replace(/{menu_is_hidden}/g, String(menu.is_hidden));
			template = template.replace(
				/{trash_icon}/g,
				wasAdded
					? '<span class="dashicons dashicons-trash udb-menu-builder--remove-menu-item"></span>'
					: ""
			);
			template = template.replace(
				/{hidden_icon}/g,
				isHidden ? "hidden" : "visibility"
			);
			template = template.replace(/{menu_was_added}/g, String(menu.was_added));
			template = template.replace(/{default_menu_id}/g, menu.id_default);
			template = template.replace(/{default_menu_url}/g, menu.url_default);
		} else {
			template = udbAdminMenu?.templates.menuList ?? "";
			template = template.replace(/{menu_title}/g, menu.title);
			template = template.replace(/{default_menu_title}/g, menu.title_default);

			var parsedTitle = menu.title ? menu.title : menu.title_default;
			template = template.replace(/{parsed_menu_title}/g, parsedTitle);

			template = template.replace(/{menu_url}/g, menu.url);
			template = template.replace(/{default_menu_url}/g, menu.url_default);

			template = template.replace(/{menu_id}/g, menu.id);
			template = template.replace(/{default_menu_id}/g, menu.id_default);

			template = template.replace(/{menu_dashicon}/g, menu.dashicon);
			template = template.replace(
				/{default_menu_dashicon}/g,
				menu.dashicon_default
			);

			template = template.replace(/{menu_icon_svg}/g, menu.icon_svg);
			template = template.replace(
				/{default_menu_icon_svg}/g,
				menu.icon_svg_default
			);

			template = template.replace(/{menu_is_hidden}/g, String(menu.is_hidden));
			template = template.replace(/{trash_icon}/g, "");
			template = template.replace(
				/{hidden_icon}/g,
				isHidden ? "hidden" : "visibility"
			);
			template = template.replace(/{menu_was_added}/g, String(menu.was_added));

			const menuIconSuffix =
				menu.icon_type && menu[menu.icon_type] ? "" : "_default";

			/** @type {"icon_type_default" | "icon_type"} */
			const iconTypeKey = `icon_type${menuIconSuffix}`;

			/** @type {"icon_svg_default" | "icon_svg"} */
			const iconSvgKey = `icon_svg${menuIconSuffix}`;

			/** @type {"dashicon_default" | "dashicon"} */
			const dashiconKey = `dashicon${menuIconSuffix}`;

			if (menu[iconTypeKey] === "icon_svg") {
				icon = '<img alt="" src="' + menu[iconSvgKey] + '">';
				template = template.replace(/{icon_svg_tab_is_active}/g, "is-active");
				template = template.replace(/{dashicon_tab_is_active}/g, "");
			} else {
				icon = '<i class="dashicons ' + menu[dashiconKey] + '"></i>';
				template = template.replace(/{icon_svg_tab_is_active}/g, "");
				template = template.replace(/{dashicon_tab_is_active}/g, "is-active");
			}

			template = template.replace(/{menu_icon}/g, icon);

			if (menu.submenu) {
				submenuTemplate = buildSubmenu(by, value, menu);
				template = template.replace(/{submenu_template}/g, submenuTemplate);
			} else {
				template = template.replace(/{submenu_template}/g, "");
			}
		}

		if (by === "role") {
			template = template.replace(/{role}/g, value);
		} else if (by === "user_id") {
			template = template.replace(/{role}/g, "user-" + value);
			template = template.replace(/{user_id}/g, value);
		}

		return template;
	}

	/**
	 * Build submenu list.
	 *
	 * @param {string} by The identifier, could be "role" or "user_id".
	 * @param {string} value The specified role or user id.
	 * @param {UdbAdminMenuItem} menu The menu item which contains the submenu list.
	 *
	 * @return {string} template The submenu template.
	 */
	function buildSubmenu(by, value, menu) {
		var template = "";

		menu.submenu?.forEach(function (submenu) {
			template += replaceSubmenuPlaceholders(by, value, submenu, menu);
		});

		return template;
	}

	/**
	 * Replace submenu placeholders.
	 *
	 * @param {string} by Either by role or user_id.
	 * @param {string} value The role or user_id value.
	 * @param {UdbAdminMenuSubmenuItem} submenu The submenu item.
	 * @param {UdbAdminMenuItem} menu The menu item which contains the submenu list.
	 */
	function replaceSubmenuPlaceholders(by, value, submenu, menu) {
		const submenuIsHidden = Number(submenu.is_hidden) ? true : false;

		let template = udbAdminMenu?.templates.submenuList ?? "";

		if (by === "role") {
			template = template.replace(/{role}/g, value);
		} else if (by === "user_id") {
			template = template.replace(/{role}/g, "user-" + value);
			template = template.replace(/{user_id}/g, value);
		}

		template = template.replace(/{default_menu_id}/g, menu.id_default);

		let submenuId = submenu.url ? submenu.url : submenu.url_default;
		submenuId = submenuId.replace(/\//g, "udbslashsign");
		template = template.replace(/{submenu_id}/g, submenuId);

		template = template.replace(/{submenu_title}/g, submenu.title);
		template = template.replace(
			/{default_submenu_title}/g,
			submenu.title_default
		);

		const parsedTitle = submenu.title ? submenu.title : submenu.title_default;
		template = template.replace(/{parsed_submenu_title}/g, parsedTitle);

		template = template.replace(/{submenu_url}/g, submenu.url);
		template = template.replace(/{default_submenu_url}/g, submenu.url_default);

		template = template.replace(
			/{submenu_is_hidden}/g,
			String(submenu.is_hidden)
		);
		template = template.replace(/{trash_icon}/g, "");
		template = template.replace(
			/{hidden_icon}/g,
			submenuIsHidden ? "hidden" : "visibility"
		);
		template = template.replace(
			/{submenu_was_added}/g,
			String(submenu.was_added)
		);

		return template;
	}

	/**
	 * Setup menu items.
	 *
	 * @param {Element} listArea The list area element.
	 * @param {boolean} [isSubmenu] Whether the list area is a submenu list.
	 */
	function setupMenuItems(listArea, isSubmenu) {
		setupSortable(listArea, isSubmenu);

		if (!isSubmenu) {
			setupItemChanges(listArea);
			$(listArea).find(".dashicons-picker").dashiconsPicker();
		}
	}

	/**
	 * Sortable setup for both active & available widgets.
	 *
	 * @param {Element} listArea The list area element.
	 * @param {boolean} [isSubmenu] Whether the list area is a submenu list.
	 */
	function setupSortable(listArea, isSubmenu) {
		$(listArea).sortable({
			connectWith: isSubmenu ? ".udb-menu-builder--submenu-list" : false,
			receive: function (e, ui) {
				//
			},
			update: function (e, ui) {
				//
			},
		});
	}

	/**
	 * Expand / collapse menu item.
	 *
	 * @param {JQuery.ClickEvent} e The event object.
	 * @this {HTMLElement}
	 */
	function expandCollapseMenuItem(e) {
		const parent = this.classList.contains("expand-menu")
			? this.parentElement?.parentElement?.parentElement
			: this.parentElement?.parentElement;

		const target = parent?.querySelector(".udb-menu-builder--expanded-panel");
		if (!target) return;

		if (parent?.classList.contains("is-expanded")) {
			$(target)
				.stop()
				.slideUp(350, function () {
					parent.classList.remove("is-expanded");
				});
		} else {
			$(target)
				.stop()
				.slideDown(350, function () {
					parent?.classList.add("is-expanded");
				});
		}
	}

	/**
	 * show / hide menu item.
	 *
	 * @param {JQuery.ClickEvent} e The event object.
	 * @this {HTMLElement}
	 */
	function showHideMenuItem(e) {
		const parent = this.parentElement?.parentElement?.parentElement;
		if (!parent) return;

		const isHidden = parent.dataset.hidden === "1" ? true : false;

		if (isHidden) {
			this.classList.add("dashicons-visibility");
			this.classList.remove("dashicons-hidden");
			parent.dataset.hidden = "0";
		} else {
			parent.dataset.hidden = "1";
			this.classList.remove("dashicons-visibility");
			this.classList.add("dashicons-hidden");
		}
	}

	/**
	 * Setup item changes.
	 * @param {Element} listArea The list area element.
	 */
	function setupItemChanges(listArea) {
		var menuItems = listArea.querySelectorAll(".udb-menu-builder--menu-item");
		if (!menuItems.length) return;

		menuItems.forEach(function (menuItem) {
			if (!(menuItem instanceof HTMLElement)) return;
			setupItemChange(menuItem);
		});
	}

	/**
	 * Setup item change.
	 *
	 * @param {Element} menuItem The menu item element.
	 */
	function setupItemChange(menuItem) {
		const iconFields = menuItem.querySelectorAll(
			".udb-menu-builder--icon-field"
		);

		iconFields.forEach(function (field) {
			if (
				!(field instanceof HTMLInputElement) &&
				!(field instanceof HTMLTextAreaElement)
			) {
				return;
			}

			field.addEventListener(
				"change",
				/** @this {HTMLInputElement | HTMLTextAreaElement} */
				function () {
					const iconWrapper = menuItem.querySelector(
						".udb-menu-builder--menu-icon"
					);

					let iconOutput = "";

					if (this.dataset.name === "dashicon") {
						iconOutput =
							'<i class="dashicons ' +
							wp.escapeHtml.escapeEditableHTML(this.value) +
							'"></i>';
					} else if (this.dataset.name === "icon_svg") {
						const maybeEncodedSvg = this.value.replace(
							"data:image/svgxml",
							"data:image/svg+xml"
						);

						const isValidSvgDataUrl =
							/^data:image\/svg\+xml;base64,[a-zA-Z0-9+/=]+$/.test(
								maybeEncodedSvg
							);

						iconOutput = isValidSvgDataUrl
							? '<img src="' +
								wp.escapeHtml.escapeHTML(maybeEncodedSvg) +
								'" alt="" />'
							: "<em>Invalid SVG Data URI</em>";
					}

					if (iconWrapper) {
						iconWrapper.innerHTML = iconOutput;
					}
				}
			);
		});

		const titleFields = menuItem.querySelectorAll('[data-name="menu_title"]');

		titleFields.forEach(function (field) {
			if (
				!(field instanceof HTMLInputElement) &&
				!(field instanceof HTMLTextAreaElement)
			) {
				return;
			}

			field.addEventListener(
				"change",
				/** @this {HTMLInputElement | HTMLTextAreaElement} */
				function () {
					const menuNameEl = menuItem.querySelector(
						".udb-menu-builder--menu-name"
					);

					if (menuNameEl) {
						menuNameEl.innerHTML = wp.escapeHtml.escapeHTML(this.value);
					}
				}
			);
		});
	}

	/**
	 * Function to execute on form submission.
	 *
	 * @param {Event} e The on submit event.
	 */
	function submitForm(e) {
		e.preventDefault();
	}

	init();
})(jQuery, window.udbAdminMenu);
