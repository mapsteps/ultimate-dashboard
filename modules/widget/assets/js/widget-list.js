/**
 * Setup admin page list.
 */
(function ($) {
	function init() {
		setupStatusSwitch();
	}

	function setupStatusSwitch() {
		const checkboxes = document.querySelectorAll('[name="udb_is_active"]');
		if (!checkboxes.length) return;

		checkboxes.forEach(function (checkbox) {
			checkbox.addEventListener(
				"change",
				/** @this {HTMLInputElement} */
				function () {
					changeActiveStatus(this);
				}
			);
		});
	}

	/**
	 * Switch page as active or inactive.
	 *
	 * @param {HTMLInputElement} checkbox The current checkbox.
	 */
	function changeActiveStatus(checkbox) {
		$.ajax({
			url: window.ajaxurl,
			type: "post",
			dataType: "json",
			data: {
				action: "udb_widget_change_active_status",
				nonce: checkbox.dataset.nonce,
				post_id: checkbox.dataset.postId,
				is_active: checkbox.checked ? 1 : 0,
			},
		});
	}

	init();
})(jQuery);
