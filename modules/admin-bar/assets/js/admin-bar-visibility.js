(function ($) {
	/** @type {string[]} */
	let finalSelectedRoles = [];

	const saveRemoveByRoles = document.querySelector(".js-save-remove-admin-bar");

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
		const $removeAdminBar = $(".admin-bar-visibility-box .remove-admin-bar");

		$removeAdminBar.select2();

		setAdminBarRemovalRoles($removeAdminBar.select2("data"));

		$removeAdminBar.on("select2:select", function (e) {
			const roleObjects = $removeAdminBar.select2("data");

			/** @type {string[]} */
			const newSelections = [];

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

	/**
	 * @param {Select2.OptionData[]} roleObjects
	 */
	function setAdminBarRemovalRoles(roleObjects) {
		/** @type {string[]} */
		const selectedRoles = [];

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

	/**
	 * @param {JQuery.ClickEvent} e
	 */
	function onSubmitRoles(e) {
		e.preventDefault();

		const $box = $(".admin-bar-visibility-box");

		saveRemoveByRoles?.classList.add("is-loading");
		$box.find(".udb-admin-bar-visibility-error").remove();

		$.ajax({
			type: "POST",
			url: window.ajaxurl,
			data: {
				action: window.udbAdminBarVisibility?.action,
				roles: finalSelectedRoles,
				nonce: window.udbAdminBarVisibility?.nonce,
			},
		})
			.done(function (r) {
				if (!r.success) {
					showError($box, r.data);
					return;
				}

				showDone($box);
			})
			.fail(function () {
				showError($box, "");
			})
			.always(function () {
				saveRemoveByRoles?.classList.remove("is-loading");
			});
	}

	/**
	 * Show the success indicator (🚀) in the box's h2, like the modules page.
	 *
	 * @param {JQuery} $box The visibility box.
	 */
	function showDone($box) {
		let $indicator = $box.find(".heatbox-status-indicator");

		if (!$indicator.length) {
			$indicator = $('<span class="heatbox-status-indicator"></span>');
			$box.find("h2").first().append($indicator);
		}

		$indicator.removeClass("is-done").text("🚀");

		// Force reflow to restart the CSS animation.
		void $indicator[0].offsetWidth;

		$indicator.addClass("is-done");

		setTimeout(function () {
			$indicator.removeClass("is-done").text("");
		}, 1600);
	}

	/**
	 * Show an error below the save button.
	 *
	 * @param {JQuery} $box The visibility box.
	 * @param {string} message The error message from the server, if any.
	 */
	function showError($box, message) {
		const text =
			typeof message === "string" && message
				? message
				: window.udbAdminBarVisibility?.errorText ?? "";

		$('<p class="udb-admin-bar-visibility-error" role="alert"></p>')
			.text(text)
			.appendTo($box.find(".heatbox-footer").first());
	}

	init();
})(jQuery);
