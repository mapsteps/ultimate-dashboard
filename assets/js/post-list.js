/**
 * Dashboard Widgets & Admin Pages lists: the "Active" toggle saves right away.
 *
 * If the save fails, the toggle goes back and a short error shows under it.
 * The AJAX action comes from the toggle's data-action.
 */
(function () {
	"use strict";

	document.querySelectorAll('input[name="udb_is_active"][data-action]').forEach(function (toggle) {
		toggle.addEventListener("change", function () {
			save(toggle);
		});
	});

	/**
	 * Save a toggle's state.
	 *
	 * @param {HTMLInputElement} toggle The toggle.
	 */
	function save(toggle) {
		const wrapper = toggle.closest(".status-switch");
		const notice = wrapper.querySelector(".heatbox-notice");
		const data = new FormData();

		data.append("action", toggle.dataset.action);
		data.append("nonce", toggle.dataset.nonce);
		data.append("post_id", toggle.dataset.postId);
		data.append("is_active", toggle.checked ? "1" : "0");

		showError(notice, "");

		fetch(window.ajaxurl, { method: "POST", body: data })
			.then((response) => response.json())
			.then(function (response) {
				if (!response.success) {
					throw { message: "string" === typeof response.data ? response.data : "" };
				}
			})
			.catch(function (error) {
				toggle.checked = !toggle.checked;

				// Only the server's own message; anything else (network, invalid response) gets the generic one.
				showError(notice, error instanceof Error ? wrapper.dataset.error : error.message || wrapper.dataset.error);
			});
	}

	/**
	 * Show (or clear) the error under a toggle.
	 *
	 * @param {HTMLElement} notice The notice element.
	 * @param {string} message The message; empty to clear it.
	 */
	function showError(notice, message) {
		notice.textContent = message;
		notice.hidden = !message;
		notice.classList.toggle("is-shown", !!message);
	}
})();
