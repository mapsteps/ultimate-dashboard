// Import fields change listeners.
import listenFormPositionFieldChange from "./fields/form-layout/form-position";
import listenFormBgColorFieldChange from "./fields/form-layout/form-bg-color";
import listenFormWidthFieldChange from "./fields/form-layout/form-width";
import listenFormTopPaddingFieldChange from "./fields/form-layout/form-top-padding";
import listenFormBottomPaddingFieldChange from "./fields/form-layout/form-bottom-padding";
import listenFormHorizontalPaddingFieldChange from "./fields/form-layout/form-horizontal-padding";
import listenFormBorderWidthFieldChange from "./fields/form-layout/form-border-width";
import listenFormBorderStyleFieldChange from "./fields/form-layout/form-border-style";
import listenFormBorderColorFieldChange from "./fields/form-layout/form-border-color";
import listenFormBorderRadiusFieldChange from "./fields/form-layout/form-border-radius";
import listenEnableFormShadowFieldChange from "./fields/form-layout/enable-form-shadow";
import listenFormShadowBlurFieldChange from "./fields/form-layout/form-shadow-blur";
import listenFormShadowColorFieldChange from "./fields/form-layout/form-shadow-color";

declare var wp: any;

const listenFormLayoutFieldsChange = () => {
	listenFormPositionFieldChange();
	listenFormBgColorFieldChange();
	listenFormWidthFieldChange();
	listenFormTopPaddingFieldChange();
	listenFormBottomPaddingFieldChange();
	listenFormHorizontalPaddingFieldChange();
	listenFormBorderWidthFieldChange();
	listenFormBorderStyleFieldChange();
	listenFormBorderColorFieldChange();
	listenFormBorderRadiusFieldChange();
	listenEnableFormShadowFieldChange();
	listenFormShadowBlurFieldChange();
	listenFormShadowColorFieldChange();
};

export default listenFormLayoutFieldsChange;
