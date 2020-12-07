(function ($) {
	$(document)
		.on("udb_dashboard_jquery_init", function () {
			var nonce = $(".udb-dashboard-form").find("#udb_modules_nonce"),
				checkboxes = $(".udb-dashboard-form").find(
					'.switch-control input[type="checkbox"]'
				);

			checkboxes.on("change", function () {
				var t = $(this),
					p = t.parents(".form-table"),
					statusTag = p.find(".status-code");

				var data = {
					action: "udb_handle_module_actions",
					status: t.prop("checked"),
					nonce: nonce.val(),
					name: t.attr("name"),
					value: t.val(),
				};

				data.status == true
					? statusTag.html(
							'<p class="active">' + statusTag.data("active-text") + "</p>"
					  )
					: statusTag.html(
							'<p class="inactive">' + statusTag.data("inactive-text") + "</p>"
					  );

				$.ajax({
					beforeSend: function () {
						t.attr("disabled", true);
					},
					complete: function () {
						t.attr("disabled", false);
					},
					dataType: "json",
					data: data,
					method: "POST",
					url: ajaxurl,
				});
			});
		})
		.trigger("udb_dashboard_jquery_init");
})(jQuery);
