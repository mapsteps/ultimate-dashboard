/**
 * Used global objects:
 * - jQuery
 * - ajaxurl
 * - udbAdminBar
 * - udbAdminBarBuilder
 */
(function ($) {
	if (window.NodeList && !NodeList.prototype.forEach) {
		NodeList.prototype.forEach = Array.prototype.forEach;
	}

	if (!String.prototype.includes) {
		String.prototype.includes = function (search, start) {
			'use strict';

			if (search instanceof RegExp) {
				throw TypeError('first argument must not be a RegExp');
			}
			if (start === undefined) { start = 0; }
			return this.indexOf(search, start) !== -1;
		};
	}

	// var usersData;

	/**
	 * Init the script.
	 * Call the main functions here.
	 */
	function init() {
		// loadUsers();
		buildMenu(udbAdminBarBuilder.builderItems);

		document.querySelector('.udb-admin-bar--edit-form').addEventListener('submit', submitForm);

		$(document).on('click', '.udb-admin-bar--tab-menu-item', switchTab);
		$(document).on('click', '.udb-admin-bar--expand-menu', expandCollapseMenuItem);
		$(document).on('click', '.hide-menu', showHideMenuItem);
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
	// 		type: 'get',
	// 		url: ajaxurl,
	// 		cache: false,
	// 		data: {
	// 			action: 'udb_admin_bar_get_users',
	// 			nonce: udbAdminBar.nonces.getUsers
	// 		}
	// 	}).done(function (r) {
	// 		if (!r.success) return;

	// 		usersData = r.data;

	// 		buildMenu(udbAdminBarBuilder.builderItems);
	// 	}).fail(function () {
	// 		console.log('Failed to load users');
	// 	}).always(function () {
	// 		//
	// 	});
	// }

	/**
	 * Switch tabs.
	 */
	function switchTab(e) {
		if (e.target.classList.contains('delete-icon')) return;
		var tabArea = this.parentNode.parentNode;
		var tabId = this.dataset.udbTabContent;

		var tabHasIdByDefault = false;

		if (tabArea.id) {
			tabHasIdByDefault = true;
		} else {
			tabArea.id = 'udb-admin-bar--tab' + Math.random().toString(36).substring(7);
		}

		var menus = document.querySelectorAll('#' + tabArea.id + ' > .udb-admin-bar--tab-menu > .udb-admin-bar--tab-menu-item');
		var contents = document.querySelectorAll('#' + tabArea.id + ' > .udb-admin-bar--tab-content > .udb-admin-bar--tab-content-item');

		if (!tabHasIdByDefault) tabArea.removeAttribute('id');

		menus.forEach(function (menu) {
			if (menu.dataset.udbTabContent !== tabId) {
				menu.classList.remove('is-active');
			} else {
				menu.classList.add('is-active');
			}
		});

		contents.forEach(function (content) {
			if (content.id !== tabId) {
				content.classList.remove('is-active');
			} else {
				content.classList.add('is-active');
			}
		});
	}

	/**
	 * Build menu list.
	 *
	 * @param {array} menuList List of menu object.
	 */
	function buildMenu(menuList) {
		var editArea = document.querySelector('#udb-admin-bar--workspace');
		if (!editArea) return;
		var listArea = editArea.querySelector('.udb-admin-bar--menu-list');
		var builtMenu = '';

		for (var menu in menuList) {
			if (menuList.hasOwnProperty(menu)) {
				builtMenu += replaceMenuPlaceholders(menuList[menu]);
			}
		}

		listArea.innerHTML = builtMenu;

		setupMenuItems(listArea);

		var submenuList = listArea.querySelectorAll('.udb-admin-bar--submenu-list');

		if (submenuList.length) {
			submenuList.forEach(function (submenu) {
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
	// 	var select2Fields = area.querySelectorAll('.udb-admin-bar--select2-field');

	// 	select2Fields.forEach(function (selectbox) {
	// 		if (selectbox.dataset.name !== 'disallowed_roles' && selectbox.dataset.name !== 'disallowed_users') return;

	// 		var select2Data = [];
	// 		var disallowedRoles = [];
	// 		var disallowedUsers = [];

	// 		if ('disallowed_roles' === selectbox.dataset.name) {
	// 			disallowedRoles = selectbox.dataset.disallowedRoles.split(', ');

	// 			udbAdminBar.roles.forEach(function (role) {
	// 				if (disallowedRoles.indexOf(role.id) > -1) {
	// 					role.selected = true;
	// 				}

	// 				select2Data.push(role);
	// 			});
	// 		} else if ('disallowed_users' === selectbox.dataset.name) {
	// 			disallowedUsers = selectbox.dataset.disallowedUsers.split(', ');
	// 			disallowedUsers = disallowedUsers.map(function (user) {
	// 				return parseInt(user, 10);
	// 			});

	// 			usersData.forEach(function (userData) {
	// 				if (disallowedUsers.indexOf(userData.id) > -1) {
	// 					userData.selected = true;
	// 				}

	// 				select2Data.push(userData);
	// 			});
	// 		}

	// 		$(selectbox).select2({
	// 			data: select2Data
	// 		});
	// 	});
	// }

	/**
	 * Replace menu placeholders.
	 *
	 * @param {Object} menu The menu item.
	 */
	function replaceMenuPlaceholders(menu) {
		var template;
		var submenuTemplate;
		var icon;

		template = udbAdminBar.templates.menuList;
		template = template.replace(/{menu_title}/g, menu.title);
		template = template.replace(/{encoded_default_menu_title}/g, menu.title_default_encoded);

		if (menu.group) {
			template = template.replace(/{empty_menu_settings_text}/g, 'No settings available.');
			template = template.replace(/{menu_title_field_is_hidden}/g, 'is-hidden');
			template = template.replace(/{menu_href_field_is_hidden}/g, 'is-hidden');
		} else {
			if ('wp-logo' === menu.id_default) {
				template = template.replace(/{menu_title_field_is_hidden}/g, 'is-hidden');
			} else {
				template = template.replace(/{menu_title_field_is_hidden}/g, '');
			}

			template = template.replace(/{empty_menu_settings_text}/g, '');

			if ('customize' === menu.id_default || 'edit' === menu.id_default) {
				template = template.replace(/{menu_href_field_is_hidden}/g, 'is-hidden');
			} else {
				template = template.replace(/{menu_href_field_is_hidden}/g, '');
			}
		}

		var parsedTitle;

		if ('menu-toggle' === menu.id_default || 'wp-logo' === menu.id_default || 'updates' === menu.id_default || 'edit' === menu.id_default || 'appearance' === menu.id_default || 'comments' === menu.id_default || 'search' === menu.id_default || false === menu.title_default) {
			template = template.replace(/{menu_title_is_disabled}/g, 'disabled');

			if ('wp-logo' === menu.id_default) {
				parsedTitle = 'WP Logo';
			} else if ('comments' === menu.id_default) {
				parsedTitle = 'Comments';
			} else if ('search' === menu.id_default) {
				parsedTitle = 'Search Form';
			} else {
				parsedTitle = menu.id ? menu.id : menu.id_default;
			}
		} else {
			template = template.replace(/{menu_title_is_disabled}/g, '');

			if ('updates' === menu.id_default) {
				parsedTitle = menu.meta.title ? menu.meta.title : menu.id_default;
			} else {
				parsedTitle = menu.title ? menu.title_clean : menu.title_default_clean;
			}
		}

		template = template.replace(/{parsed_menu_title}/g, parsedTitle);

		template = template.replace(/{menu_href}/g, menu.href);
		template = template.replace(/{default_menu_href}/g, menu.href_default);

		template = template.replace(/{default_menu_group}/g, (menu.group_default ? menu.group_default : "false"));

		if (false === menu.href_default || 'my-sites' === menu.id_default || 'site-name' === menu.id_default || 'site-name-frontend' === menu.id_default || 'new-content' === menu.id_default || 'comments' === menu.id_default || 'updates' === menu.id_default) {
			template = template.replace(/{menu_href_is_disabled}/g, 'disabled');
		} else {
			template = template.replace(/{menu_href_is_disabled}/g, '');
		}

		template = template.replace(/{menu_id}/g, menu.id);
		template = template.replace(/{default_menu_id}/g, menu.id_default);

		template = template.replace(/{default_menu_parent}/g, menu.parent_default);

		template = template.replace(/{menu_icon_is_disabled}/g, (menu.was_added ? '' : 'disabled'));

		template = template.replace(/{menu_is_hidden}/g, menu.is_hidden.toString());
		template = template.replace(/{frontend_only_indicator}/g, (menu.frontend_only ? '<span class="udb-admin-bar--tag udb-admin-bar--frontend-only-tag">Frontend</span>' : ''));
		template = template.replace(/{group_indicator}/g, (menu.group ? '<span class="udb-admin-bar--tag udb-admin-bar--group-tag">Group</span>' : ''));
		template = template.replace(/{trash_icon}/g, '');
		template = template.replace(/{hidden_icon}/g, (menu.is_hidden ? 'hidden' : 'visibility'));

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
			template = template.replace(/{menu_icon_field_is_hidden}/g, '');
			template = template.replace(/{menu_icon}/g, menu.icon);

			if (menu.icon) {
				icon = '<i class="dashicons ' + menu.icon + '"></i>';
				template = template.replace(/{render_menu_icon}/g, icon);
			} else {
				template = template.replace(/{render_menu_icon}/g, '');
			}
		} else {
			template = template.replace(/{menu_icon_field_is_hidden}/g, 'is-hidden');
			template = template.replace(/{menu_icon}/g, '');

			if ('wp-logo' === menu.id_default) {
				template = template.replace(/{render_menu_icon}/g, '<i class="dashicons dashicons-wordpress"></i>');
			} else if ('my-sites' === menu.id_default) {
				template = template.replace(/{render_menu_icon}/g, '<i class="dashicons dashicons-admin-multisite"></i>');
			} else if ('site-name' === menu.id_default) {
				template = template.replace(/{render_menu_icon}/g, '<i class="dashicons dashicons-admin-home"></i>');
			} else if ('site-name-frontend' === menu.id_default) {
				template = template.replace(/{render_menu_icon}/g, '<i class="dashicons dashicons-dashboard"></i>');
			} else if ('customize' === menu.id_default) {
				template = template.replace(/{render_menu_icon}/g, '<i class="dashicons dashicons-admin-customizer"></i>');
			} else if ('updates' === menu.id_default) {
				template = template.replace(/{render_menu_icon}/g, '<i class="dashicons dashicons-update"></i>');
			} else if ('comments' === menu.id_default) {
				template = template.replace(/{render_menu_icon}/g, '<i class="dashicons dashicons-admin-comments"></i>');
			} else if ('new-content' === menu.id_default) {
				template = template.replace(/{render_menu_icon}/g, '<i class="dashicons dashicons-plus"></i>');
			} else if ('edit' === menu.id_default) {
				template = template.replace(/{render_menu_icon}/g, '<i class="dashicons dashicons-edit"></i>');
			} else {
				template = template.replace(/{render_menu_icon}/g, '');
			}
		}

		if (menu.submenu && Object.keys(menu.submenu).length) {
			submenuTemplate = buildSubmenu({
				menu: menu,
				depth: 1
			});

			template = template.replace(/{submenu_template}/g, submenuTemplate);
		} else {
			template = template.replace(/{submenu_template}/g, '');
		}

		return template;
	}

	/**
	 * Build submenu list.
	 *
	 * @param {Object} param The submenu parameter containing some arguments.
	 *
	 * @param {array} param.menu The menu item which contains the submenu list.
	 * @param {int} param.depth The submenu depth level.
	 * 
	 * @return {string} template The submenu template.
	 */
	function buildSubmenu(param) {
		var menu = param.menu;
		var depth = param.depth;

		var template = '';

		for (var submenuId in menu.submenu) {
			if (menu.submenu.hasOwnProperty(submenuId)) {
				template += replaceSubmenuPlaceholders({
					menu: menu,
					// Current submenu item.
					submenu: menu.submenu[submenuId],
					depth: depth
				});
			}
		}

		return template;
	}

	/**
	 * Replace submenu placeholders.
	 *
	 * @param {Object} param The parameter containing some arguments.
	 *
	 * @param {Object} param.menu The menu item which contains the submenu list.
	 * @param {Object} param.submenu The current submenu item.
	 * @param {int} param.depth The submenu depth level.
	 */
	function replaceSubmenuPlaceholders(param) {
		var menu = param.menu;
		var submenu = param.submenu;
		var depth = param.depth;

		var template = udbAdminBar.templates.submenuList;

		template = template.replace(/{default_menu_id}/g, menu.id_default);

		template = template.replace(/{submenu_id}/g, submenu.id);
		template = template.replace(/{default_submenu_id}/g, submenu.id_default);

		template = template.replace(/{default_submenu_parent}/g, submenu.parent_default);

		template = template.replace(/{submenu_level}/g, depth.toString());
		template = template.replace(/{submenu_next_level}/g, (depth + 1).toString());
		template = template.replace(/{submenu_title}/g, submenu.title);
		template = template.replace(/{encoded_default_submenu_title}/g, submenu.title_default_encoded);

		if (submenu.group || submenu.id_default === 'search') {
			template = template.replace(/{empty_submenu_settings_text}/g, 'No settings available.');
			template = template.replace(/{submenu_title_field_is_hidden}/g, 'is-hidden');
			template = template.replace(/{submenu_href_field_is_hidden}/g, 'is-hidden');
		} else {
			template = template.replace(/{empty_submenu_settings_text}/g, '');
			template = template.replace(/{submenu_title_field_is_hidden}/g, '');
			template = template.replace(/{submenu_href_field_is_hidden}/g, '');
		}

		var parsedTitle;

		if ('wp-logo' === submenu.id_default || 'appearance' === submenu.id_default || 'comments' === submenu.id_default || 'search' === submenu.id_default || 'user-info' === submenu.id_default || false === submenu.title_default) {
			template = template.replace(/{submenu_title_is_disabled}/g, 'disabled');
			parsedTitle = submenu.id ? submenu.id : submenu.id_default;
		} else {
			if ('my-account' === submenu.id_default) {
				template = template.replace(/{submenu_title_is_disabled}/g, 'disabled');
			} else {
				template = template.replace(/{submenu_title_is_disabled}/g, '');
			}

			if ('updates' === menu.id_default) {
				parsedTitle = menu.meta.title ? menu.meta.title : menu.id_default;
				parsedTitle = submenu.meta.title ? submenu.meta.title : submenu.id_default;
			} else {
				parsedTitle = submenu.title ? submenu.title_clean : submenu.title_default_clean;
			}
		}

		template = template.replace(/{parsed_submenu_title}/g, parsedTitle);

		if ('logout' === submenu.id_default) {
			template = template.replace(/{submenu_href_is_disabled}/g, 'disabled');
		} else {
			if (!submenu.was_added) {
				if ('my-sites-super-admin' === submenu.parent_default || 'my-sites-list' === submenu.parent_default || 'network-admin' === submenu.parent_default || 'blog-1' === submenu.parent_default || 'site-name' === submenu.parent_default || 'site-name-frontend' === submenu.parent_default || 'appearance' === submenu.parent_default || 'new-content' === submenu.parent_default) {
					template = template.replace(/{submenu_href_is_disabled}/g, 'disabled');
				} else {
					template = template.replace(/{submenu_href_is_disabled}/g, '');
				}
			} else {
				template = template.replace(/{submenu_href_is_disabled}/g, '');
			}
		}

		template = template.replace(/{submenu_href}/g, submenu.href);
		template = template.replace(/{default_submenu_href}/g, submenu.href_default);

		template = template.replace(/{default_submenu_group}/g, (submenu.group_default ? submenu.group_default : "false"));

		template = template.replace(/{submenu_tab_is_hidden}/g, (3 === depth ? 'is-hidden' : ''));
		template = template.replace(/{submenu_is_hidden}/g, submenu.is_hidden.toString());
		template = template.replace(/{frontend_only_indicator}/g, (submenu.frontend_only ? '<span class="udb-admin-bar--tag udb-admin-bar--frontend-only-tag">Frontend</span>' : ''));
		template = template.replace(/{group_indicator}/g, (submenu.group ? '<span class="udb-admin-bar--tag udb-admin-bar--group-tag">Group</span>' : ''));
		template = template.replace(/{trash_icon}/g, '');
		template = template.replace(/{hidden_icon}/g, (submenu.is_hidden ? 'hidden' : 'visibility'));
		template = template.replace(/{submenu_was_added}/g, submenu.was_added);

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
			submenuTemplate = buildSubmenu({
				menu: submenu,
				depth: depth + 1
			});
			template = template.replace(/{submenu_template}/g, submenuTemplate);
		} else {
			template = template.replace(/{submenu_template}/g, '');
		}

		return template;
	}

	/**
	 * Setup menu items.
	 */
	function setupMenuItems(listArea, isSubmenu) {
		setupSortable(listArea);

		if (!isSubmenu) {
			setupItemChanges(listArea);
			$(listArea).find('.dashicons-picker').dashiconsPicker();
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
			}
		});
	}

	/**
	 * Expand / collapse menu item.
	 * @param {Event} e The event object.
	 */
	function expandCollapseMenuItem(e) {
		var parent = this.parentNode.parentNode;
		var target = parent.querySelector('.udb-admin-bar--expanded-panel');

		if (parent.classList.contains('is-expanded')) {
			$(target).stop().slideUp(350, function () {
				parent.classList.remove('is-expanded');
			});
		} else {
			$(target).stop().slideDown(350, function () {
				parent.classList.add('is-expanded');
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
		var isHidden = parent.dataset.hidden === '1' ? true : false;

		if (isHidden) {
			this.classList.add('dashicons-visibility');
			this.classList.remove('dashicons-hidden');
			parent.dataset.hidden = 0;
		} else {
			parent.dataset.hidden = 1;
			this.classList.remove('dashicons-visibility');
			this.classList.add('dashicons-hidden');
		}
	}

	/**
	 * Setup item changes.
	 * @param {HTMLElement} listArea The list area element.
	 */
	function setupItemChanges(listArea) {
		var menuItems = listArea.querySelectorAll('.udb-admin-bar--menu-item');
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
		var iconFields = menuItem.querySelectorAll('.udb-admin-bar--icon-field');
		iconFields = iconFields.length ? iconFields : [];

		iconFields.forEach(function (field) {
			field.addEventListener('change', function () {
				var iconWrapper = menuItem.querySelector('.udb-admin-bar--menu-icon');
				var iconOutput;

				if (this.dataset.name === 'dashicon') {
					iconOutput = '<i class="dashicons ' + this.value + '"></i>';
				} else if (this.dataset.name === 'icon_svg') {
					iconOutput = '<img alt="" src="' + this.value + '">';
				}

				iconWrapper.innerHTML = iconOutput;
			});
		});

		var titleFields = menuItem.querySelectorAll('[data-name="menu_title"]');
		titleFields = titleFields.length ? titleFields : [];

		titleFields.forEach(function (field) {
			field.addEventListener('change', function () {
				menuItem.querySelector('.udb-admin-bar--menu-name').innerHTML = this.value;
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