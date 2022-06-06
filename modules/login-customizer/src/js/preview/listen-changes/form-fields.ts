import writeStyle from "../css-utilities/write-style";

declare var wp: any;

const listenFormFieldsChange = () => {
	wp.customize("udb_login[fields_height]", function (setting: any) {
		setting.bind(function (val: string) {
			let valueUnit = val.replace(/\d+/g, "");
			valueUnit = valueUnit ? valueUnit : "px";

			let valueNumber: number = parseInt(val.replace(valueUnit, "").trim(), 10);

			const hidePwTop: number = valueNumber / 2 - 20;

			const content = val
				? ".login input[type=text], .login input[type=password] {height: " +
				val +
				";} .login .button.wp-hide-pw {margin-top: " +
				hidePwTop.toString() +
				valueUnit +
				";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_height]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[fields_horizontal_padding]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".login input[type=text], .login input[type=password] {padding: 0 " +
				val +
				";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_horizontal_padding]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[fields_border_width]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".login input[type=text], .login input[type=password] {border-width: " +
				val +
				";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_border_width]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[fields_border_radius]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".login input[type=text], .login input[type=password] {border-radius: " +
				val +
				";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_border_radius]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[fields_font_size]", function (setting: any) {
		setting.bind(function (val: string) {
			val = val ? val : "24px";

			writeStyle({
				styleEl: '[data-listen-value="udb_login[fields_font_size]"]',
				cssSelector: ".login input[type=text], .login input[type=password]",
				cssRules: "font-size: " + val + ";",
			});
		});
	});

	wp.customize("udb_login[fields_text_color]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".login input[type=text], .login input[type=password] {color: " +
				val +
				";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_text_color]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[fields_text_color_focus]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".login input[type=text]:focus, .login input[type=password]:focus {color: " +
				val +
				";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_text_color_focus]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[fields_bg_color]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".login input[type=text], .login input[type=password] {background-color: " +
				val +
				";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_bg_color]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[fields_bg_color_focus]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".login input[type=text]:focus, .login input[type=password]:focus {background-color: " +
				val +
				";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_bg_color_focus]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[fields_border_color]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".login input[type=text], .login input[type=password] {border-color: " +
				val +
				";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_border_color]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[fields_border_color_focus]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".login input[type=text]:focus, .login input[type=password]:focus {border-color: " +
				val +
				";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_border_color_focus]"]'
			).innerHTML = content;
		});
	});
};

export default listenFormFieldsChange;