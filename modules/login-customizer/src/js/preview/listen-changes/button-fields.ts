declare var wp: any;

const listenButtonFieldsChange = () => {
	wp.customize("udb_login[button_height]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".wp-core-ui .button.button-large {height: " +
				val +
				"; line-height: " +
				val +
				";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[button_height]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[button_horizontal_padding]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".wp-core-ui .button.button-large {padding: 0 " + val + ";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[button_horizontal_padding]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[button_text_color]", function (setting: any) {
		setting.bind(function (val: string) {
			val = val ? val : "#ffffff";

			document.querySelector(
				'[data-listen-value="udb_login[button_text_color]"]'
			).innerHTML = ".wp-core-ui .button.button-large {color: " + val + ";}";
		});
	});

	wp.customize("udb_login[button_text_color_hover]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".wp-core-ui .button.button-large:hover, .wp-core-ui .button.button-large:focus {color: " +
				val +
				";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[button_text_color_hover]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[button_bg_color]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".wp-core-ui .button.button-large {background-color: " + val + ";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[button_bg_color]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[button_bg_color_hover]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".wp-core-ui .button.button-large:hover, .wp-core-ui .button.button-large:focus {background-color: " +
				val +
				";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[button_bg_color_hover]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[button_border_radius]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".wp-core-ui .button.button-large {border-radius: " + val + ";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[button_border_radius]"]'
			).innerHTML = content;
		});
	});
};

export default listenButtonFieldsChange;