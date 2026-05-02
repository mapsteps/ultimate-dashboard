(function ($) {
	$(document)
		.on("udb_dashboard_jquery_init", function () {
			var nonce = $(".udb-features-page").find("#udb_module_nonce");

			/** @type {JQuery<HTMLInputElement>} */
			const checkboxes = $(".udb-features-page").find(
				'.status-switch input[type="checkbox"]',
			);

			/**
			 * Tracks pending module changes that haven't been saved yet.
			 * @type {Object<string, string>}
			 */
			var pendingChanges = {};

			/**
			 * The currently in-flight XHR (jQuery jqXHR), or null.
			 * @type {JQuery.jqXHR|null}
			 */
			var currentXhr = null;

			/**
			 * Set of module names that are currently in a "loading" state.
			 * @type {Object<string, boolean>}
			 */
			var loadingModules = {};

			checkboxes.on("change", function () {
				const $checkbox = $(this);
				const $heatbox = $checkbox.parents(".heatbox");
				const $statusTag = $heatbox.find(".status-code");
				const moduleName = $checkbox.attr("name") || "";

				// Update the status text immediately.
				$checkbox.prop("checked")
					? $statusTag.html(
							'<span class="active">' +
								wp.escapeHtml.escapeAttribute($statusTag.data("active-text")) +
								"</span>",
						)
					: $statusTag.html(
							'<span class="inactive">' +
								wp.escapeHtml.escapeAttribute(
									$statusTag.data("inactive-text"),
								) +
								"</span>",
						);

				// Clear any previous error on this box.
				$heatbox.find(".feature-save-error").remove();

				// Track this change.
				pendingChanges[moduleName] = $checkbox.prop("checked")
					? "true"
					: "false";

				// Show loading indicator for this heatbox.
				showLoading($heatbox);
				loadingModules[moduleName] = true;

				// Abort the previous in-flight request.
				if (currentXhr) {
					currentXhr.abort();
					currentXhr = null;
				}

				// Fire a new request with all pending changes.
				sendBatchSave();
			});

			/**
			 * Show the loading spinner in a heatbox's h2.
			 *
			 * @param {JQuery} $heatbox The heatbox element.
			 */
			function showLoading($heatbox) {
				var $indicator = $heatbox.find(".heatbox-status-indicator");

				// Create the indicator element if it doesn't exist.
				if (!$indicator.length) {
					$indicator = $('<span class="heatbox-status-indicator"></span>');
					$heatbox.find("h2").append($indicator);
				}

				// Reset classes and force reflow for animation restart.
				$indicator.removeClass("is-done").addClass("is-loading").text("");
			}

			/**
			 * Show the success indicator (🚀) in a heatbox's h2.
			 *
			 * @param {JQuery} $heatbox The heatbox element.
			 */
			function showDone($heatbox) {
				var $indicator = $heatbox.find(".heatbox-status-indicator");
				if (!$indicator.length) return;

				$indicator.removeClass("is-loading").text("🚀");

				// Force reflow to restart the CSS animation.
				void $indicator[0].offsetWidth;

				$indicator.addClass("is-done");

				// Clean up after animation completes.
				setTimeout(function () {
					$indicator.removeClass("is-done").text("");
				}, 1600);
			}

			/**
			 * Hide the loading indicator on a heatbox.
			 *
			 * @param {JQuery} $heatbox The heatbox element.
			 */
			function hideLoading($heatbox) {
				var $indicator = $heatbox.find(".heatbox-status-indicator");
				if (!$indicator.length) return;

				$indicator.removeClass("is-loading is-done").text("");
			}

			/**
			 * Show an error message in a heatbox.
			 *
			 * @param {JQuery} $heatbox The heatbox element.
			 * @param {string} message  The error message.
			 */
			function showError($heatbox, message) {
				// Remove any existing error first.
				$heatbox.find(".feature-save-error").remove();

				var $error = $('<div class="feature-save-error"></div>').text(message);

				// Insert before the feature-status bar.
				$heatbox.find(".feature-status").before($error);
			}

			/**
			 * Revert a checkbox to its opposite state.
			 *
			 * @param {string} moduleName The module name (checkbox name attribute).
			 */
			function revertCheckbox(moduleName) {
				var $checkbox = checkboxes.filter('[name="' + moduleName + '"]');
				if (!$checkbox.length) return;

				var $heatbox = $checkbox.parents(".heatbox");
				var $statusTag = $heatbox.find(".status-code");

				// Toggle back to the opposite of what we attempted.
				var wasChecked = $checkbox.prop("checked");
				$checkbox.prop("checked", !wasChecked);

				!wasChecked
					? $statusTag.html(
							'<span class="active">' +
								wp.escapeHtml.escapeAttribute($statusTag.data("active-text")) +
								"</span>",
						)
					: $statusTag.html(
							'<span class="inactive">' +
								wp.escapeHtml.escapeAttribute(
									$statusTag.data("inactive-text"),
								) +
								"</span>",
						);
			}

			/**
			 * Send all pending changes in a single AJAX request.
			 */
			function sendBatchSave() {
				// Snapshot the changes we're about to send.
				var changesToSend = $.extend({}, pendingChanges);

				var data = {
					action: "udb_handle_module_actions",
					nonce: nonce.val(),
					modules: changesToSend,
				};

				currentXhr = $.ajax({
					dataType: "json",
					data: data,
					method: "POST",
					url: window.ajaxurl,
					success: function (response) {
						currentXhr = null;

						if (response.success) {
							// Remove the sent changes from pending.
							$.each(changesToSend, function (name) {
								delete pendingChanges[String(name)];
							});

							// Show success on each saved module's heatbox.
							$.each(changesToSend, function (name) {
								var moduleName = String(name);
								var $cb = checkboxes.filter('[name="' + moduleName + '"]');

								if ($cb.length) {
									var $hb = $cb.parents(".heatbox");
									delete loadingModules[moduleName];
									showDone($hb);
								}
							});
						} else {
							handleSaveError(changesToSend, response.data || "Save failed.");
						}
					},
					error: function (jqXHR, textStatus) {
						currentXhr = null;

						// Don't treat an intentional abort as an error.
						if (textStatus === "abort") return;

						handleSaveError(changesToSend, "An error occurred while saving.");
					},
				});
			}

			/**
			 * Handle a save error: revert checkboxes and show error messages.
			 *
			 * @param {Object<string, string>} failedChanges The changes that failed.
			 * @param {string}                  message       The error message.
			 */
			function handleSaveError(failedChanges, message) {
				$.each(failedChanges, function (name) {
					var moduleName = String(name);
					var $cb = checkboxes.filter('[name="' + moduleName + '"]');

					if ($cb.length) {
						var $hb = $cb.parents(".heatbox");
						delete loadingModules[moduleName];
						delete pendingChanges[moduleName];
						hideLoading($hb);
						revertCheckbox(moduleName);
						showError($hb, message);
					}
				});
			}
		})
		.trigger("udb_dashboard_jquery_init");
})(jQuery);
