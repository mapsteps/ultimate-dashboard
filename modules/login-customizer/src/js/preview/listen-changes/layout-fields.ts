import { udbLoginCustomizerInterface } from "../../global";
import buildBoxShadowCssRule from "../css-utilities/build-box-shadow-css-rule";
import writeBgPositionStyle from "../css-utilities/write-bg-position-style";
import writeStyle from "../css-utilities/write-style";
import writeStyles from "../css-utilities/write-styles";

declare var wp: any;
declare var udbLoginCustomizer: udbLoginCustomizerInterface;

const listenLayoutFieldsChange = () => {
	wp.customize("udb_login[form_position]", function (setting: any) {
		const formPositionStyleTag = document.querySelector(
			'[data-listen-value="udb_login[form_position]"]'
		) as HTMLStyleElement;

		const formBgColorStyleTag = document.querySelector(
			'[data-listen-value="udb_login[form_bg_color]"]'
		) as HTMLStyleElement;

		const formBgImageStyleTag = document.querySelector(
			'[data-listen-value="udb_login[form_bg_image]"]'
		) as HTMLStyleElement;

		const formBgRepeatStyleTag = document.querySelector(
			'[data-listen-value="udb_login[form_bg_repeat]"]'
		) as HTMLStyleElement;

		const formBgPositionStyleTag = document.querySelector(
			'[data-listen-value="udb_login[form_bg_position]"]'
		) as HTMLStyleElement;

		const formBgSizeStyleTag = document.querySelector(
			'[data-listen-value="udb_login[form_bg_size]"]'
		) as HTMLStyleElement;

		const formWidthStyleTag = document.querySelector(
			'[data-listen-value="udb_login[form_width]"]'
		) as HTMLStyleElement;

		const formHorizontalPaddingStyleTag = document.querySelector(
			'[data-listen-value="udb_login[form_horizontal_padding]"]'
		) as HTMLStyleElement;

		const formBorderWidthStyleTag = document.querySelector(
			'[data-listen-value="udb_login[form_border_width]"]'
		) as HTMLStyleElement;

		const formBorderStyleStyleTag = document.querySelector(
			'[data-listen-value="udb_login[form_border_style]"]'
		) as HTMLStyleElement;

		const formBorderColorStyleTag = document.querySelector(
			'[data-listen-value="udb_login[form_border_color]"]'
		) as HTMLStyleElement;

		setting.bind(function (val: string) {
			const formBgColor = wp.customize("udb_login[form_bg_color]").get();
			const formBgImage = wp.customize("udb_login[form_bg_image]").get();
			const formBgRepeat = wp.customize("udb_login[form_bg_repeat]").get();
			const formBgPosition = wp.customize("udb_login[form_bg_position]").get();
			const formBgSize = wp.customize("udb_login[form_bg_size]").get();
			let formWidth = wp.customize("udb_login[form_width]").get();

			let formHorizontalPadding = wp
				.customize("udb_login[form_horizontal_padding]")
				.get();

			formWidth = formWidth ? formWidth : "320px";

			formHorizontalPadding = formHorizontalPadding
				? formHorizontalPadding
				: "24px";

			// The non "default" value is handled in the pro version.
			if (val === "default") {
				if (formBgColor) {
					writeStyles({
						styleEl: formBgColorStyleTag,
						styles: [
							{
								cssSelector: "#login",
								cssRules: "background-color: transparent;",
							},
							{
								cssSelector: ".login form, #loginform",
								cssRules: "background-color: " + formBgColor + ";",
							},
						],
					});
				}

				if (formBgImage) {
					writeStyles({
						styleEl: formBgImageStyleTag,
						styles: [
							{
								cssSelector: "#login",
								cssRules: "background-image: none;",
							},
							{
								cssSelector: ".login form, #loginform",
								cssRules: "background-image: url(" + formBgImage + ");",
							},
						],
					});
				}

				if (formBgRepeat) {
					writeStyle({
						styleEl: formBgRepeatStyleTag,
						cssSelector: ".login form, #loginform",
						cssRules: "background-repeat: " + formBgRepeat + ";",
					});
				}

				if (formBgPosition) {
					writeBgPositionStyle({
						styleEl: formBgPositionStyleTag,
						keyPrefix: "form_",
						cssSelector: ".login form, #loginform",
						bgPosition: formBgPosition,
					});
				}

				if (formBgSize) {
					writeStyle({
						styleEl: formBgSizeStyleTag,
						cssSelector: ".login form, #loginform",
						cssRules: "background-size: " + formBgSize + ";",
					});
				}

				formWidthStyleTag.innerHTML = formWidthStyleTag.innerHTML.replace(
					"#loginform {max-width:",
					"#login {width:"
				);

				formPositionStyleTag.innerHTML =
					"#login {margin-left: auto; margin-right: auto; width: " +
					formWidth +
					"; min-height: 0; background-color: transparent;} #loginform {min-width: 0; max-width: none;}";

				formHorizontalPaddingStyleTag.innerHTML =
					"#loginform {padding-left: " +
					formHorizontalPadding +
					"; padding-right: " +
					formHorizontalPadding +
					";}";

				var formBorderWidth = wp
					.customize("udb_login[form_border_width]")
					.get();

				formBorderWidth = formBorderWidth ? formBorderWidth : "2px";

				formBorderWidthStyleTag.innerHTML =
					"#loginform {border-width: " + formBorderWidth + ";}";

				var formBorderStyle = wp
					.customize("udb_login[form_border_style]")
					.get();

				formBorderStyle = formBorderStyle ? formBorderStyle : "solid";

				formBorderStyleStyleTag.innerHTML =
					"#loginform {border-style: " + formBorderStyle + ";}";

				var formBorderColor = wp
					.customize("udb_login[form_border_color]")
					.get();

				formBorderColor = formBorderColor ? formBorderColor : "#dddddd";

				formBorderColorStyleTag.innerHTML =
					"#loginform {border-color: " + formBorderColor + ";}";
			}
		});
	});

	wp.customize("udb_login[form_bg_color]", function (setting: any) {
		const formBgColorStyleTag = document.querySelector(
			'[data-listen-value="udb_login[form_bg_color]"]'
		) as HTMLStyleElement;

		setting.bind(function (val: string) {
			var formPosition = wp.customize("udb_login[form_position]").get();

			formPosition = !udbLoginCustomizer.isProActive
				? "default"
				: formPosition;

			val = val ? val : "#ffffff";
			formPosition = formPosition ? formPosition : "default";

			// The non "default" value is handled in the pro version.
			if (formPosition === "default") {
				writeStyles({
					styleEl: formBgColorStyleTag,
					styles: [
						{
							cssSelector: "#login",
							cssRules: "background-color: transparent;",
						},
						{
							cssSelector: ".login form, #loginform",
							cssRules: "background-color: " + val + ";",
						},
					],
				});
			}
		});
	});

	wp.customize("udb_login[form_width]", function (setting: any) {
		setting.bind(function (val: string) {
			var formPosition = wp.customize("udb_login[form_position]").get();

			formPosition = !udbLoginCustomizer.isProActive
				? "default"
				: formPosition;

			var content = "";

			formPosition = formPosition ? formPosition : "default";

			if (formPosition === "default") {
				content = "#login {width: " + val + ";}";
			} else {
				content = "#loginform {max-width: " + val + ";}";
			}

			document.querySelector(
				'[data-listen-value="udb_login[form_width]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[form_top_padding]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = "#loginform {padding-top: " + val + ";}";

			document.querySelector(
				'[data-listen-value="udb_login[form_top_padding]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[form_bottom_padding]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = "#loginform {padding-bottom: " + val + ";}";

			document.querySelector(
				'[data-listen-value="udb_login[form_bottom_padding]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[form_horizontal_padding]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? "#loginform {padding-left: " +
				val +
				"; padding-right: " +
				val +
				";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[form_horizontal_padding]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[form_border_width]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val ? "#loginform {border-width: " + val + ";}" : "";

			document.querySelector(
				'[data-listen-value="udb_login[form_border_width]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[form_border_style]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val ? "#loginform {border-style: " + val + ";}" : "";

			document.querySelector(
				'[data-listen-value="udb_login[form_border_style]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[form_border_color]", function (setting: any) {
		setting.bind(function (val: string) {
			val = val ? val : "#dddddd";

			document.querySelector(
				'[data-listen-value="udb_login[form_border_color]"]'
			).innerHTML = "#loginform {border-color: " + val + ";}";
		});
	});

	wp.customize("udb_login[form_border_radius]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val ? "#loginform {border-radius: " + val + ";}" : "";

			document.querySelector(
				'[data-listen-value="udb_login[form_border_radius]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[enable_form_shadow]", function (setting: any) {
		setting.bind(function (val: boolean | number) {
			let shadowBlur: string;
			let shadowColor: string;
			let content: string;

			if (val) {
				shadowBlur = wp.customize("udb_login[form_shadow_blur]").get();
				shadowColor = wp.customize("udb_login[form_shadow_color]").get();
				content = buildBoxShadowCssRule(shadowBlur, shadowColor);
			} else {
				content = "box-shadow: none;";
			}

			content = "#loginform {" + content + "}";
			document.querySelector(
				'[data-listen-value="udb_login[form_shadow]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[form_shadow_blur]", function (setting: any) {
		setting.bind(function (val: string) {
			var shadowColor = wp.customize("udb_login[form_shadow_color]").get();
			var content = buildBoxShadowCssRule(val, shadowColor);
			content = "#loginform {" + content + "}";

			document.querySelector(
				'[data-listen-value="udb_login[form_shadow]"]'
			).innerHTML = content;
		});
	});

	wp.customize("udb_login[form_shadow_color]", function (setting: any) {
		setting.bind(function (val: string) {
			var shadowBlur = wp.customize("udb_login[form_shadow_blur]").get();
			var content = buildBoxShadowCssRule(shadowBlur, val);
			content = "#loginform {" + content + "}";

			document.querySelector(
				'[data-listen-value="udb_login[form_shadow]"]'
			).innerHTML = content;
		});
	});
};

export default listenLayoutFieldsChange;