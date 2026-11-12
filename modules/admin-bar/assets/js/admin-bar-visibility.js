(function ($) {
	const saveRemoveByRoles = document.querySelector(".js-save-remove-admin-bar");
	const roleBoxes = document.querySelectorAll(".admin-bar-visibility-box .udb-remove-admin-bar-role");

	function init() {
		setupRoleBoxes();
		$(document).on("click", ".js-save-remove-admin-bar", onSubmitRoles);
	}

	/**
	 * "All" or individual roles, not both (like the Setup Wizard).
	 */
	function setupRoleBoxes() {
		roleBoxes.forEach(function (box) {
			box.addEventListener("change", function () {
				if (!box.checked) return;

				roleBoxes.forEach(function (other) {
					if (other !== box && ("all" === box.value || "all" === other.value)) {
						other.checked = false;
					}
				});
			});
		});
	}

	/**
	 * The checked roles.
	 *
	 * @returns {string[]}
	 */
	function selectedRoles() {
		return Array.from(roleBoxes)
			.filter((box) => box.checked)
			.map((box) => box.value);
	}

	function onSubmitRoles(e) {
		e.preventDefault();

		const $box = $(".admin-bar-visibility-box");

		saveRemoveByRoles?.classList.add("is-loading");
		$box.find(".heatbox-notice").removeClass("is-shown is-error").text("");

		$.ajax({
			type: "POST",
			url: window.ajaxurl,
			data: {
				action: window.udbAdminBarVisibility?.action,
				roles: selectedRoles(),
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
	 * Show an error next to the save button (heatbox notice).
	 *
	 * @param {JQuery} $box The visibility box.
	 * @param {string} message The error message from the server, if any.
	 */
	function showError($box, message) {
		const text =
			typeof message === "string" && message
				? message
				: window.udbAdminBarVisibility?.errorText ?? "";

		$box.find(".heatbox-notice").text(text).addClass("is-shown is-error");
	}

	init();
})(jQuery);
