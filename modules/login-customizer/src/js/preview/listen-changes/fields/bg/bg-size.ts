import { BgFieldsOpts } from "../../../../interfaces";
import writeBgSizeStyle from "../../../css-utilities/write-bg-size-style";
import getFormPosition from "../../../helpers/get-form-position";

declare var wp: any;

const listenBgSizeFieldChange = (opts: BgFieldsOpts) => {
	const keyPrefix = opts.keyPrefix;

	wp.customize("udb_login[" + keyPrefix + "bg_size]", function (setting: any) {
		let cssSelector = opts.cssSelector;

		setting.bind(function (val: string) {
			const formPosition = getFormPosition();

			if (keyPrefix === "form_" && formPosition !== "default") {
				cssSelector = "#login";
			}

			writeBgSizeStyle({
				styleEl: '[data-listen-value="udb_login[' + keyPrefix + 'bg_size]"]',
				keyPrefix: keyPrefix,
				cssSelector: cssSelector,
				bgSize: val,
			});
		});
	});
};

export default listenBgSizeFieldChange;
