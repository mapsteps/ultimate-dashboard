import { udbLoginCustomizerInterface } from "../../../interfaces";

declare var wp: any;
declare var udbLoginCustomizer: udbLoginCustomizerInterface;

/**
 * Listen to "Login Customizer" panel state.
 * Change the preview page when it is expanded or collapsed.
 */
const listenLoginCustomizerPanelState = () => {
	wp.customize.panel("udb_login_customizer_panel", function (section: any) {
		section.expanded.bind(function (isExpanded: boolean | number) {
			var currentUrl = wp.customize.previewer.previewUrl();

			if (isExpanded) {
				if (!currentUrl.includes(udbLoginCustomizer.loginPageUrl)) {
					wp.customize.previewer.send(
						"udb-login-customizer-goto-login-page",
						{ expanded: isExpanded }
					);
				}
			} else {
				// Head back to the home page, if we leave the "Login Customizer" panel.
				wp.customize.previewer.send("udb-login-customizer-goto-home-page", {
					url: wp.customize.settings.url.home,
				});
			}
		});
	});
};

export default listenLoginCustomizerPanelState;