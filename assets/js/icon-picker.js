/* Icon picker */

(function ($) {
	/**
	 *
	 * @returns {JQuery<HTMLElement>}
	 */
	$.fn.iconPicker = function () {
		/**
		 * Icons
		 *
		 * @type string[]
		 */
		const icons = window.iconPickerIcons ? window.iconPickerIcons : [];

		return this.each(function () {
			const eventType =
				this.tagName.toLowerCase() === "button" ? "click" : "focus";

			$(this).on(eventType, function (e) {
				if (
					!(this instanceof HTMLInputElement) &&
					!(this instanceof HTMLTextAreaElement)
				) {
					return;
				}

				createPopup(this);
			});

			/**
			 * Create popup.
			 *
			 * @param {HTMLInputElement|HTMLTextAreaElement} field
			 */
			function createPopup(field) {
				// const boxWidth = field.dataset.width;

				const popup = $(
						'<div class="icon-picker-container"> \
						<div class="icon-picker-control" /> \
						<ul class="icon-picker-list" /> \
					</div>'
					),
					list = popup.find(".icon-picker-list");

				// if (boxWidth) popup.width(boxWidth);

				for (var i in icons) {
					list.append(
						'<li data-icon="' +
							icons[i] +
							'"><a href="#" title="' +
							icons[i] +
							'"><span class="' +
							icons[i] +
							'"></span></a></li>'
					);
				}

				$("a", list).click(function (e) {
					e.preventDefault();
					var title = $(this).attr("title");
					field.dataset.icon = title;

					if (field.tagName.toLowerCase() === "input") {
						var prevVal = field.value;
						field.value = "" + title;
						if (field.value !== prevVal)
							field.dispatchEvent(new Event("change"));
					}

					$(document).trigger("iconPicker:selected", [field, title]);

					field.blur();
					removePopup();
				});

				var control = popup.find(".icon-picker-control");

				control.prepend(
					'<a data-direction="back" href="#"> \
					<span class="dashicons dashicons-arrow-left-alt2"></span></a> \
					<span class="icon-picker--search-wrapper"><input type="text" class="" placeholder="Search" /></span> \
					<a data-direction="forward" href="#"><span class="dashicons dashicons-arrow-right-alt2"></span></a>'
				);

				$("a", control).click(function (e) {
					e.preventDefault();
					if ($(this).data("direction") === "back") {
						$("li:gt(" + (icons.length - 26) + ")", list).prependTo(list);
					} else {
						$("li:lt(25)", list).appendTo(list);
					}
				});

				if (field.parentElement) popup.appendTo(field.parentElement).show();

				$("input", control).on("keyup", function (e) {
					const search = $(this).val() ?? "";
					if (typeof search !== "string") return;

					if (search === "") {
						$("li:lt(25)", list).show();
					} else {
						$("li", list).each(function () {
							if (
								$(this)
									.data("icon")
									.toLowerCase()
									.indexOf(search.toLowerCase()) !== -1
							) {
								$(this).show();
							} else {
								$(this).hide();
							}
						});
					}
				});

				$(document).bind("mouseup.icon-picker", function (e) {
					if (
						(e.target instanceof HTMLInputElement ||
							e.target instanceof HTMLTextAreaElement) &&
						e.target !== field &&
						!popup.is(e.target) &&
						popup.has(e.target).length === 0
					) {
						removePopup();
					}
				});
			}

			function removePopup() {
				$(".icon-picker-container").remove();
				$(document).unbind(".icon-picker");
			}
		});
	};

	$(function () {
		$(".icon-picker").iconPicker();
	});
})(jQuery);
