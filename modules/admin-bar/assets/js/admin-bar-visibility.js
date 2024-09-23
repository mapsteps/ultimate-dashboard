/**
 * Used global objects:
 * - jQuery
 */
(function ($) {
	/**
	 * Init the script.
	 * Call the main functions here.
	 */
	function init() {
		setupTabsNavigation();
		setupAdminBarRemovalRoles();
	}

	/**
	 * Setup the tabs navigation for settings page.
	 */
	function setupTabsNavigation() {
		$(".heatbox-tab-nav-item").on("click", function () {
			$(".heatbox-tab-nav-item").removeClass("active");
			$(this).addClass("active");

			var link = this.querySelector("a");
			var hashValue = link.href.substring(link.href.indexOf("#") + 1);

			setRefererValue(hashValue);

			$(".udb-menu-builder--edit-form .heatbox-admin-panel").css(
				"display",
				"none"
			);
			$(".udb-menu-builder--edit-form .udb-" + hashValue + "-panel").css(
				"display",
				"block"
			);
		});

		window.addEventListener("load", function () {
			var hashValue = window.location.hash.substr(1);

			if (!hashValue) {
				hashValue = "menu-builder-box";
			}

			setRefererValue(hashValue);

			$(".heatbox-tab-nav-item").removeClass("active");
			$(".heatbox-tab-nav-item." + hashValue + "-panel").addClass("active");

			$(".udb-menu-builder--edit-form .heatbox-admin-panel").css(
				"display",
				"none"
			);
			$(".udb-menu-builder--edit-form .udb-" + hashValue + "-panel").css(
				"display",
				"block"
			);
		});
	}

	/**
	 * Set referer value for the tabs navigation of settings page.
	 * This is being used to preserve the active tab after saving the settings page.
	 *
	 * @param {string} hashValue The hash value.
	 */
	function setRefererValue(hashValue) {
		var refererField = document.querySelector('[name="_wp_http_referer"]');
		if (!refererField) return;
		var url;

		if (refererField.value.includes("#")) {
			url = refererField.value.split("#");
			url = url[0];

			refererField.value = url + "#" + hashValue;
		} else {
			refererField.value = refererField.value + "#" + hashValue;
		}
	}

	function setupAdminBarRemovalRoles() {
		$removeAdminBar = $(".admin-bar-settings-box .remove-admin-bar");

		$removeAdminBar.select2();

		setAdminBarRemovalRoles($removeAdminBar.select2("data"));

		$removeAdminBar.on("select2:select", function (e) {
			var roleObjects = $removeAdminBar.select2("data");
			var newSelections = [];

			if (e.params.data.id === "all") {
				$removeAdminBar.val("all");
				$removeAdminBar.trigger("change");
			} else {
				if (roleObjects.length) {
					roleObjects.forEach(function (role) {
						if (role.id !== "all") {
							newSelections.push(role.id);
						}
					});

					$removeAdminBar.val(newSelections);
					$removeAdminBar.trigger("change");
				}
			}

			// Use the modified list.
			setAdminBarRemovalRoles($removeAdminBar.select2("data"));
		});

		$removeAdminBar.on("select2:unselect", function (e) {
			setAdminBarRemovalRoles($removeAdminBar.select2("data"));
		});
	}

	function setAdminBarRemovalRoles(roleObjects) {
		adminBarRemovalRoles = [];

		if (!roleObjects || !roleObjects.length) {
			return;
		}

		roleObjects.forEach(function (role) {
			adminBarRemovalRoles.push(role.id);
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
