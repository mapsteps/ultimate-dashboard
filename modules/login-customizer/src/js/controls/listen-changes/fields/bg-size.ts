import { OptsWithKeyPrefix } from "../../../interfaces";
import handleBgCustomSize from "../../helpers/handle-bg-custom-size";

declare var wp: any;

const listenBgSizeFieldChange = (opts: OptsWithKeyPrefix) => {
	const keyPrefix = opts.keyPrefix;

	wp.customize("udb_login[" + keyPrefix + "bg_size]", function (setting: any) {
		setting.bind(function (val: string) {
			handleBgCustomSize(keyPrefix, val);
		});
	});
};

export default listenBgSizeFieldChange;
