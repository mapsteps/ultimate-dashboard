import { BgFieldsOpts } from '../../../../interfaces';
import writeBgPositionStyle from '../../../css-utilities/write-bg-position-style';
import getFormPosition from '../../../helpers/get-form-position';

declare var wp: any;

const listenBgVerticalPositionFieldChange = (opts: BgFieldsOpts) => {
	const keyPrefix = opts.keyPrefix;

	wp.customize(
		"udb_login[" + keyPrefix + "bg_vertical_position]",
		function (setting: any) {
			let cssSelector = opts.cssSelector;

			setting.bind(function (val: any) {
				const formPosition = getFormPosition();

				if (keyPrefix === "form_" && formPosition !== "default") {
					cssSelector = "#login";
				}

				const bgPosition = wp
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
};

export default listenBgVerticalPositionFieldChange;