import jQuery from "jquery";

import { udbLoginCustomizerInterface } from "./interfaces";

// Import custom events listeners.
import listenProNoticeEvent from "./preview/listen-changes/pro-notice-event";
import listenPreviewSwitchEvent from "./preview/listen-changes/switch-preview-event";

// Imports fields change listeners.
import listenLogoFieldsChange from "./preview/listen-changes/logo-fields";
import listenBgFieldsChange from "./preview/listen-changes/bg-fields";
import listenFormLayoutFieldsChange from "./preview/listen-changes/form-layout-fields";
import listenFormFieldsChange from "./preview/listen-changes/form-fields";
import listenLabelFieldsChange from "./preview/listen-changes/label-fields";
import listenButtonFieldsChange from "./preview/listen-changes/button-fields";
import listenFooterFieldsChange from "./preview/listen-changes/footer-fields";
import listenCustomCssFieldChange from "./preview/listen-changes/fields/custom-css";
import listenTemplateFieldChange from "./preview/listen-changes/fields/template";
import listenBgColorFieldChange from "./preview/listen-changes/fields/bg/bg-color";

declare var wp: any;
declare var udbLoginCustomizer: udbLoginCustomizerInterface;

/**
 * Scripts within customizer preview window.
 *
 * Used global objects:
 * - jQuery
 * - wp
 * - udbLoginCustomizer
 */
(function ($) {
	wp.customize.bind("preview-ready", function () {
		listen();
	});

	const listen = () => {
		listenCustomEvents();
		listenFieldsChanges();
	};

	const listenCustomEvents = () => {
		listenPreviewSwitchEvent();
		if (!udbLoginCustomizer.isProActive) listenProNoticeEvent();
	};

	const listenFieldsChanges = () => {
		if (!udbLoginCustomizer.isProActive) listenTemplateFieldChange();

		listenLogoFieldsChange();
		listenBgColorFieldChange();

		listenBgFieldsChange({
			keyPrefix: "",
			cssSelector: "body.login",
		});

		listenBgFieldsChange({
			keyPrefix: "form_",
			cssSelector: ".login form, #loginform",
		});

		listenFormLayoutFieldsChange();
		listenFormFieldsChange();
		listenLabelFieldsChange();
		listenButtonFieldsChange();
		listenFooterFieldsChange();
		listenCustomCssFieldChange();
	};
})(jQuery);
