import jQuery from 'jquery';

const setupColorPickerControl = () => {
	var colorFields = document.querySelectorAll(
		".udb-customize-color-picker-field"
	);

	if (colorFields.length) {
		[].slice.call(colorFields).forEach(function (el) {
			var valueField = document.getElementById(el.dataset.pickerFor) as HTMLInputElement;

			var opts = {
				change: function (event, ui) {
					var rgb = ui.color.toRgb();
					var rgba =
						"rgba(" +
						rgb.r +
						", " +
						rgb.g +
						", " +
						rgb.b +
						", " +
						ui.color._alpha +
						")";

					valueField.value = rgba;
					valueField.dispatchEvent(new Event("change"));
				},
				clear: function () {
					valueField.value = "";
					valueField.dispatchEvent(new Event("change"));
				},
				hide: true,
				palettes: true,
			};

			const $el: any = jQuery(el);

			$el.wpColorPicker(opts);
		});
	}
}

export default setupColorPickerControl;