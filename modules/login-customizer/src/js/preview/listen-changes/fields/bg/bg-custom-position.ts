import { BgFieldsOpts } from "../../../../interfaces";
import writeBgPositionStyle from "../../../css-utilities/write-bg-position-style";
import getFormPosition from "../../../helpers/get-form-position";

declare var wp: any;

const listenBgCustomPositionFieldChange = (opts: BgFieldsOpts) => {
	const keyPrefix = opts.keyPrefix;

	wp.customize(
		"udb_login[" + keyPrefix + "bg_custom_position]",
		function (setting: any) {
			let cssSelector = opts.cssSelector;

			setting.bind(function (val: string) {
				const formPosition = getFormPosition();

				if (keyPrefix === "form_" && formPosition !== "default") {
					cssSelector = "#login";
				}

				var bgPosition = wp
					.customize("udb_login[" + keyPrefix + "bg_position]")
					.get();

				writeBgPositionStyle({
					styleEl:
						'[data-listen-value="udb_login[' + keyPrefix + 'bg_position]"]',
					keyPrefix: keyPrefix,
					cssSelector: cssSelector,
					bgPosition: bgPosition,
					bgCustomPosition: val,
				});
			});
		}
	);
};

export default listenBgCustomPositionFieldChange;
