/**
 * Used global objects:
 * - jQuery
 * - ajaxurl
 */
(function ($) {
	if (window.NodeList && !NodeList.prototype.forEach) {
		NodeList.prototype.forEach = Array.prototype.forEach;
	}

	if (!String.prototype.includes) {
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

	var elms = {};
	var state = {};
	var usersSelect2 = null;
	var usersData = [];
	var savedUsers = [];
	var loadedRoleMenu = [];

	/**
	 * Init the script.
	 * Call the main functions here.
	 */
	function init() {
		elms.searchBox = document.querySelector(".udb-admin-menu-box--search-box");
		elms.roleTabs = document.querySelector(".udb-admin-menu--role-tabs");
		elms.userTabs = document.querySelector(".udb-admin-menu--user-tabs");
		elms.userTabsMenu = elms.userTabs.querySelector(
			".udb-admin-menu--user-menu"
		);
		elms.userTabsContent = elms.userTabs.querySelector(
			".udb-admin-menu--edit-area"
		);

		state.usersLoaded = false;

		// Load administrator's menu as it's shown in initial load.
		getMenu("role", "administrator");

		var savedUserTabsContentItems = elms.userTabsContent.querySelectorAll(
			".udb-admin-menu--tab-content-item"
		);

		savedUserTabsContentItems.forEach(function (item) {
			savedUsers.push(parseInt(item.dataset.userId, 10));
			getMenu("user_id", item.dataset.userId);
		});

		document
			.querySelector(".udb-admin-menu--edit-form")
			.addEventListener("submit", submitForm);

		$(document).on("click", ".udb-admin-menu--tab-menu-item", switchTab);
		$(document).on("click", ".udb-admin-menu--remove-tab", removeTab);
		$(document).on(
			"click",
			".udb-admin-menu-box--header-tab a",
			switchHeaderTab
		);
		checkHeaderTabState();
		$(document).on(
			"click",
			".udb-admin-menu--expand-menu",
			expandCollapseMenuItem
		);
		$(document).on("click", ".hide-menu", showHideMenuItem);

		setupUsersSelect2();
	}

	function setupUsersSelect2() {
		if (state.usersLoaded) return;
		loadUsers();
	}

	function switchHeaderTab(e) {
		var tabs = document.querySelectorAll(".udb-admin-menu-box--header-tab");
		if (!tabs.length) return;

		var tabMenuItem = e.target.parentNode;

		tabs.forEach(function (tab) {
			if (tab !== tabMenuItem) {
				tab.classList.remove("is-active");
			}
		});

		tabMenuItem.classList.add("is-active");

		if (tabMenuItem.dataset.headerTab === "users") {
			elms.searchBox.classList.remove("is-hidden");
			elms.userTabs.classList.remove("is-hidden");
			elms.roleTabs.classList.add("is-hidden");
		} else {
			elms.searchBox.classList.add("is-hidden");
			elms.userTabs.classList.add("is-hidden");
			elms.roleTabs.classList.remove("is-hidden");
		}
	}

	function checkHeaderTabState() {
		var hash = window.location.hash.substr(1);
		if (!hash) return;

		$(".udb-admin-menu-box--header-tab").removeClass("is-active");

		if (hash === "users-menu") {
			$('.udb-admin-menu-box--header-tab[data-header-tab="users"]').addClass(
				"is-active"
			);
			elms.searchBox.classList.remove("is-hidden");
			elms.userTabs.classList.remove("is-hidden");
			elms.roleTabs.classList.add("is-hidden");
		} else {
			$('.udb-admin-menu-box--header-tab[data-header-tab="roles"]').addClass(
				"is-active"
			);
			elms.searchBox.classList.add("is-hidden");
			elms.userTabs.classList.add("is-hidden");
			elms.roleTabs.classList.remove("is-hidden");
		}
	}

	/**
	 * Load users select2 data via ajax.
	 */
	function loadUsers() {
		$.ajax({
			type: "get",
			url: ajaxurl,
			cache: false,
			data: {
				action: "udb_admin_menu_get_users",
				nonce: udbAdminMenu.nonces.getUsers,
			},
		})
			.done(function (r) {
				if (!r.success) return;

				var field = document.querySelector(".udb-admin-menu--search-user");
				if (!field) return;

				field.options[0].innerHTML = field.dataset.placeholder;
				field.disabled = false;
				usersData = r.data;

				usersData.forEach(function (data, index) {
					if (savedUsers.indexOf(data.id) >= 0) {
						usersData[index].disabled = true;
					}
				});

				usersSelect2 = $(field).select2({
					placeholder: field.dataset.placeholder,
					data: usersData,
				});

				$(field).on("select2:select", onUserSelected);

				state.usersLoaded = true;
			})
			.fail(function () {
				console.log("Failed to load users");
			})
			.always(function () {
				//
			});
	}

	/**
	 * Event handler to run when a user (inside select2) is selected.
	 * @param {Event} e The event object.
	 */
	function onUserSelected(e) {
		appendUserTabsMenu(e.params.data);
		appendUserTabsContent(e.params.data);

		usersData.forEach(function (data, index) {
			if (data.id == e.params.data.id) {
				usersData[index].disabled = true;
			}
		});

		usersSelect2.select2("destroy");
		usersSelect2.empty();

		usersSelect2.select2({
			placeholder: usersSelect2.data("placeholder"),
			data: usersData,
		});

		getMenu("user_id", e.params.data.id);
	}

	/**
	 * Build user tab menu item template string and append it to user tab menu.
	 * @param {object} data The id and text pair (select2 data format).
	 */
	function appendUserTabsMenu(data) {
		var template = udbAdminMenu.templates.userTabMenu;

		template = template.replace(/{user_id}/g, data.id);
		template = template.replace(/{display_name}/g, data.text);

		elms.userTabsMenu
			.querySelectorAll(".udb-admin-menu--tab-menu-item")
			.forEach(function (el) {
				el.classList.remove("is-active");
			});

		$(elms.userTabsMenu).append(template);
	}

	/**
	 * Build user tab menu item template string and append it to user tab menu.
	 * @param {object} data The id and text pair (select2 data format).
	 */
	function appendUserTabsContent(data) {
		var template = udbAdminMenu.templates.userTabContent;

		template = template.replace(/{user_id}/g, data.id);

		document
			.querySelectorAll(
				".udb-admin-menu--user-tabs > .udb-admin-menu--tab-content > .udb-admin-menu--tab-content-item"
			)
			.forEach(function (el) {
				el.classList.remove("is-active");
			});

		$(elms.userTabsContent).append(template);
	}

	/**
	 * Switch tabs.
	 */
	function switchTab(e) {
		if (e.target.classList.contains("delete-icon")) return;
		var tabArea = this.parentNode.parentNode;
		var tabId = this.dataset.udbTabContent;

		var tabHasIdByDefault = false;

		if (tabArea.id) {
			tabHasIdByDefault = true;
		} else {
			tabArea.id =
				"udb-admin-menu--tab" + Math.random().toString(36).substring(7);
		}

		var menus = document.querySelectorAll(
			"#" +
				tabArea.id +
				" > .udb-admin-menu--tab-menu > .udb-admin-menu--tab-menu-item"
		);
		var contents = document.querySelectorAll(
			"#" +
				tabArea.id +
				" > .udb-admin-menu--tab-content > .udb-admin-menu--tab-content-item"
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

		if (this.parentNode.classList.contains("udb-admin-menu--role-menu")) {
			if (loadedRoleMenu.indexOf(this.dataset.role) === -1) {
				getMenu("role", this.dataset.role);
			}
		}
	}

	/**
	 * Remove tab.
	 * @param {Event} e The event object.
	 */
	function removeTab(e) {
		var tabArea = this.parentNode.parentNode.parentNode;
		var menuItem = this.parentNode;
		var menuWrapper = tabArea.querySelector(".udb-admin-menu--tab-menu");
		var contentWrapper = tabArea.querySelector(".udb-admin-menu--tab-content");

		usersData.forEach(function (data, index) {
			if (data.id == menuItem.dataset.userId) {
				usersData[index].disabled = false;
			}
		});

		usersSelect2.select2("destroy");
		usersSelect2.empty();

		usersSelect2.select2({
			placeholder: usersSelect2.data("placeholder"),
			data: usersData,
		});

		menuWrapper.removeChild(this.parentNode);
		contentWrapper.removeChild(
			tabArea.querySelector("#" + this.parentNode.dataset.udbTabContent)
		);

		if (
			contentWrapper.querySelectorAll(".udb-admin-menu--tab-content-item")
				.length === 1
		) {
			document
				.querySelector("#udb-admin-menu--user-empty-edit-area")
				.classList.add("is-active");
		}
	}

	/**
	 * Get menu & submenu either by role or user id.
	 *
	 * @param {string} by The identifier, could be "role" or "user_id".
	 * @param {string} value The specified role or user id.
	 */
	function getMenu(by, value) {
		var data = {};

		data.action = "udb_admin_menu_get_menu";
		data.nonce = udbAdminMenu.nonces.getMenu;
		data[by] = value;

		$.ajax({
			url: ajaxurl,
			type: "post",
			dataType: "json",
			data: data,
		})
			.done(function (r) {
				if (!r || !r.success) return;

				if (by === "role" && loadedRoleMenu.indexOf(value) === -1) {
					loadedRoleMenu.push(value);
				}

				buildMenu(by, value, r.data);
			})
			.always(function () {
				//
			});
	}

	/**
	 * Build menu list.
	 *
	 * @param {string} by The identifier, could be "role" or "user_id".
	 * @param {string} value The specified role or user id.
	 * @param {array} menuList The menu list returned from ajax response.
	 */
	function buildMenu(by, value, menuList) {
		var identifier = by === "role" ? value : "user-" + value;
		var editArea = document.querySelector(
			"#udb-admin-menu--" + identifier + "-edit-area"
		);
		if (!editArea) return;
		var listArea = editArea.querySelector(".udb-admin-menu--menu-list");
		var builtMenu = "";

		menuList.forEach(function (menu) {
			builtMenu += replaceMenuPlaceholders(by, value, menu);
		});

		listArea.innerHTML = builtMenu;

		setupMenuItems(listArea);

		var submenuList = listArea.querySelectorAll(
			".udb-admin-menu--submenu-list"
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
	 * @param {object} menu The menu item.
	 */
	function replaceMenuPlaceholders(by, value, menu) {
		var template;
		var submenuTemplate;
		var icon;

		if (menu.type === "separator") {
			template = udbAdminMenu.templates.menuSeparator;
			template = template.replace(/{separator}/g, menu.url_default);

			template = template.replace(/{menu_is_hidden}/g, menu.is_hidden);
			template = template.replace(
				/{trash_icon}/g,
				parseInt(menu.was_added, 10)
					? '<span class="dashicons dashicons-trash udb-admin-menu--remove-menu-item"></span>'
					: ""
			);
			template = template.replace(
				/{hidden_icon}/g,
				menu.is_hidden == "1" ? "hidden" : "visibility"
			);
			template = template.replace(/{menu_was_added}/g, menu.was_added);
			template = template.replace(/{default_menu_id}/g, menu.id_default);
			template = template.replace(/{default_menu_url}/g, menu.url_default);
		} else {
			template = udbAdminMenu.templates.menuList;
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

			template = template.replace(/{menu_is_hidden}/g, menu.is_hidden);
			template = template.replace(/{trash_icon}/g, "");
			template = template.replace(
				/{hidden_icon}/g,
				menu.is_hidden == "1" ? "hidden" : "visibility"
			);
			template = template.replace(/{menu_was_added}/g, menu.was_added);

			var menuIconSuffix =
				menu.icon_type && menu[menu.icon_type] ? "" : "_default";

			if (menu["icon_type" + menuIconSuffix] === "icon_svg") {
				icon = '<img alt="" src="' + menu["icon_svg" + menuIconSuffix] + '">';
				template = template.replace(/{icon_svg_tab_is_active}/g, "is-active");
				template = template.replace(/{dashicon_tab_is_active}/g, "");
			} else {
				icon =
					'<i class="dashicons ' + menu["dashicon" + menuIconSuffix] + '"></i>';
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
	 * @param {array} menu The menu item which contains the submenu list.
	 *
	 * @return {string} template The submenu template.
	 */
	function buildSubmenu(by, value, menu) {
		var template = "";

		menu.submenu.forEach(function (submenu) {
			template += replaceSubmenuPlaceholders(by, value, submenu, menu);
		});

		return template;
	}

	/**
	 * Replace submenu placeholders.
	 *
	 * @param {string} by Either by role or user_id.
	 * @param {string} value The role or user_id value.
	 * @param {object} submenu The submenu item.
	 * @param {array} menu The menu item which contains the submenu list.
	 */
	function replaceSubmenuPlaceholders(by, value, submenu, menu) {
		var template = udbAdminMenu.templates.submenuList;

		if (by === "role") {
			template = template.replace(/{role}/g, value);
		} else if (by === "user_id") {
			template = template.replace(/{role}/g, "user-" + value);
			template = template.replace(/{user_id}/g, value);
		}

		template = template.replace(/{default_menu_id}/g, menu.id_default);

		var submenuId = submenu.id ? submenu.id : submenu.url_default;
		submenuId = submenuId.replace(/\//g, "udbslashsign");
		template = template.replace(/{submenu_id}/g, submenuId);

		template = template.replace(/{submenu_title}/g, submenu.title);
		template = template.replace(
			/{default_submenu_title}/g,
			submenu.title_default
		);

		var parsedTitle = submenu.title ? submenu.title : submenu.title_default;
		template = template.replace(/{parsed_submenu_title}/g, parsedTitle);

		template = template.replace(/{submenu_url}/g, submenu.url);
		template = template.replace(/{default_submenu_url}/g, submenu.url_default);

		template = template.replace(/{submenu_is_hidden}/g, submenu.is_hidden);
		template = template.replace(/{trash_icon}/g, "");
		template = template.replace(
			/{hidden_icon}/g,
			submenu.is_hidden == "1" ? "hidden" : "visibility"
		);
		template = template.replace(/{submenu_was_added}/g, submenu.was_added);

		return template;
	}

	/**
	 * Setup menu items.
	 */
	function setupMenuItems(listArea, isSubmenu) {
		setupSortable(listArea);

		if (!isSubmenu) {
			setupItemChanges(listArea);
			$(listArea).find(".dashicons-picker").dashiconsPicker();
		}
	}

	/**
	 * Sortable setup for both active & available widgets.
	 */
	function setupSortable(listArea) {
		$(listArea).sortable({
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
	 * @param {Event} e The event object.
	 */
	function expandCollapseMenuItem(e) {
		var parent = this.parentNode.parentNode;
		var target = parent.querySelector(".udb-admin-menu--expanded-panel");

		if (parent.classList.contains("is-expanded")) {
			$(target)
				.stop()
				.slideUp(350, function () {
					parent.classList.remove("is-expanded");
				});
		} else {
			$(target)
				.stop()
				.slideDown(350, function () {
					parent.classList.add("is-expanded");
				});
		}
	}

	/**
	 * show / hide menu item.
	 *
	 * @param {Event} listArea The event object.
	 */
	function showHideMenuItem(e) {
		var parent = this.parentNode.parentNode.parentNode;
		var isHidden = parent.dataset.hidden === "1" ? true : false;

		if (isHidden) {
			this.classList.add("dashicons-visibility");
			this.classList.remove("dashicons-hidden");
			parent.dataset.hidden = 0;
		} else {
			parent.dataset.hidden = 1;
			this.classList.remove("dashicons-visibility");
			this.classList.add("dashicons-hidden");
		}
	}

	/**
	 * Setup item changes.
	 * @param {HTMLElement} listArea The list area element.
	 */
	function setupItemChanges(listArea) {
		var menuItems = listArea.querySelectorAll(".udb-admin-menu--menu-item");
		if (!menuItems.length) return;

		menuItems.forEach(function (menuItem) {
			setupItemChange(menuItem);
		});
	}

	/**
	 * Setup item change.
	 * @param {HTMLElement} menuItem The menu item element.
	 */
	function setupItemChange(menuItem) {
		var iconFields = menuItem.querySelectorAll(".udb-admin-menu--icon-field");
		iconFields = iconFields.length ? iconFields : [];

		iconFields.forEach(function (field) {
			field.addEventListener("change", function () {
				var iconWrapper = menuItem.querySelector(".udb-admin-menu--menu-icon");
				var iconOutput;

				if (this.dataset.name === "dashicon") {
					iconOutput = '<i class="dashicons ' + this.value + '"></i>';
				} else if (this.dataset.name === "icon_svg") {
					iconOutput = '<img alt="" src="' + this.value + '">';
				}

				iconWrapper.innerHTML = iconOutput;
			});
		});

		var titleFields = menuItem.querySelectorAll('[data-name="menu_title"]');
		titleFields = titleFields.length ? titleFields : [];

		titleFields.forEach(function (field) {
			field.addEventListener("change", function () {
				menuItem.querySelector(".udb-admin-menu--menu-name").innerHTML =
					this.value;
			});
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
})(jQuery);
