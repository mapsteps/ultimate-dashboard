(function ($) {
	var colorFields = document.querySelectorAll('.udb-color-field');

	function init() {
		if (!colorFields.length) return;

		colorFields.forEach(function (el) {
			var opts = {
				defaultColor: el.dataset.default,
				change: function (event, ui) {
					onTriggerChange(el, ui.color.toString());
				},
				clear: function () { },
				hide: true,
				palettes: true
			};

			$(el).wpColorPicker(opts);
		});
	}

	/**
	 * Converting color from hex to rgb format.
	 * 
	 * @see https://stackoverflow.com/questions/5623838/rgb-to-hex-and-hex-to-rgb#answer-5624139
	 *
	 * @param {string} $hex Color in hex format.
	 */
	function hexToRgb($hex) {
		// Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
		var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
		hex = hex.replace(shorthandRegex, function (m, r, g, b) {
			return r + r + g + g + b + b;
		});

		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);

		return result ? {
			r: parseInt(result[1], 16),
			g: parseInt(result[2], 16),
			b: parseInt(result[3], 16)
		} : null;
	}

	/**
	 * Function to run on color picker value's change.
	 *
	 * @param {HTMLElement} el The input field of the color picker.
	 * @param {string} color The color value.
	 */
	function onTriggerChange(el, color) {
		var triggerName = el.dataset.udbTriggerName;
		var targets = document.querySelectorAll('[data-udb-triggered-by="' + triggerName + '"]');

		targets.forEach(function (target) {
			// @see https://stackoverflow.com/questions/5034781/js-regex-to-split-by-line#answer-5035058
			var content = target.innerHTML;
			var lines = content.match(/[^\r\n]+/g);
			var newCssRule = '';
			lineIndex = -1;

			lines.some(function (line, index) {
				if (line.indexOf(target.dataset.udbCssProp) > -1) {
					var str = line.split(':');

					newCssRule = str[0] + ': ' + color + ';';
					lineIndex = index;

					return true;
				}
			});

			if (lineIndex > -1) {
				lines[lineIndex] = newCssRule;
				content = lines.join('\n');

				target.innerHTML = content;
			}
		});
	}

	init();

})(jQuery);
