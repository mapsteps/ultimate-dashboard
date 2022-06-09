import listenButtonBgColorFieldChange from "./fields/button/button-bg-color";
import listenButtonBgColorHoverFieldChange from "./fields/button/button-bg-color-hover";
import listenButtonBorderRadiusFieldChange from "./fields/button/button-border-radius";
import listenButtonHeightFieldChange from "./fields/button/button-height";
import listenButtonHorizontalPaddingFieldChange from "./fields/button/button-horizontal-padding";
import listenButtonTextColorFieldChange from "./fields/button/button-text-color";
import listenButtonTextColorHoverFieldChange from "./fields/button/button-text-color-hover";

const listenButtonFieldsChange = () => {
	listenButtonHeightFieldChange();
	listenButtonHorizontalPaddingFieldChange();
	listenButtonTextColorFieldChange();
	listenButtonTextColorHoverFieldChange();
	listenButtonBgColorFieldChange();
	listenButtonBgColorHoverFieldChange();
	listenButtonBorderRadiusFieldChange();
};

export default listenButtonFieldsChange;
