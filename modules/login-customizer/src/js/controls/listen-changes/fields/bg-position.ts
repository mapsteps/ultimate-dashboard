import handleBgCustomPostion from "../../helpers/handle-bg-custom-position";
import { OptsWithKeyPrefix } from "../../../interfaces";

declare var wp: any;

const listenBgPositionFieldChange = (opts: OptsWithKeyPrefix) => {
	const keyPrefix = opts.keyPrefix;

	wp.customize(
		"udb_login[" + keyPrefix + "bg_position]",
		function (setting: any) {
			setting.bind(function (val: string) {
				handleBgCustomPostion(keyPrefix, val);
			});
		}
	);
};

export default listenBgPositionFieldChange;
