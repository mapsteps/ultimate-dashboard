import { BgFieldsOpts } from "../../../../interfaces";
import writeStyle from "../../../css-utilities/write-style";
import getFormPosition from "../../../helpers/get-form-position";

declare var wp: any;

const listenBgRepeatFieldChange = (opts: BgFieldsOpts) => {
	const keyPrefix = opts.keyPrefix;

	wp.customize(
		"udb_login[" + keyPrefix + "bg_repeat]",
		function (setting: any) {
			let cssSelector = opts.cssSelector;

			const bgRepeatStyleTag = document.querySelector(
				'[data-listen-value="udb_login[' + keyPrefix + 'bg_repeat]"]'
			) as HTMLStyleElement;

			setting.bind(function (val: string) {
				const formPosition = getFormPosition();

				if (keyPrefix === "form_" && formPosition !== "default") {
					cssSelector = "#login";
				}

				writeStyle({
					styleEl: bgRepeatStyleTag,
					cssSelector: cssSelector,
					cssRules: "background-repeat: " + val + ";",
				});
			});
		}
	);
};

export default listenBgRepeatFieldChange;
