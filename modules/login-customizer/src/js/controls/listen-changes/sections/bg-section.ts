import handleBgCustomPostion from "../../helpers/handle-bg-custom-position";
import handleBgCustomSize from "../../helpers/handle-bg-custom-size";

interface ListenBgSectionChangeOpts {
	sectionName: string;
	keyPrefix: string;
}

declare var wp: any;

const listenBgSectionState = (opts: ListenBgSectionChangeOpts) => {
	const sectionName = opts.sectionName;
	const keyPrefix = opts.keyPrefix;

	wp.customize.section(sectionName, function (section: any) {
		section.expanded.bind(function (isExpanded: boolean | number) {
			if (isExpanded) {
				const bgPosition = wp
					.customize("udb_login[" + keyPrefix + "bg_position]")
					.get();

				const bgSize = wp
					.customize("udb_login[" + keyPrefix + "bg_size]")
					.get();

				if (wp.customize("udb_login[" + keyPrefix + "bg_image]").get()) {
					wp.customize
						.control("udb_login[" + keyPrefix + "bg_position]")
						.activate();

					handleBgCustomPostion(keyPrefix, bgPosition);
					handleBgCustomSize(keyPrefix, bgSize);

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_repeat]")
						.activate();

					// The overlay feature is only for bg_image, not for for form_bg_image.
					if (!keyPrefix) {
						wp.customize
							.control("udb_login[enable_bg_overlay_color]")
							.activate();

						if (wp.customize("udb_login[enable_bg_overlay_color]").get()) {
							wp.customize.control("udb_login[bg_overlay_color]").activate();
						} else {
							wp.customize.control("udb_login[bg_overlay_color]").deactivate();
						}
					}
				} else {
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
			}
		});
	});
};

export default listenBgSectionState;
