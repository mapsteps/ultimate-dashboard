import writeStyle from "../../../css-utilities/write-style";
import writeStyles from "../../../css-utilities/write-styles";
import writeBgPositionStyle from "../../../css-utilities/write-bg-position-style";
import writeBgSizeStyle from "../../../css-utilities/write-bg-size-style";

declare var wp: any;

const listenFormPositionFieldChange = () => {
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
					writeBgSizeStyle({
						styleEl: formBgSizeStyleTag,
						keyPrefix: "form_",
						cssSelector: ".login form, #loginform",
						bgSize: formBgSize,
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

				let formBorderWidth = wp
					.customize("udb_login[form_border_width]")
					.get();

				formBorderWidth = formBorderWidth ? formBorderWidth : "2px";

				formBorderWidthStyleTag.innerHTML =
					"#loginform {border-width: " + formBorderWidth + ";}";

				let formBorderStyle = wp
					.customize("udb_login[form_border_style]")
					.get();

				formBorderStyle = formBorderStyle ? formBorderStyle : "solid";

				formBorderStyleStyleTag.innerHTML =
					"#loginform {border-style: " + formBorderStyle + ";}";

				let formBorderColor = wp
					.customize("udb_login[form_border_color]")
					.get();

				formBorderColor = formBorderColor ? formBorderColor : "#dddddd";

				formBorderColorStyleTag.innerHTML =
					"#loginform {border-color: " + formBorderColor + ";}";
			}
		});
	});
};

export default listenFormPositionFieldChange;
