(function ($) {
	var ajax = {};

	function init() {
		$(document).on(
			"click",
			".udb-review-notice.is-permanent-dismissible .notice-dismiss",
			ajax.saveDismissal
		);
		$(document).on(
			"click",
			".udb-bfcm-notice.is-permanent-dismissible .notice-dismiss",
			ajax.saveDismissal
		);
	}

	ajax.saveDismissal = function (e) {
		$.ajax({
			url: ajaxurl,
			type: "post",
			data: {
				action: this.parentNode.dataset.ajaxAction,
				dismiss: 1,
			},
		}).always(function (r) {
			if (r.success) console.log(r.data);
		});
	};

	init();
})(jQuery);
