/**
 * Setup admin page list.
 */
(function ($) {
	var ajax = {};

	function init() {
		setupStatusSwitch();
	}

	function setupStatusSwitch() {
		var checkboxes = document.querySelectorAll('[name="udb_is_active"]');
		if (!checkboxes.length) return;

		checkboxes.forEach(function (checkbox) {
			checkbox.addEventListener(
				"change",
				/**
				 * Switch page as active or inactive.
				 *
				 * @this {HTMLElement}
				 */
				function () {
					ajax.changeActiveStatus(this);
				}
			);
		});
	}

	/**
	 * Switch page as active or inactive.
	 *
	 * @param {HTMLElement} checkbox The current checkbox.
	 */
	ajax.changeActiveStatus = function (checkbox) {
		if (!(checkbox instanceof HTMLInputElement)) return;

		$.ajax({
			url: window.ajaxurl,
			type: "post",
			dataType: "json",
			data: {
				action: "udb_admin_page_change_active_status",
				nonce: checkbox.dataset.nonce,
				post_id: checkbox.dataset.postId,
				is_active: checkbox.checked ? 1 : 0,
			},
		});
	};

	init();
})(jQuery);
