(function ($) {
	$(document)
		.on("udb_dashboard_jquery_init", function () {
			var nonce = $(".udb-features-page").find("#udb_module_nonce"),
				checkboxes = $(".udb-features-page").find(
					'.switch-control input[type="checkbox"]'
				);

			checkboxes.on("change", function () {
				var t = $(this),
					p = t.parents(".heatbox"),
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
							'<span class="active">' + statusTag.data("active-text") + "</span>"
					  )
					: statusTag.html(
							'<span class="inactive">' + statusTag.data("inactive-text") + "</span>"
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
