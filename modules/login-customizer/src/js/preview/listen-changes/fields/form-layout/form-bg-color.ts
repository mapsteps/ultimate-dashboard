import { udbLoginCustomizerInterface } from "../../../../interfaces";
import writeStyles from "../../../css-utilities/write-styles";

declare var wp: any;
declare var udbLoginCustomizer: udbLoginCustomizerInterface;

const listenFormBgColorFieldChange = () => {
	wp.customize("udb_login[form_bg_color]", function (setting: any) {
		const formBgColorStyleTag = document.querySelector(
			'[data-listen-value="udb_login[form_bg_color]"]'
		) as HTMLStyleElement;

		setting.bind(function (val: string) {
			let formPosition = wp.customize("udb_login[form_position]").get();

			formPosition = !udbLoginCustomizer.isProActive ? "default" : formPosition;

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
};

export default listenFormBgColorFieldChange;
