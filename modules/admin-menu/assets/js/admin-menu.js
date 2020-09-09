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
			'use strict';

			if (search instanceof RegExp) {
				throw TypeError('first argument must not be a RegExp');
			}
			if (start === undefined) { start = 0; }
			return this.indexOf(search, start) !== -1;
		};
	}

	/**
	 * Init the script.
	 * Call the main functions here.
	 */
	function init() {
		var roleMenu = document.querySelector('.udb-admin-menu--role-tabs');
		setupTabs(roleMenu);

		udbAdminMenu.roles.forEach(function (role) {
			getMenu(role.key);
		});

		document.querySelector('.udb-admin-menu--edit-form').addEventListener('submit', submitForm);
	}

	/**
	 * Setup tabs.
	 * 
	 * @param HTMLElement tabArea The tab area.
	 */
	function setupTabs(tabArea) {
		var tabArea = document.querySelectorAll('.udb-admin-menu--tabs');
		if (!tabArea.length) return;

		tabArea.forEach(function (tab) {
			var tabHasIdByDefault = false;

			if (tab.id) {
				tabHasIdByDefault = true;
			} else {
				tab.id = 'udb-admin-menu--tab' + Math.random().toString(36).substring(7);
			}

			var menus = document.querySelectorAll('#' + tab.id + ' > .udb-admin-menu--tab-menu > .udb-admin-menu--tab-menu-item');

			if (!tabHasIdByDefault) tab.removeAttribute('id');

			menus.forEach(function (menu) {
				menu.addEventListener('click', function (e) {
					switchTab(tab, this.dataset.udbTabContent);
				});
			});
		});

		/**
		 * Switch tab.
		 *
		 * @param {HTMLElement} tabArea The tab area.
		 * @param {string} tabId The target tab id.
		 */
		function switchTab(tabArea, tabId) {
			var tabHasIdByDefault = false;

			if (tabArea.id) {
				tabHasIdByDefault = true;
			} else {
				tabArea.id = 'udb-admin-menu--tab' + Math.random().toString(36).substring(7);
			}

			var menus = document.querySelectorAll('#' + tabArea.id + ' > .udb-admin-menu--tab-menu > .udb-admin-menu--tab-menu-item');
			var contents = document.querySelectorAll('#' + tabArea.id + ' > .udb-admin-menu--tab-content > .udb-admin-menu--tab-content-item');

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
	}

	/**
	 * Get menu & submenu by role.
	 * @param {string} role The specified role.
	 */
	function getMenu(role) {
		$.ajax({
			url: ajaxurl,
			type: "post",
			dataType: 'json',
			data: {
				action: 'udb_admin_menu_get_menu',
				nonce: udbAdminMenu.nonces.getMenu,
				role: role
			}
		}).done(function (r) {
			if (!r || !r.success) return;
			buildMenu(role, r.data);
		}).always(function () {
			//
		});
	}

	/**
	 * Build menu list.
	 *
	 * @param {array} menuList The menu list returned from ajax response.
	 */
	function buildMenu(role, menuList) {
		var editArea = document.querySelector('#udb-admin-menu--' + role + '-edit-area');
		if (!editArea) return;
		var listArea = editArea.querySelector('.udb-admin-menu--menu-list');
		var builtMenu = '';

		menuList.forEach(function (menu) {
			var template;
			var submenuTemplate;
			var icon;
			
			if (menu.type === 'separator') {
				template = udbAdminMenu.templates.menuSeparator;
				template = template.replace(/{separator}/g, menu.url_default);


				template = template.replace(/{menu_is_hidden}/g, menu.is_hidden);
				template = template.replace(/{hidden_icon}/g, (menu.is_hidden == '1' ? 'hidden' : 'visibility'));
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
				template = template.replace(/{default_menu_dashicon}/g, menu.dashicon_default);

				template = template.replace(/{menu_icon_svg}/g, menu.icon_svg);
				template = template.replace(/{default_menu_icon_svg}/g, menu.icon_svg_default);

				template = template.replace(/{menu_is_hidden}/g, menu.is_hidden);
				template = template.replace(/{hidden_icon}/g, (menu.is_hidden == '1' ? 'hidden' : 'visibility'));
				template = template.replace(/{menu_was_added}/g, menu.was_added);

				var menuIconSuffix = menu.icon_type && menu[menu.icon_type] ? '' : '_default';

				if (menu['icon_type' + menuIconSuffix] === 'icon_svg') {
					icon = '<img alt="" src="' + menu['icon_svg' + menuIconSuffix] + '">';
					template = template.replace(/{icon_svg_tab_is_active}/g, 'is-active');
					template = template.replace(/{dashicon_tab_is_active}/g, '');
				} else {
					icon = '<i class="dashicons ' + menu['dashicon' + menuIconSuffix] + '"></i>';
					template = template.replace(/{icon_svg_tab_is_active}/g, '');
					template = template.replace(/{dashicon_tab_is_active}/g, 'is-active');
				}

				template = template.replace(/{menu_icon}/g, icon);

				if (menu.submenu) {
					submenuTemplate = buildSubmenu(role, menu);
					template = template.replace(/{submenu_template}/g, submenuTemplate);
				} else {
					template = template.replace(/{submenu_template}/g, '');
				}
			}

			template = template.replace(/{role}/g, role);
			builtMenu += template;
		});

		listArea.innerHTML = builtMenu;

		setupMenuItems(listArea);

		var submenuList = listArea.querySelectorAll('.udb-admin-menu--submenu-list');

		if (submenuList.length) {
			submenuList.forEach(function (submenu) {
				setupMenuItems(submenu, true);
			});
		}
	}

	/**
	 * Build submenu list.
	 *
	 * @param {string} role The specified role.
	 * @param {array} menu The menu which contains the submenu list.
	 * 
	 * @return {string} template The submenu template.
	 */
	function buildSubmenu(role, menu) {
		var templates = '';

		menu.submenu.forEach(function (submenu) {
			var template = udbAdminMenu.templates.submenuList;

			template = template.replace(/{role}/g, role);

			template = template.replace(/{default_menu_id}/g, menu.id_default);

			template = template.replace(/{submenu_title}/g, submenu.title);
			template = template.replace(/{default_submenu_title}/g, submenu.title_default);

			var parsedTitle = submenu.title ? submenu.title : submenu.title_default;
			template = template.replace(/{parsed_submenu_title}/g, parsedTitle);

			template = template.replace(/{submenu_url}/g, submenu.url);
			template = template.replace(/{default_submenu_url}/g, submenu.url_default);

			template = template.replace(/{submenu_is_hidden}/g, submenu.is_hidden);
			template = template.replace(/{hidden_icon}/g, (submenu.is_hidden == '1' ? 'hidden' : 'visibility'));
			template = template.replace(/{submenu_was_added}/g, submenu.was_added);

			templates += template;
		});

		return templates;
	}

	/**
	 * Setup menu items.
	 */
	function setupMenuItems(listArea, isSubmenu) {
		var tabArea;

		setupSortable(listArea);

		if (!isSubmenu) {
			tabArea = listArea.querySelector('.udb-admin-menu--menu-item-tabs');
			setupTabs(tabArea);
			setupItemChanges(listArea);
			$(listArea).find('.dashicons-picker').dashiconsPicker();

			/**
			 * These functions will register their functionality both on parent menu & their submenus.
			 */
			setupExpandCollapseMenu(listArea);
			setupShowHideMenu(listArea);
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
	 * Setup menu item's expand-collapse functionality.
	 *
	 * @param {HTMLElement} listArea The list area.
	 */
	function setupExpandCollapseMenu(listArea) {
		var triggers = listArea.querySelectorAll('.udb-admin-menu--expand-menu');
		if (!triggers.length) return;

		triggers.forEach(function (trigger) {
			trigger.addEventListener('click', function (e) {
				var parent = this.parentNode.parentNode;
				var target = parent.querySelector('.udb-admin-menu--expanded-panel');

				if (parent.classList.contains('is-expanded')) {
					$(target).stop().slideUp(350, function () {
						parent.classList.remove('is-expanded');
					});
				} else {
					$(target).stop().slideDown(350, function () {
						parent.classList.add('is-expanded');
					});
				}
			});
		});
	}

	/**
	 * Setup show/hide menu functionality.
	 *
	 * @param {HTMLElement} listArea The list area.
	 */
	function setupShowHideMenu(listArea) {
		var triggers = listArea.querySelectorAll('.hide-menu');
		if (!triggers.length) return;

		triggers.forEach(function (trigger) {
			trigger.addEventListener('click', function (e) {
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

			});
		});
	}

	function setupItemChanges(listArea) {
		var menuItems = listArea.querySelectorAll('.udb-admin-menu--menu-item');
		if (!menuItems.length) return;

		menuItems.forEach(function (menuItem) {
			var iconFields = menuItem.querySelectorAll('.udb-admin-menu--icon-field');
			iconFields = iconFields.length ? iconFields : [];

			iconFields.forEach(function (field) {
				field.addEventListener('change', function () {
					var iconWrapper = menuItem.querySelector('.udb-admin-menu--menu-icon');
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
					menuItem.querySelector('.udb-admin-menu--menu-name').innerHTML = this.value;
				});
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