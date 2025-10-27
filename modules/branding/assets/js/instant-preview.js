(function ($) {
	const colorFields = document.querySelectorAll(".udb-color-field");

	const instantPreviewStyleTags = document.querySelectorAll(
		".udb-instant-preview",
	);

	function init() {
		if (!window.udbBrandingInstantPreview?.isProActive) {
			enableBranding();
		}

		colorFields.forEach(function (el) {
			if (!(el instanceof HTMLInputElement)) return;

			$(el).wpColorPicker({
				defaultColor: el.dataset.default,
				change: function (event, ui) {
					onTriggerChange(el, ui.color.toString());
				},
				clear: function () {},
				hide: true,
				palettes: true,
			});
		});
	}

	function enableBranding() {
		instantPreviewStyleTags.forEach(function (tag) {
			if (!(tag instanceof HTMLStyleElement)) return;
			tag.setAttribute("type", "text/css");
		});
	}

	/**
	 * Converting color from hex to rgb format.
	 *
	 * @see https://stackoverflow.com/questions/5623838/rgb-to-hex-and-hex-to-rgb#answer-5624139
	 *
	 * @param {string} hex Color in hex format.
	 */
	function hexToRgb(hex) {
		// Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
		var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
		hex = hex.replace(shorthandRegex, function (m, r, g, b) {
			return r + r + g + g + b + b;
		});

		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);

		return result
			? {
					r: parseInt(result[1], 16),
					g: parseInt(result[2], 16),
					b: parseInt(result[3], 16),
				}
			: null;
	}

	/**
	 * Function to run on color picker value's change.
	 *
	 * @param {HTMLElement} el The input field of the color picker.
	 * @param {string} color The color value.
	 */
	function onTriggerChange(el, color) {
		var triggerName = el.dataset.udbTriggerName;
		var targets = document.querySelectorAll(
			"[data-udb-prop-" + triggerName + "]",
		);

		targets.forEach(function (target) {
			let content = target.innerHTML;

			// @see https://stackoverflow.com/questions/5034781/js-regex-to-split-by-line#answer-5035058
			const lines = content.match(/[^\r\n]+/g);
			let newCssRule = "";
			let lineIndex = -1;

			let prop = target.getAttribute("data-udb-prop-" + triggerName) ?? "";

			/** @type {string[]} */
			let props = [];

			if (prop.includes(",")) {
				// @see https://stackoverflow.com/questions/661305/how-can-i-trim-the-leading-and-trailing-comma-in-javascript/#answer-661317
				prop = prop.replace(/(^,)|(,$)/g, "");

				props = prop.split(",");

				props.forEach(function (prop, index) {
					prop = prop.trim();

					if ("" === prop) {
						props.splice(index, 1);
					} else {
						props[index] = prop;
					}
				});
			} else {
				props = [prop];
			}

			props.forEach(function (prop) {
				if (!lines) return;

				lines.some(function (line, index) {
					if (!line.includes(prop)) return false;

					const str = line.split(":");
					const cssProp = wp.escapeHtml.escapeAttribute(str[0]);

					let format = "hex";
					let opacity = "1";
					let cssValue = color;

					let rgb;
					let checkOpacity;

					if ("box-shadow" === prop && target instanceof HTMLElement) {
						cssValue =
							target.dataset.udbBoxShadowValue?.replace(
								/{box_shadow_value}/g,
								color,
							) ?? "";
					} else {
						if (str[1].includes("rgb")) {
							format = "rgb";

							if (str[1].includes("rgba")) {
								format = "rgba";
								checkOpacity = str[1].split(")");
								checkOpacity = checkOpacity[0].split(",");
								opacity = checkOpacity[3];
							}
						}

						if ("rgb" === format || "rgba" === format) {
							rgb = hexToRgb(color);

							if (rgb) {
								cssValue = "rgb(" + rgb.r + ", " + rgb.g + ", " + rgb.b + ")";

								if ("rgba" === format) {
									cssValue =
										"rgba(" +
										rgb.r +
										", " +
										rgb.g +
										", " +
										rgb.b +
										", " +
										opacity +
										")";
								}
							}
						}
					}

					newCssRule = cssProp + ": " + cssValue + ";";
					lineIndex = index;

					// Stop the ".some()" loop.
					return true;
				});

				if (lineIndex > -1) {
					lines[lineIndex] = newCssRule;

					target.innerHTML = lines.join("\n");
				}
			});
		});
	}

	init();
})(jQuery);
