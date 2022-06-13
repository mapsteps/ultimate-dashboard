import { BgFieldsOpts } from "../../interfaces";
import listenBgImageFieldChange from "./fields/bg/bg-image";
import listenBgPositionFieldChange from "./fields/bg/bg-position";
import listenBgCustomPositionFieldChange from "./fields/bg/bg-custom-position";
import listenBgRepeatFieldChange from "./fields/bg/bg-repeat";
import listenBgSizeFieldChange from "./fields/bg/bg-size";
import listenBgCustomSizeFieldChange from "./fields/bg/bg-custom-size";
import listenBgOverlayColorFieldChange from "./fields/bg/bg-overlay-color";
import listenEnableBgOverlayColorFieldChange from "./fields/bg/enable-bg-overlay-color";

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

	listenBgCustomSizeFieldChange({
		keyPrefix: keyPrefix,
		cssSelector: opts.cssSelector,
	});

	// The overlay feature is only for bg_image, not for for form_bg_image.
	if (!keyPrefix) {
		listenEnableBgOverlayColorFieldChange();
		listenBgOverlayColorFieldChange();
	}
};

export default listenBgFieldsChange;
