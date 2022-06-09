import handleBgCustomPostion from "../../helpers/handle-bg-custom-position";
import { OptsWithKeyPrefix } from "../../../interfaces";
import handleBgCustomSize from "../../helpers/handle-bg-custom-size";

declare var wp: any;

const listenBgImageFieldChange = (opts: OptsWithKeyPrefix) => {
	const keyPrefix = opts.keyPrefix;

	wp.customize("udb_login[" + keyPrefix + "bg_image]", function (setting: any) {
		setting.bind(function (val: string) {
			const bgPosition = wp
				.customize("udb_login[" + keyPrefix + "bg_position]")
				.get();

			const bgSize = wp.customize("udb_login[" + keyPrefix + "bg_size]").get();

			if (val) {
				document
					.querySelector(
						'[data-control-name="udb_login[' + keyPrefix + 'bg_image]"]'
					)
					.classList.remove("is-empty");

				wp.customize
					.control("udb_login[" + keyPrefix + "bg_position]")
					.activate();

				handleBgCustomPostion(keyPrefix, bgPosition);

				wp.customize.control("udb_login[" + keyPrefix + "bg_size]").activate();

				handleBgCustomSize(keyPrefix, bgSize);

				wp.customize
					.control("udb_login[" + keyPrefix + "bg_repeat]")
					.activate();
			} else {
				document
					.querySelector(
						'[data-control-name="udb_login[' + keyPrefix + 'bg_image]"]'
					)
					.classList.add("is-empty");

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
			}
		});
	});
};

export default listenBgImageFieldChange;
