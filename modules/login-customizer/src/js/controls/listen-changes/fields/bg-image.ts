import handleBgCustomPostion from "../../helpers/handle-bg-custom-position";
import { OptsWithKeyPrefix } from "../../../interfaces";
import handleBgCustomSize from "../../helpers/handle-bg-custom-size";

declare var wp: any;

const listenBgImageFieldChange = (opts: OptsWithKeyPrefix) => {
	const keyPrefix = opts.keyPrefix;

	wp.customize("udb_login[" + keyPrefix + "bg_image]", function (setting: any) {
		const bgImageField = document.querySelector(
			'[data-control-name="udb_login[' + keyPrefix + 'bg_image]"]'
		) as HTMLElement;

		setting.bind(function (val: string) {
			const bgPosition = wp
				.customize("udb_login[" + keyPrefix + "bg_position]")
				.get();

			const bgSize = wp.customize("udb_login[" + keyPrefix + "bg_size]").get();

			if (val) {
				bgImageField.classList.remove("is-empty");

				wp.customize
					.control("udb_login[" + keyPrefix + "bg_position]")
					.activate();

				handleBgCustomPostion(keyPrefix, bgPosition);

				wp.customize.control("udb_login[" + keyPrefix + "bg_size]").activate();

				handleBgCustomSize(keyPrefix, bgSize);

				wp.customize
					.control("udb_login[" + keyPrefix + "bg_repeat]")
					.activate();

				// The overlay feature is only for bg_image, not for for form_bg_image.
				if (!keyPrefix) {
					wp.customize.control("udb_login[enable_bg_overlay_color]").activate();

					if (wp.customize("udb_login[enable_bg_overlay_color]").get()) {
						wp.customize.control("udb_login[bg_overlay_color]").activate();
					} else {
						wp.customize.control("udb_login[bg_overlay_color]").deactivate();
					}
				}
			} else {
				bgImageField.classList.add("is-empty");

				wp.customize
					.control("udb_login[" + keyPrefix + "bg_position]")
					.deactivate();

				wp.customize
					.control("udb_login[" + keyPrefix + "bg_custom_position]")
					.deactivate();

				wp.customize
					.control("udb_login[" + keyPrefix + "bg_size]")
					.deactivate();

				wp.customize
					.control("udb_login[" + keyPrefix + "bg_custom_size]")
					.deactivate();

				wp.customize
					.control("udb_login[" + keyPrefix + "bg_repeat]")
					.deactivate();

				// The overlay feature is only for bg_image, not for for form_bg_image.
				if (!keyPrefix) {
					wp.customize
						.control("udb_login[enable_bg_overlay_color]")
						.deactivate();

					wp.customize.control("udb_login[bg_overlay_color]").deactivate();
				}
			}
		});
	});
};

export default listenBgImageFieldChange;
