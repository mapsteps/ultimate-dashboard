import { BgFieldsOpts } from "../../interfaces";
import listenBgImageFieldChange from "./fields/bg/bg-image";
import listenBgPositionFieldChange from "./fields/bg/bg-position";
import listenBgCustomPositionFieldChange from "./fields/bg/bg-custom-position";
import listenBgRepeatFieldChange from "./fields/bg/bg-repeat";
import listenBgSizeFieldChange from "./fields/bg/bg-size";

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

	listenBgCustomPositionFieldChange({
		keyPrefix: keyPrefix,
		cssSelector: opts.cssSelector,
	});

	listenBgSizeFieldChange({
		keyPrefix: keyPrefix,
		cssSelector: opts.cssSelector,
	});
};

export default listenBgFieldsChange;
