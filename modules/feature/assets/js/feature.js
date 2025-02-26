(function ($) {
	$(document)
		.on("udb_dashboard_jquery_init", function () {
			var nonce = $(".udb-features-page").find("#udb_module_nonce");

			/** @type {JQuery<HTMLInputElement>} */
			const checkboxes = $(".udb-features-page").find(
				'.status-switch input[type="checkbox"]'
			);

			checkboxes.on("change", function () {
				const $checkbox = $(this);
				const $heatbox = $checkbox.parents(".heatbox");
				const $statusTag = $heatbox.find(".status-code");

				const data = {
					action: "udb_handle_module_actions",
					status: $checkbox.prop("checked"),
					nonce: nonce.val(),
					name: $checkbox.attr("name"),
					value: $checkbox.val(),
				};

				data.status == true
					? $statusTag.html(
							'<span class="active">' +
								wp.escapeHtml.escapeAttribute($statusTag.data("active-text")) +
								"</span>"
						)
					: $statusTag.html(
							'<span class="inactive">' +
								wp.escapeHtml.escapeAttribute(
									$statusTag.data("inactive-text")
								) +
								"</span>"
						);

				$.ajax({
					beforeSend: function () {
						// $checkbox.attr("disabled", true);
						$checkbox[0].disabled = true;
					},
					complete: function () {
						$checkbox[0].disabled = false;
					},
					dataType: "json",
					data: data,
					method: "POST",
					url: window.ajaxurl,
				});
			});
		})
		.trigger("udb_dashboard_jquery_init");
})(jQuery);
