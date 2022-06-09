// Import fields change listeners.
import listenFieldsHeightFieldChange from "./fields/form-fields/fields-height";
import listenFieldsHorizontalPaddingFieldChange from "./fields/form-fields/fields-horizontal-padding";
import listenFieldsBorderWidthFieldChange from "./fields/form-fields/fields-border-width";
import listenFieldsBorderRadiusFieldChange from "./fields/form-fields/fields-border-radius";
import listenFieldsFontSizeFieldChange from "./fields/form-fields/fields-font-size";
import listenFieldsTextColorFieldChange from "./fields/form-fields/fields-text-color";
import listenFieldsTextColorFocusFieldChange from "./fields/form-fields/fields-text-color-focus";
import listenFieldsBgColorFieldChange from "./fields/form-fields/fields-bg-color";
import listenFieldsBgColorFocusFieldChange from "./fields/form-fields/fields-bg-color-focus";
import listenFieldsBorderColorFieldChange from "./fields/form-fields/fields-border-color";
import listenFieldsBorderColorFocusFieldChange from "./fields/form-fields/fields-border-color-focus";

declare var wp: any;

const listenFormFieldsChange = () => {
	listenFieldsHeightFieldChange();
	listenFieldsHorizontalPaddingFieldChange();
	listenFieldsBorderWidthFieldChange();
	listenFieldsBorderRadiusFieldChange();
	listenFieldsFontSizeFieldChange();
	listenFieldsTextColorFieldChange();
	listenFieldsTextColorFocusFieldChange();
	listenFieldsBgColorFieldChange();
	listenFieldsBgColorFocusFieldChange();
	listenFieldsBorderColorFieldChange();
	listenFieldsBorderColorFocusFieldChange();
};

export default listenFormFieldsChange;
