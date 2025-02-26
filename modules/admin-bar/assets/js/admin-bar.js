/**
 * Used global objects:
 * - jQuery
 * - ajaxurl
 * - udbAdminBar
 * - udbAdminBarBuilder
 */
(function ($) {
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

	/** @type {UdbAdminBarUser[]} */
	// let usersData = [];

	/**
	 * Init the script.
	 * Call the main functions here.
	 */
	function init() {
		// loadUsers();
		buildMenu(window.udbAdminBarBuilder?.builderItems);

		document
			.querySelector(".udb-menu-builder--edit-form")
			?.addEventListener("submit", submitForm);

		$(document).on("click", ".udb-menu-builder--tab-menu-item", switchTab);

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
	}

	/**
	 * Load users as select2 data.
	 *
	 * This function is not used currently.
	 * But leave it here because in the future, if requested, it would be used for
	 * "hide menu item for specific user(s)" functionality (inside a dropdown).
	 */
	// function loadUsers() {
	// 	$.ajax({
	// 		type: "get",
	// 		url: window.ajaxurl,
	// 		cache: false,
	// 		data: {
	// 			action: "udb_admin_bar_get_users",
	// 			nonce: window.udbAdminBar?.nonces.getUsers,
	// 		},
	// 	})
	// 		.done(function (r) {
	// 			if (!r.success) return;

	// 			usersData = r.data;

	// 			buildMenu(window.udbAdminBarBuilder?.builderItems);
	// 		})
	// 		.fail(function () {
	// 			console.log("Failed to load users");
	// 		})
	// 		.always(function () {
	// 			//
	// 		});
	// }

	/**
	 * Switch tabs.
	 *
	 * @param {JQuery.ClickEvent} e
	 * @this {HTMLElement}
	 */
	function switchTab(e) {
		if (e.target.classList.contains("delete-icon")) return;
		const tabArea = this.parentElement?.parentElement;
		const tabId = this.dataset.udbTabContent;

		let tabHasIdByDefault = false;

		if (tabArea) {
			if (tabArea.id) {
				tabHasIdByDefault = true;
			} else {
				tabArea.id =
					"udb-menu-builder--tab" + Math.random().toString(36).substring(7);
			}
		}

		const menus = document.querySelectorAll(
			"#" +
				tabArea?.id +
				" > .udb-menu-builder--tab-menu > .udb-menu-builder--tab-menu-item"
		);

		const contents = document.querySelectorAll(
			"#" +
				tabArea?.id +
				" > .udb-menu-builder--tab-content > .udb-menu-builder--tab-content-item"
		);

		if (!tabHasIdByDefault) tabArea?.removeAttribute("id");

		menus.forEach(function (menu) {
			if (!(menu instanceof HTMLElement)) return;

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
	}

	/**
	 * Build menu list.
	 *
	 * @param {Record<string, UdbAdminBarMenuItem>|undefined} menuList List of menu object.
	 */
	function buildMenu(menuList) {
		if (!menuList) return;

		const editArea = document.querySelector("#udb-menu-builder--workspace");
		if (!editArea) return;

		const listArea = editArea.querySelector(".udb-menu-builder--menu-list");
		let builtMenu = "";

		for (const menu in menuList) {
			if (menuList.hasOwnProperty(menu)) {
				builtMenu += replaceMenuPlaceholders(menuList[menu]);
			}
		}

		if (listArea instanceof HTMLElement) {
			listArea.innerHTML = builtMenu;
			setupMenuItems(listArea);
		}

		const submenuList = listArea?.querySelectorAll(
			".udb-menu-builder--submenu-list"
		);

		if (submenuList?.length) {
			submenuList.forEach(function (submenu) {
				if (!(submenu instanceof HTMLElement)) return;
				setupMenuItems(submenu, true);
			});
		}

		// setupSelect2Fields(listArea);
	}

	/**
	 * Setup select2 fields.
	 *
	 * This function is not used currently.
	 * But leave it here because in the future, if requested, it would be used for
	 * "hide menu item for specific role(s) / user(s)" functionality (inside dropdowns).
	 *
	 * @param {HTMLElement} area The setup area.
	 */
	// function setupSelect2Fields(area) {
	// 	const select2Fields = area.querySelectorAll(
	// 		".udb-menu-builder--select2-field"
	// 	);

	// 	select2Fields.forEach(function (selectbox) {
	// 		if (!(selectbox instanceof HTMLSelectElement)) return;

	// 		if (
	// 			selectbox.dataset.name !== "disallowed_roles" &&
	// 			selectbox.dataset.name !== "disallowed_users"
	// 		)
	// 			return;

	// 		/** @type {UdbSelect2Option[]} */
	// 		const select2Data = [];

	// 		/** @type string[] */
	// 		let disallowedRoles = [];

	// 		/** @type number[] */
	// 		let disallowedUsers = [];

	// 		if ("disallowed_roles" === selectbox.dataset.name) {
	// 			disallowedRoles = selectbox.dataset.disallowedRoles?.split(", ") ?? [];

	// 			window.udbAdminBar?.roles.forEach(function (role) {
	// 				if (disallowedRoles.indexOf(String(role.id)) > -1) {
	// 					role.selected = true;
	// 				}

	// 				select2Data.push(role);
	// 			});
	// 		} else if ("disallowed_users" === selectbox.dataset.name) {
	// 			disallowedUsers = (
	// 				selectbox.dataset?.disallowedUsers?.split(", ") ?? []
	// 			).map(function (user) {
	// 				return parseInt(user, 10);
	// 			});

	// 			usersData.forEach(function (userData) {
	// 				if (disallowedUsers.indexOf(Number(userData.id)) > -1) {
	// 					userData.selected = true;
	// 				}

	// 				select2Data.push(userData);
	// 			});
	// 		}

	// 		$(selectbox).select2({
	// 			data: select2Data,
	// 		});
	// 	});
	// }

	/**
	 * Replace menu placeholders.
	 *
	 * @param {UdbAdminBarMenuItem} menu The menu item.
	 */
	function replaceMenuPlaceholders(menu) {
		let submenuTemplate;
		let icon;

		let template = window.udbAdminBar?.templates.menuList ?? "";

		template = template.replace(/{menu_title}/g, menu.title);
		template = template.replace(
			/{encoded_default_menu_title}/g,
			menu.title_default_encoded ?? ""
		);

		if (menu.group) {
			template = template.replace(
				/{empty_menu_settings_text}/g,
				"No settings available."
			);
			template = template.replace(/{menu_title_field_is_hidden}/g, "is-hidden");
			template = template.replace(/{menu_href_field_is_hidden}/g, "is-hidden");
		} else {
			if ("wp-logo" === menu.id_default) {
				template = template.replace(
					/{menu_title_field_is_hidden}/g,
					"is-hidden"
				);
			} else {
				template = template.replace(/{menu_title_field_is_hidden}/g, "");
			}

			template = template.replace(/{empty_menu_settings_text}/g, "");

			if ("customize" === menu.id_default || "edit" === menu.id_default) {
				template = template.replace(
					/{menu_href_field_is_hidden}/g,
					"is-hidden"
				);
			} else {
				template = template.replace(/{menu_href_field_is_hidden}/g, "");
			}
		}

		let parsedTitle = "";

		if (
			"menu-toggle" === menu.id_default ||
			"wp-logo" === menu.id_default ||
			"updates" === menu.id_default ||
			"edit" === menu.id_default ||
			"appearance" === menu.id_default ||
			"comments" === menu.id_default ||
			"search" === menu.id_default ||
			!menu.title_default
		) {
			template = template.replace(/{menu_title_is_disabled}/g, "disabled");

			if ("wp-logo" === menu.id_default) {
				parsedTitle = "WP Logo";
			} else if ("comments" === menu.id_default) {
				parsedTitle = "Comments";
			} else if ("search" === menu.id_default) {
				parsedTitle = "Search Form";
			} else {
				parsedTitle = menu.id ? menu.id : menu.id_default;
			}
		} else {
			template = template.replace(/{menu_title_is_disabled}/g, "");

			if ("updates" === menu.id_default) {
				parsedTitle = menu.meta.menu_title
					? menu.meta.menu_title
					: menu.id_default;
			} else {
				parsedTitle = menu.title_clean
					? menu.title_clean
					: (menu.title_default_clean ?? "");
			}
		}

		template = template.replace(/{parsed_menu_title}/g, parsedTitle);

		template = template.replace(/{menu_href}/g, menu.href);
		template = template.replace(/{default_menu_href}/g, menu.href_default);

		template = template.replace(
			/{default_menu_group}/g,
			menu.group_default ? menu.group_default : "false"
		);

		if (
			!menu.href_default ||
			"my-sites" === menu.id_default ||
			"site-name" === menu.id_default ||
			"site-name-frontend" === menu.id_default ||
			"new-content" === menu.id_default ||
			"comments" === menu.id_default ||
			"updates" === menu.id_default
		) {
			template = template.replace(/{menu_href_is_disabled}/g, "disabled");
		} else {
			template = template.replace(/{menu_href_is_disabled}/g, "");
		}

		template = template.replace(/{menu_id}/g, menu.id);
		template = template.replace(/{default_menu_id}/g, menu.id_default);

		template = template.replace(/{default_menu_parent}/g, menu.parent_default);

		template = template.replace(
			/{menu_icon_is_disabled}/g,
			menu.was_added ? "" : "disabled"
		);

		template = template.replace(/{menu_is_hidden}/g, menu.is_hidden.toString());
		template = template.replace(
			/{frontend_only_indicator}/g,
			menu.frontend_only
				? '<span class="udb-menu-builder--tag udb-menu-builder--frontend-only-tag">Frontend</span>'
				: ""
		);
		template = template.replace(
			/{group_indicator}/g,
			menu.group
				? '<span class="udb-menu-builder--tag udb-menu-builder--group-tag">Group</span>'
				: ""
		);
		template = template.replace(/{trash_icon}/g, "");
		template = template.replace(
			/{hidden_icon}/g,
			menu.is_hidden ? "hidden" : "visibility"
		);

		/**
		 * These codes are not being used currently.
		 * But leave it here because in the future, if requested, it would be used for
		 * "hide menu item for specific role(s) / user(s)" functionality (inside dropdowns).
		 */
		// var disallowedRoles = menu.disallowed_roles.join(', ');
		// var disallowedUsers = menu.disallowed_users.join(', ');

		// template = template.replace(/{disallowed_roles}/g, disallowedRoles);
		// template = template.replace(/{disallowed_users}/g, disallowedUsers);

		if (menu.was_added) {
			template = template.replace(/{menu_icon_field_is_hidden}/g, "");
			template = template.replace(/{menu_icon}/g, menu.icon ?? "");

			if (menu.icon) {
				icon = '<i class="dashicons ' + menu.icon + '"></i>';
				template = template.replace(/{render_menu_icon}/g, icon);
			} else {
				template = template.replace(/{render_menu_icon}/g, "");
			}
		} else {
			template = template.replace(/{menu_icon_field_is_hidden}/g, "is-hidden");
			template = template.replace(/{menu_icon}/g, "");

			if ("wp-logo" === menu.id_default) {
				template = template.replace(
					/{render_menu_icon}/g,
					'<i class="dashicons dashicons-wordpress"></i>'
				);
			} else if ("my-sites" === menu.id_default) {
				template = template.replace(
					/{render_menu_icon}/g,
					'<i class="dashicons dashicons-admin-multisite"></i>'
				);
			} else if ("site-name" === menu.id_default) {
				template = template.replace(
					/{render_menu_icon}/g,
					'<i class="dashicons dashicons-admin-home"></i>'
				);
			} else if ("site-name-frontend" === menu.id_default) {
				template = template.replace(
					/{render_menu_icon}/g,
					'<i class="dashicons dashicons-dashboard"></i>'
				);
			} else if ("customize" === menu.id_default) {
				template = template.replace(
					/{render_menu_icon}/g,
					'<i class="dashicons dashicons-admin-customizer"></i>'
				);
			} else if ("updates" === menu.id_default) {
				template = template.replace(
					/{render_menu_icon}/g,
					'<i class="dashicons dashicons-update"></i>'
				);
			} else if ("comments" === menu.id_default) {
				template = template.replace(
					/{render_menu_icon}/g,
					'<i class="dashicons dashicons-admin-comments"></i>'
				);
			} else if ("new-content" === menu.id_default) {
				template = template.replace(
					/{render_menu_icon}/g,
					'<i class="dashicons dashicons-plus"></i>'
				);
			} else if ("edit" === menu.id_default) {
				template = template.replace(
					/{render_menu_icon}/g,
					'<i class="dashicons dashicons-edit"></i>'
				);
			} else {
				template = template.replace(/{render_menu_icon}/g, "");
			}
		}

		if (menu.submenu && Object.keys(menu.submenu).length) {
			submenuTemplate = buildSubmenu({
				menu: menu,
				depth: 1,
			});

			template = template.replace(/{submenu_template}/g, submenuTemplate);
		} else {
			template = template.replace(/{submenu_template}/g, "");
		}

		return template;
	}

	/**
	 * Build submenu list.
	 *
	 * @param {{menu: UdbAdminBarMenuItem, depth: number}} param The submenu parameter containing some arguments.
	 *
	 * @return {string} template The submenu template.
	 */
	function buildSubmenu(param) {
		let template = "";

		for (const submenuId in param.menu.submenu) {
			if (param.menu.submenu.hasOwnProperty(submenuId)) {
				template += replaceSubmenuPlaceholders({
					menu: param.menu,
					// Current submenu item.
					submenu: param.menu.submenu[submenuId],
					depth: param.depth,
				});
			}
		}

		return template;
	}

	/**
	 * Replace submenu placeholders.
	 *
	 * @param {{menu: UdbAdminBarMenuItem, submenu: UdbAdminBarMenuItem, depth: number}} param The parameter containing some arguments.
	 */
	function replaceSubmenuPlaceholders(param) {
		const menu = param.menu;
		const submenu = param.submenu;

		let template = window.udbAdminBar?.templates.submenuList ?? "";

		template = template.replace(/{default_menu_id}/g, menu.id_default);

		template = template.replace(/{submenu_id}/g, submenu.id);
		template = template.replace(/{default_submenu_id}/g, submenu.id_default);

		template = template.replace(
			/{default_submenu_parent}/g,
			submenu.parent_default
		);

		template = template.replace(/{submenu_level}/g, param.depth.toString());
		template = template.replace(
			/{submenu_next_level}/g,
			(param.depth + 1).toString()
		);
		template = template.replace(/{submenu_title}/g, submenu.title);
		template = template.replace(
			/{encoded_default_submenu_title}/g,
			submenu.title_default_encoded ?? ""
		);

		if (submenu.group || submenu.id_default === "search") {
			template = template.replace(
				/{empty_submenu_settings_text}/g,
				"No settings available."
			);
			template = template.replace(
				/{submenu_title_field_is_hidden}/g,
				"is-hidden"
			);
			template = template.replace(
				/{submenu_href_field_is_hidden}/g,
				"is-hidden"
			);
		} else {
			template = template.replace(/{empty_submenu_settings_text}/g, "");
			template = template.replace(/{submenu_title_field_is_hidden}/g, "");
			template = template.replace(/{submenu_href_field_is_hidden}/g, "");
		}

		var parsedTitle;

		if (
			"wp-logo" === submenu.id_default ||
			"appearance" === submenu.id_default ||
			"comments" === submenu.id_default ||
			"search" === submenu.id_default ||
			"user-info" === submenu.id_default ||
			!submenu.title_default
		) {
			template = template.replace(/{submenu_title_is_disabled}/g, "disabled");
			parsedTitle = submenu.id ? submenu.id : submenu.id_default;
		} else {
			if ("my-account" === submenu.id_default) {
				template = template.replace(/{submenu_title_is_disabled}/g, "disabled");
			} else {
				template = template.replace(/{submenu_title_is_disabled}/g, "");
			}

			if ("updates" === menu.id_default) {
				parsedTitle = menu.meta.menu_title
					? menu.meta.menu_title
					: menu.id_default;
				parsedTitle = submenu.meta.menu_title
					? submenu.meta.menu_title
					: submenu.id_default;
			} else {
				parsedTitle = submenu.title
					? submenu.title_clean
					: submenu.title_default_clean;
			}
		}

		template = template.replace(/{parsed_submenu_title}/g, parsedTitle ?? "");

		if ("logout" === submenu.id_default) {
			template = template.replace(/{submenu_href_is_disabled}/g, "disabled");
		} else {
			if (!submenu.was_added) {
				if (
					"my-sites-super-admin" === submenu.parent_default ||
					"my-sites-list" === submenu.parent_default ||
					"network-admin" === submenu.parent_default ||
					"blog-1" === submenu.parent_default ||
					"site-name" === submenu.parent_default ||
					"site-name-frontend" === submenu.parent_default ||
					"appearance" === submenu.parent_default ||
					"new-content" === submenu.parent_default
				) {
					template = template.replace(
						/{submenu_href_is_disabled}/g,
						"disabled"
					);
				} else {
					template = template.replace(/{submenu_href_is_disabled}/g, "");
				}
			} else {
				template = template.replace(/{submenu_href_is_disabled}/g, "");
			}
		}

		template = template.replace(/{submenu_href}/g, submenu.href);
		template = template.replace(
			/{default_submenu_href}/g,
			submenu.href_default
		);

		template = template.replace(
			/{default_submenu_group}/g,
			submenu.group_default ? submenu.group_default : "false"
		);

		template = template.replace(
			/{submenu_tab_is_hidden}/g,
			3 === param.depth ? "is-hidden" : ""
		);
		template = template.replace(
			/{submenu_is_hidden}/g,
			submenu.is_hidden.toString()
		);
		template = template.replace(
			/{frontend_only_indicator}/g,
			submenu.frontend_only
				? '<span class="udb-menu-builder--tag udb-menu-builder--frontend-only-tag">Frontend</span>'
				: ""
		);
		template = template.replace(
			/{group_indicator}/g,
			submenu.group
				? '<span class="udb-menu-builder--tag udb-menu-builder--group-tag">Group</span>'
				: ""
		);
		template = template.replace(/{trash_icon}/g, "");
		template = template.replace(
			/{hidden_icon}/g,
			submenu.is_hidden ? "hidden" : "visibility"
		);

		template = template.replace(
			/{submenu_was_added}/g,
			String(submenu.was_added)
		);

		/**
		 * These codes are not being used currently.
		 * But leave it here because in the future, if requested, it would be used for
		 * "hide menu item for specific role(s) / user(s)" functionality (inside dropdowns).
		 */
		// var disallowedRoles = submenu.disallowed_roles.join(', ');
		// var disallowedUsers = submenu.disallowed_users.join(', ');

		// template = template.replace(/{disallowed_roles}/g, disallowedRoles);
		// template = template.replace(/{disallowed_users}/g, disallowedUsers);

		if (submenu.submenu && Object.keys(submenu.submenu).length) {
			const submenuTemplate = buildSubmenu({
				menu: submenu,
				depth: param.depth + 1,
			});

			template = template.replace(/{submenu_template}/g, submenuTemplate);
		} else {
			template = template.replace(/{submenu_template}/g, "");
		}

		return template;
	}

	/**
	 * Setup menu items.
	 *
	 * @param {HTMLElement} listArea The list area element.
	 * @param {boolean} [isSubmenu] Whether the list area is a submenu list.
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
	 *
	 * @param {HTMLElement} listArea The list area element.
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
	 *
	 * @param {JQuery.ClickEvent} e The event object.
	 * @this {HTMLElement}
	 */
	function expandCollapseMenuItem(e) {
		const parent = this.classList.contains("expand-menu")
			? this.parentElement?.parentElement?.parentElement
			: this.parentElement?.parentElement;

		if (!parent) return;
		const target = parent?.querySelector(".udb-menu-builder--expanded-panel");
		if (!target) return;

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
	 * @param {JQuery.ClickEvent} e The event object.
	 * @this {HTMLElement}
	 */
	function showHideMenuItem(e) {
		const parent = this.parentElement?.parentElement?.parentElement;
		const isHidden = parent?.dataset.hidden === "1" ? true : false;

		if (isHidden) {
			this.classList.add("dashicons-visibility");
			this.classList.remove("dashicons-hidden");
			if (parent) parent.dataset.hidden = "0";
		} else {
			if (parent) parent.dataset.hidden = "1";
			this.classList.remove("dashicons-visibility");
			this.classList.add("dashicons-hidden");
		}
	}

	/**
	 * Setup item changes.
	 *
	 * @param {HTMLElement} listArea The list area element.
	 */
	function setupItemChanges(listArea) {
		const menuItems = listArea.querySelectorAll(".udb-menu-builder--menu-item");
		if (!menuItems.length) return;

		menuItems.forEach(function (menuItem) {
			if (!(menuItem instanceof HTMLElement)) return;
			setupItemChange(menuItem);
		});
	}

	/**
	 * Setup item change.
	 *
	 * @param {HTMLElement} menuItem The menu item element.
	 */
	function setupItemChange(menuItem) {
		const iconFields = menuItem.querySelectorAll(
			".udb-menu-builder--icon-field"
		);

		iconFields.forEach(function (field) {
			field.addEventListener(
				"change",
				/** @this {HTMLInputElement | HTMLTextAreaElement} */
				function () {
					const iconWrapper = menuItem.querySelector(
						".udb-menu-builder--menu-icon"
					);
					if (!iconWrapper) return;

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

					iconWrapper.innerHTML = iconOutput;
				}
			);
		});

		const titleFields = menuItem.querySelectorAll('[data-name="menu_title"]');

		titleFields.forEach(function (field) {
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
})(jQuery);
