import { BgFieldsOpts } from "../../../../interfaces";
import writeBgPositionStyle from "../../../css-utilities/write-bg-position-style";
import getFormPosition from "../../../helpers/get-form-position";

declare var wp: any;

const listenBgPositionFieldChange = (opts: BgFieldsOpts) => {
	const keyPrefix = opts.keyPrefix;

	wp.customize(
		"udb_login[" + keyPrefix + "bg_position]",
		function (setting: any) {
			let cssSelector = opts.cssSelector;

			setting.bind(function (val: string) {
				const formPosition = getFormPosition();

				if (keyPrefix === "form_" && formPosition !== "default") {
					cssSelector = "#login";
				}

				writeBgPositionStyle({
					styleEl:
						'[data-listen-value="udb_login[' + keyPrefix + 'bg_position]"]',
					keyPrefix: keyPrefix,
					cssSelector: cssSelector,
					bgPosition: val,
				});
			});
		}
	);
};

export default listenBgPositionFieldChange;
