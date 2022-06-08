import handleBgCustomPostion from "../../helpers/handle-bg-custom-position";
import { OptsWithKeyPrefix } from "../interfaces";

declare var wp: any;

const listenBgImageFieldChange = (opts: OptsWithKeyPrefix) => {
	const keyPrefix = opts.keyPrefix;

	wp.customize("udb_login[" + keyPrefix + "bg_image]", function (setting: any) {
		setting.bind(function (val: string) {
			var bgPosition = wp
				.customize("udb_login[" + keyPrefix + "bg_position]")
				.get();

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

				wp.customize
					.control("udb_login[" + keyPrefix + "bg_size]")
					.activate();

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
					.control("udb_login[" + keyPrefix + "bg_size]")
					.deactivate();

				wp.customize
					.control("udb_login[" + keyPrefix + "bg_repeat]")
					.deactivate();
			}
		});
	});
}

export default listenBgImageFieldChange;