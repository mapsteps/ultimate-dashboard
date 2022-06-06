import { udbLoginCustomizerInterface } from "../../global";
import writeBgPositionStyle from "../css-utilities/write-bg-position-style";
import writeStyle from "../css-utilities/write-style";

declare var wp: any;
declare var udbLoginCustomizer: udbLoginCustomizerInterface;

const handleBgFieldsChange = (keyPrefix: string, selector: string) => {
	wp.customize("udb_login[" + keyPrefix + "bg_image]", function (setting: any) {
		var cssSelector = selector;

		const bgImageStyleTag = document.querySelector(
			'[data-listen-value="udb_login[' + keyPrefix + 'bg_image]"]'
		) as HTMLStyleElement;

		setting.bind(function (val: string) {
			var formPosition = wp.customize("udb_login[form_position]").get();

			formPosition = !udbLoginCustomizer.isProActive
				? "default"
				: formPosition;

			if (keyPrefix === "form_" && formPosition !== "default") {
				cssSelector = "#login";
			}

			writeStyle({
				styleEl: bgImageStyleTag,
				cssSelector: cssSelector,
				cssRules: "background-image: url(" + val + ");",
			});

			var bgRepeat = wp
				.customize("udb_login[" + keyPrefix + "bg_repeat]")
				.get();

			writeStyle({
				styleEl: '[data-listen-value="udb_login[' + keyPrefix + 'bg_repeat]"]',
				cssSelector: cssSelector,
				cssRules: "background-repeat: " + bgRepeat + ";",
			});

			var bgPosition = wp
				.customize("udb_login[" + keyPrefix + "bg_position]")
				.get();

			writeBgPositionStyle({
				styleEl: '[data-listen-value="udb_login[' + keyPrefix + 'bg_position]"]',
				keyPrefix: keyPrefix,
				cssSelector: cssSelector,
				bgPosition: bgPosition,
			});

			var bgSize = wp.customize("udb_login[" + keyPrefix + "bg_size]").get();

			writeStyle({
				styleEl: '[data-listen-value="udb_login[' + keyPrefix + 'bg_size]"]',
				cssSelector: cssSelector,
				cssRules: "background-size: " + bgSize + ";",
			});
		});
	});

	wp.customize("udb_login[" + keyPrefix + "bg_repeat]", function (setting: any) {
		var cssSelector = selector;

		const bgRepeatStyleTag = document.querySelector(
			'[data-listen-value="udb_login[' + keyPrefix + 'bg_repeat]"]'
		) as HTMLStyleElement;

		setting.bind(function (val: string) {
			var formPosition = wp.customize("udb_login[form_position]").get();

			formPosition = !udbLoginCustomizer.isProActive
				? "default"
				: formPosition;

			if (keyPrefix === "form_" && formPosition !== "default") {
				cssSelector = "#login";
			}

			writeStyle({
				styleEl: bgRepeatStyleTag,
				cssSelector: cssSelector,
				cssRules: "background-repeat: " + val + ";",
			});
		});
	});

	wp.customize("udb_login[" + keyPrefix + "bg_position]", function (setting: any) {
		var cssSelector = selector;

		setting.bind(function (val: string) {
			var formPosition = wp.customize("udb_login[form_position]").get();

			formPosition = !udbLoginCustomizer.isProActive
				? "default"
				: formPosition;

			if (keyPrefix === "form_" && formPosition !== "default") {
				cssSelector = "#login";
			}

			writeBgPositionStyle({
				styleEl: '[data-listen-value="udb_login[' + keyPrefix + 'bg_position]"]',
				keyPrefix: keyPrefix,
				cssSelector: cssSelector,
				bgPosition: val,
			});
		});
	});

	// Binding bg_horizontal_position
	wp.customize(
		"udb_login[" + keyPrefix + "bg_horizontal_position]",
		function (setting: any) {
			var cssSelector = selector;

			setting.bind(function (val: string) {
				var formPosition = wp.customize("udb_login[form_position]").get();

				formPosition = !udbLoginCustomizer.isProActive
					? "default"
					: formPosition;

				if (keyPrefix === "form_" && formPosition !== "default") {
					cssSelector = "#login";
				}

				var bgPosition = wp
					.customize("udb_login[" + keyPrefix + "bg_position]")
					.get();

				writeBgPositionStyle({
					styleEl: '[data-listen-value="udb_login[' + keyPrefix + 'bg_position]"]',
					keyPrefix: keyPrefix,
					cssSelector: cssSelector,
					bgPosition: bgPosition,
					bgHorizontalPosition: val,
				});
			});
		}
	);

	// Binding bg_vertical_position
	wp.customize(
		"udb_login[" + keyPrefix + "bg_vertical_position]",
		function (setting: any) {
			var cssSelector = selector;

			setting.bind(function (val: any) {
				var formPosition = wp.customize("udb_login[form_position]").get();

				formPosition = !udbLoginCustomizer.isProActive
					? "default"
					: formPosition;

				if (keyPrefix === "form_" && formPosition !== "default") {
					cssSelector = "#login";
				}

				var bgPosition = wp
					.customize("udb_login[" + keyPrefix + "bg_position]")
					.get();

				writeBgPositionStyle({
					styleEl: '[data-listen-value="udb_login[' + keyPrefix + 'bg_position]"]',
					keyPrefix: keyPrefix,
					cssSelector: cssSelector,
					bgPosition: bgPosition,
					bgVerticalPosition: val,
				});
			});
		}
	);

	wp.customize("udb_login[" + keyPrefix + "bg_size]", function (setting: any) {
		var cssSelector = selector;

		setting.bind(function (val: string) {
			var formPosition = wp.customize("udb_login[form_position]").get();

			formPosition = !udbLoginCustomizer.isProActive
				? "default"
				: formPosition;

			if (keyPrefix === "form_" && formPosition !== "default") {
				cssSelector = "#login";
			}

			var rule = "background-size: " + val + ";";

			document.querySelector(
				'[data-listen-value="udb_login[' + keyPrefix + 'bg_size]"]'
			).innerHTML = cssSelector + " {" + rule + "}";
		});
	});
};

const listenBgFieldsChange = () => {
	wp.customize("udb_login[bg_color]", function (setting: any) {
		const bgColorStyleTag = document.querySelector(
			'[data-listen-value="udb_login[bg_color]"]'
		) as HTMLStyleElement;

		setting.bind(function (val: string) {
			val = val ? val : "#f1f1f1";

			bgColorStyleTag.innerHTML = "body.login {background-color: " + val + ";}";
		});
	});

	handleBgFieldsChange("", "body.login");
	handleBgFieldsChange("form_", ".login form, #loginform");
};

export default listenBgFieldsChange;