/**
 * Dashicons Picker
 *
 * Based on: https://github.com/bradvin/dashicons-picker/
 */

(function ($) {
	/**
	 *
	 * @returns {JQuery<HTMLElement>}
	 */
	$.fn.dashiconsPicker = function () {
		/**
		 * Dashicons, in CSS order
		 *
		 * @type string[]
		 */
		const icons = [
			"menu",
			"admin-site",
			"dashboard",
			"admin-media",
			"admin-page",
			"admin-comments",
			"admin-appearance",
			"admin-plugins",
			"admin-users",
			"admin-tools",
			"admin-settings",
			"admin-network",
			"admin-generic",
			"admin-home",
			"admin-collapse",
			"filter",
			"admin-customizer",
			"admin-multisite",
			"admin-links",
			"format-links",
			"admin-post",
			"format-standard",
			"format-image",
			"format-gallery",
			"format-audio",
			"format-video",
			"format-chat",
			"format-status",
			"format-aside",
			"format-quote",
			"welcome-write-blog",
			"welcome-edit-page",
			"welcome-add-page",
			"welcome-view-site",
			"welcome-widgets-menus",
			"welcome-comments",
			"welcome-learn-more",
			"image-crop",
			"image-rotate",
			"image-rotate-left",
			"image-rotate-right",
			"image-flip-vertical",
			"image-flip-horizontal",
			"image-filter",
			"undo",
			"redo",
			"editor-bold",
			"editor-italic",
			"editor-ul",
			"editor-ol",
			"editor-quote",
			"editor-alignleft",
			"editor-aligncenter",
			"editor-alignright",
			"editor-insertmore",
			"editor-spellcheck",
			"editor-distractionfree",
			"editor-expand",
			"editor-contract",
			"editor-kitchensink",
			"editor-underline",
			"editor-justify",
			"editor-textcolor",
			"editor-paste-word",
			"editor-paste-text",
			"editor-removeformatting",
			"editor-video",
			"editor-customchar",
			"editor-outdent",
			"editor-indent",
			"editor-help",
			"editor-strikethrough",
			"editor-unlink",
			"editor-rtl",
			"editor-break",
			"editor-code",
			"editor-paragraph",
			"editor-table",
			"align-left",
			"align-right",
			"align-center",
			"align-none",
			"lock",
			"unlock",
			"calendar",
			"calendar-alt",
			"visibility",
			"hidden",
			"post-status",
			"edit",
			"post-trash",
			"trash",
			"sticky",
			"external",
			"arrow-up",
			"arrow-down",
			"arrow-left",
			"arrow-right",
			"arrow-up-alt",
			"arrow-down-alt",
			"arrow-left-alt",
			"arrow-right-alt",
			"arrow-up-alt2",
			"arrow-down-alt2",
			"arrow-left-alt2",
			"arrow-right-alt2",
			"leftright",
			"sort",
			"randomize",
			"list-view",
			"excerpt-view",
			"grid-view",
			"hammer",
			"art",
			"migrate",
			"performance",
			"universal-access",
			"universal-access-alt",
			"tickets",
			"nametag",
			"clipboard",
			"heart",
			"megaphone",
			"schedule",
			"wordpress",
			"wordpress-alt",
			"pressthis",
			"update",
			"screenoptions",
			"cart",
			"feedback",
			"cloud",
			"translation",
			"tag",
			"category",
			"archive",
			"tagcloud",
			"text",
			"media-archive",
			"media-audio",
			"media-code",
			"media-default",
			"media-document",
			"media-interactive",
			"media-spreadsheet",
			"media-text",
			"media-video",
			"playlist-audio",
			"playlist-video",
			"controls-play",
			"controls-pause",
			"controls-forward",
			"controls-skipforward",
			"controls-back",
			"controls-skipback",
			"controls-repeat",
			"controls-volumeon",
			"controls-volumeoff",
			"yes",
			"no",
			"no-alt",
			"plus",
			"plus-alt",
			"plus-alt2",
			"minus",
			"dismiss",
			"marker",
			"star-filled",
			"star-half",
			"star-empty",
			"flag",
			"info",
			"warning",
			"share",
			"share1",
			"share-alt",
			"share-alt2",
			"twitter",
			"rss",
			"email",
			"email-alt",
			"facebook",
			"facebook-alt",
			"networking",
			"googleplus",
			"location",
			"location-alt",
			"camera",
			"images-alt",
			"images-alt2",
			"video-alt",
			"video-alt2",
			"video-alt3",
			"vault",
			"shield",
			"shield-alt",
			"sos",
			"search",
			"slides",
			"analytics",
			"chart-pie",
			"chart-bar",
			"chart-line",
			"chart-area",
			"groups",
			"businessman",
			"id",
			"id-alt",
			"products",
			"awards",
			"forms",
			"testimonial",
			"portfolio",
			"book",
			"book-alt",
			"download",
			"upload",
			"backup",
			"clock",
			"lightbulb",
			"microphone",
			"desktop",
			"tablet",
			"smartphone",
			"phone",
			"smiley",
			"index-card",
			"carrot",
			"building",
			"store",
			"album",
			"palmtree",
			"tickets-alt",
			"money",
			"thumbs-up",
			"thumbs-down",
			"layout",
			"",
			"",
			"",
		];

		return this.each(function () {
			$(this).on("focus", function (e) {
				if (
					!(this instanceof HTMLInputElement) &&
					!(this instanceof HTMLTextAreaElement)
				) {
					return;
				}

				createPopup(this);
			});

			/**
			 * Create the popup
			 *
			 * @param {HTMLInputElement|HTMLTextAreaElement} field
			 * @returns {void}
			 */
			function createPopup(field) {
				// const boxWidth = field.dataset.width;

				const popup = $(
						'<div class="dashicon-picker-container"> \
						<div class="dashicon-picker-control" /> \
						<ul class="dashicon-picker-list" /> \
					</div>'
					),
					list = popup.find(".dashicon-picker-list");

				// if (boxWidth) popup.width(boxWidth);

				for (const i in icons) {
					list.append(
						'<li data-icon="' +
							icons[i] +
							'"><a href="#" title="' +
							icons[i] +
							'"><span class="dashicons dashicons-' +
							icons[i] +
							'"></span></a></li>'
					);
				}

				$("a", list).click(function (e) {
					e.preventDefault();

					const title = $(this).attr("title");
					const prevVal = field.value;

					field.value = "dashicons-" + title;
					if (field.value !== prevVal) field.dispatchEvent(new Event("change"));
					field.blur();
					removePopup();
				});

				const control = popup.find(".dashicon-picker-control");

				control.prepend(
					'<a data-direction="back" href="#"> \
					<span class="dashicons dashicons-arrow-left-alt2"></span></a> \
					<span class="dashicon-picker--search-wrapper"><input type="text" class="" placeholder="Search" /></span> \
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

				$(document).on("mouseup.dashicons-picker", function (e) {
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
				$(".dashicon-picker-container").remove();
				$(document).unbind(".dashicons-picker");
			}
		});
	};

	$(function () {
		$(".dashicons-picker").dashiconsPicker();
	});
})(jQuery);
