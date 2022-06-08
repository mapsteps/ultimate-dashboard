
import jQuery from 'jquery';

import { udbLoginCustomizerInterface } from './global';

// Custom event functions.
import listenProNoticeEvent from './preview/listen-changes/pro-notice-event';
import listenLoginPreviewToggle from './preview/login-preview-toggle';

// Fields change functions.
import listenLogoFieldsChange from './preview/listen-changes/logo-fields';
import { listenBgColorFieldChange, listenBgFieldsChange } from './preview/listen-changes/bg-fields';
import listenTemplateFieldsChange from './preview/listen-changes/template-fields';
import listenLayoutFieldsChange from './preview/listen-changes/layout-fields';
import listenFormFieldsChange from './preview/listen-changes/form-fields';
import listenLabelFieldsChange from './preview/listen-changes/label-fields';
import listenButtonFieldsChange from './preview/listen-changes/button-fields';
import listenFooterFieldsChange from './preview/listen-changes/footer-fields';
import listenCustomCssFieldChange from './preview/listen-changes/custom-css';

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

	function listen() {
		if (!udbLoginCustomizer.isProActive) listenProNoticeEvent();

		listenLoginPreviewToggle();
		listenLogoFieldsChange();
		listenBgColorFieldChange();

		listenBgFieldsChange({
			keyPrefix: "",
			cssSelector: "body.login"
		});

		listenBgFieldsChange({
			keyPrefix: "form_",
			cssSelector: ".login form, #loginform"
		});

		if (!udbLoginCustomizer.isProActive) listenTemplateFieldsChange();
		listenLayoutFieldsChange();
		listenFormFieldsChange();
		listenLabelFieldsChange();
		listenButtonFieldsChange();
		listenFooterFieldsChange();
		listenCustomCssFieldChange();
	}
})(jQuery);
