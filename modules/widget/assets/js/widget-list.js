/**
 * Setup admin page list.
 *
 * Used global objects:
 * - jQuery
 * - ajaxurl
 */
(function ($) {
	var ajax = {};

	function init() {
		setupStatusSwitch();
	}

	function setupStatusSwitch() {
		var checkboxes = document.querySelectorAll('[name="udb_is_active"]');
		if (!checkboxes.length) return;

		[].slice.call(checkboxes).forEach(function (checkbox) {
			checkbox.addEventListener("change", function () {
				ajax.changeActiveStatus(this);
			});
		});
	}

	/**
	 * Switch page as active or inactive.
	 *
	 * @param {HTMLElement} checkbox The current checkbox.
	 */
	ajax.changeActiveStatus = function (checkbox) {
		$.ajax({
			url: ajaxurl,
			type: "post",
			dataType: "json",
			data: {
				action: "udb_widget_change_active_status",
				nonce: checkbox.dataset.nonce,
				post_id: checkbox.dataset.postId,
				is_active: checkbox.checked ? 1 : 0,
			},
		});
	};

	init();
})(jQuery);
