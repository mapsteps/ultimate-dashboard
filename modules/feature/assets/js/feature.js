/**
 * Modules page: each toggle turns its module on or off right away.
 *
 * Every save sends the state of all toggles, and a new save cancels the one still running,
 * so quick clicks on several toggles can't overwrite each other. If a save fails,
 * all toggles go back to the last saved state.
 */
(function () {
	"use strict";

	const config = window.udbModules;
	const toggles = Array.from(document.querySelectorAll('.udb-modules input[type="checkbox"]'));

	if (!config || !toggles.length) return;

	let saved = toggles.map((toggle) => toggle.checked);
	let controller = null;

	toggles.forEach(function (toggle) {
		toggle.addEventListener("change", function () {
			showError(toggle, "");
			showStatus(toggle);
			save(toggle);
		});
	});

	/**
	 * Save all toggles.
	 *
	 * @param {HTMLInputElement} changed The toggle the user changed (shows the error).
	 */
	function save(changed) {
		if (controller) controller.abort();

		controller = new AbortController();

		const state = toggles.map((toggle) => toggle.checked);
		const data = new FormData();

		data.append("action", "udb_handle_module_actions");
		data.append("nonce", config.nonce);

		toggles.forEach(function (toggle, index) {
			data.append("modules[" + toggle.name + "]", state[index] ? "true" : "false");
		});

		fetch(config.ajaxUrl, { method: "POST", body: data, signal: controller.signal })
			.then((response) => response.json())
			.then(function (response) {
				if (!response.success) {
					throw { message: "string" === typeof response.data ? response.data : "" };
				}

				saved = state;
			})
			.catch(function (error) {
				if ("AbortError" === error.name) return;

				toggles.forEach(function (toggle, index) {
					toggle.checked = saved[index];
					showStatus(toggle);
				});

				// Only the server's own message; anything else (network, invalid response) gets the generic one.
				showError(changed, error instanceof Error ? config.strings.error : error.message || config.strings.error);
			});
	}

	/**
	 * Show a toggle's state in its box's Active/Inactive badge.
	 *
	 * @param {HTMLInputElement} toggle The toggle.
	 */
	function showStatus(toggle) {
		const badge = toggle.closest(".heatbox").querySelector(".heatbox-badge");

		badge.textContent = toggle.checked ? config.strings.active : config.strings.inactive;
		badge.classList.toggle("is-info", toggle.checked);
		badge.classList.toggle("is-neutral", !toggle.checked);
	}

	/**
	 * Show (or clear) the error message in a toggle's box.
	 *
	 * @param {HTMLInputElement} toggle The toggle.
	 * @param {string} message The message; empty to clear it.
	 */
	function showError(toggle, message) {
		const notice = toggle.closest(".heatbox").querySelector(".heatbox-notice");

		notice.textContent = message;
		notice.hidden = !message;
		notice.classList.toggle("is-shown", !!message);
	}
})();
