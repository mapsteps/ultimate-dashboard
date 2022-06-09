import { BgFieldsOpts } from '../../../../interfaces';
import writeBgPositionStyle from '../../../css-utilities/write-bg-position-style';
import writeStyle from '../../../css-utilities/write-style';
import getFormPosition from '../../../helpers/get-form-position';

declare var wp: any;

const listenBgImageFieldChange = (opts: BgFieldsOpts) => {
	const keyPrefix = opts.keyPrefix;

	wp.customize("udb_login[" + keyPrefix + "bg_image]", function (setting: any) {
		let cssSelector = opts.cssSelector;

		const bgImageStyleTag = document.querySelector(
			'[data-listen-value="udb_login[' + keyPrefix + 'bg_image]"]'
		) as HTMLStyleElement;

		setting.bind(function (val: string) {
			const formPosition = getFormPosition();

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

			const bgPosition = wp
				.customize("udb_login[" + keyPrefix + "bg_position]")
				.get();

			writeBgPositionStyle({
				styleEl: '[data-listen-value="udb_login[' + keyPrefix + 'bg_position]"]',
				keyPrefix: keyPrefix,
				cssSelector: cssSelector,
				bgPosition: bgPosition,
			});

			const bgSize = wp.customize("udb_login[" + keyPrefix + "bg_size]").get();

			writeStyle({
				styleEl: '[data-listen-value="udb_login[' + keyPrefix + 'bg_size]"]',
				cssSelector: cssSelector,
				cssRules: "background-size: " + bgSize + ";",
			});

		}); // End of setting.bind();

	}); // End of wp.customize();
};

export default listenBgImageFieldChange;