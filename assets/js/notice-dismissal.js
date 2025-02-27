(function ($) {
	function init() {
		$(document).on(
			"click",
			".udb-review-notice.is-permanent-dismissible .notice-dismiss",
			saveDismissal
		);
		$(document).on(
			"click",
			".udb-bfcm-notice.is-permanent-dismissible .notice-dismiss",
			saveDismissal
		);
	}

	/**
	 * Save dismissal.
	 *
	 * @param {JQuery.ClickEvent} e
	 * @this {HTMLElement}
	 */
	function saveDismissal(e) {
		$.ajax({
			url: window.ajaxurl,
			type: "post",
			data: {
				action: this.parentElement?.dataset.ajaxAction,
				dismiss: 1,
			},
		}).always(function (r) {
			if (r.success) console.log(r.data);
		});
	}

	init();
})(jQuery);
