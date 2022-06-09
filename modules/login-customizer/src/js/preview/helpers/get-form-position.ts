import { udbLoginCustomizerInterface } from "../../interfaces";

declare var wp: any;
declare var udbLoginCustomizer: udbLoginCustomizerInterface;

const getFormPosition = () => {
	let formPosition = wp.customize("udb_login[form_position]").get();
	formPosition = !udbLoginCustomizer.isProActive ? "default" : formPosition;

	return formPosition;
};

export default getFormPosition;
