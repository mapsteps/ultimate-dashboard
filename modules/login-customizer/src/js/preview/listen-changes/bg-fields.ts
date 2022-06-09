import { BgFieldsOpts } from "../../interfaces";
import listenBgHorizontalPositionFieldChange from "./fields/bg/bg-horizontal-position";
import listenBgImageFieldChange from "./fields/bg/bg-image";
import listenBgPositionFieldChange from "./fields/bg/bg-position";
import listenBgRepeatFieldChange from "./fields/bg/bg-repeat";
import listenBgSizeFieldChange from "./fields/bg/bg-size";
import listenBgVerticalPositionFieldChange from "./fields/bg/bg-vertical-position";

const listenBgFieldsChange = (opts: BgFieldsOpts) => {
	const keyPrefix = opts.keyPrefix;

	listenBgImageFieldChange({
		keyPrefix: keyPrefix,
		cssSelector: opts.cssSelector,
	});

	listenBgRepeatFieldChange({
		keyPrefix: keyPrefix,
		cssSelector: opts.cssSelector,
	});

	listenBgPositionFieldChange({
		keyPrefix: keyPrefix,
		cssSelector: opts.cssSelector,
	});

	listenBgHorizontalPositionFieldChange({
		keyPrefix: keyPrefix,
		cssSelector: opts.cssSelector,
	});

	listenBgVerticalPositionFieldChange({
		keyPrefix: keyPrefix,
		cssSelector: opts.cssSelector,
	});

	listenBgSizeFieldChange({
		keyPrefix: keyPrefix,
		cssSelector: opts.cssSelector,
	});
};

export default listenBgFieldsChange;
