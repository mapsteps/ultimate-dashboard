import { BgFieldsOpts } from "../../../../interfaces";
import writeBgPositionStyle from "../../../css-utilities/write-bg-position-style";
import writeStyle from "../../../css-utilities/write-style";
import getFormPosition from "../../../helpers/get-form-position";
import toggleBgOverlay from "../../../helpers/toggle-bg-overlay";

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

			if (val) {
				writeStyle({
					styleEl: bgImageStyleTag,
					cssSelector: cssSelector,
					cssRules: "background-image: url(" + val + ");",
				});

				// The overlay feature is only for bg_image, not for for form_bg_image.
				if (!keyPrefix) {
					const enableBgOverlay = wp
						.customize("udb_login[enable_bg_overlay_color]")
						.get();

					if (enableBgOverlay) {
						toggleBgOverlay(true);
					} else {
						toggleBgOverlay(false);
					}
				}
			} else {
				writeStyle({
					styleEl: bgImageStyleTag,
					cssSelector: cssSelector,
					cssRules: "background-image: none;",
				});

				// The overlay feature is only for bg_image, not for for form_bg_image.
				if (!keyPrefix) {
					toggleBgOverlay(false);
				}
			}

			const bgRepeat = wp
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
				styleEl:
					'[data-listen-value="udb_login[' + keyPrefix + 'bg_position]"]',
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
