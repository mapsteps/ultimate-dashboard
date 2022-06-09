import { udbLoginCustomizerInterface } from "../../interfaces";

declare var wp: any;
declare var udbLoginCustomizer: udbLoginCustomizerInterface;

const listenPreviewSwitchEvent = () => {
	wp.customize.preview.bind(
		"udb-login-customizer-goto-login-page",
		function (data: any) {
			// When the section is expanded, open the login customizer page.
			if (data.expanded) {
				wp.customize.preview.send("url", udbLoginCustomizer.loginPageUrl);
			}
		}
	);

	wp.customize.preview.bind(
		"udb-login-customizer-goto-home-page",
		function (data: any) {
			wp.customize.preview.send("url", data.url);
		}
	);
};

export default listenPreviewSwitchEvent;
