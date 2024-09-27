/**
 * Used global objects:
 * - jQuery
 */
(function ($) {
	let finalSelectedRoles = [];
	const domElements = {
		saveRemoveByRoles: document.querySelector(".js-save-remove-admin-bar"),
	};
	/**
	 * Init the script.
	 * Call the main functions here.
	 */
	function init() {
		onShowElements();
		onSetupAdminBarRemovalRoles();
		setupEventsListeners();
	}

	/**
	 * Show elements.
	 */
	function onShowElements() {
		window.addEventListener("load", function () {
			$(".heatbox-admin-panel").css("display", "block");
		});
	}

	function onSetupAdminBarRemovalRoles() {
		$removeAdminBar = $(".admin-bar-visibility-box .remove-admin-bar");

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
		selectedRoles = [];

		if (roleObjects.length) {
			roleObjects.forEach(function (role) {
				selectedRoles.push(role.id);
			});
		}

		finalSelectedRoles = selectedRoles;
	}

	function setupEventsListeners() {
		$(document).on("click", ".js-save-remove-admin-bar", onSubmitRoles);
	}

	function onSubmitRoles(e) {
		e.preventDefault();

		domElements.saveRemoveByRoles.classList.add("is-loading");

		$.ajax({
			type: "POST",
			url: udbAdminBarVisibility.ajaxURL,
			data: {
				action: udbAdminBarVisibility.action,
				roles: finalSelectedRoles,
				nonce: udbAdminBarVisibility.nonce,
			},
		})
			.done(function (r) {
				if (!r.success) return;
				console.log(r);
			})
			.fail(function () {
				domElements.saveRemoveByRoles.classList.remove("is-loading");
				console.log("Failed to save remove by roles");
			})
			.always(function () {
				domElements.saveRemoveByRoles.classList.remove("is-loading");
			});
	}

	init();
})(jQuery);
