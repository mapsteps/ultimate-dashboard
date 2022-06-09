import { BgFieldsOpts } from "../../../../interfaces";
import getFormPosition from "../../../helpers/get-form-position";
import writeBgSizeStyle from "../../../css-utilities/write-bg-size-style";

declare var wp: any;

const listenBgCustomSizeFieldChange = (opts: BgFieldsOpts) => {
	const keyPrefix = opts.keyPrefix;

	wp.customize(
		"udb_login[" + keyPrefix + "bg_custom_size]",
		function (setting: any) {
			let cssSelector = opts.cssSelector;

			setting.bind(function (val: string) {
				const formPosition = getFormPosition();

				if (keyPrefix === "form_" && formPosition !== "default") {
					cssSelector = "#login";
				}

				const bgSize = wp
					.customize("udb_login[" + keyPrefix + "bg_size]")
					.get();

				writeBgSizeStyle({
					styleEl: '[data-listen-value="udb_login[' + keyPrefix + 'bg_size]"]',
					keyPrefix: keyPrefix,
					cssSelector: cssSelector,
					bgSize: bgSize,
					bgCustomSize: val,
				});
			});
		}
	);
};

export default listenBgCustomSizeFieldChange;
