import listenLabelColorFieldChange from "./fields/label/label-color";
import listenLabelFontSizeFieldChange from "./fields/label/label-font-size";

const listenLabelFieldsChange = () => {
	listenLabelColorFieldChange();
	listenLabelFontSizeFieldChange();
};

export default listenLabelFieldsChange;
