import setupPolyfills from "./polyfills";
import { udbLoginCustomizerInterface } from "./global";
import insertProLink from "./controls/helpers/insert-pro-link";

// Controls setup functions.
import setupLoginTemplateControl from "./controls/setup-controls/login-template-control";
import setupColorControl from "./controls/setup-controls/color-control";
import setupRangeControl from "./controls/setup-controls/range-control";
import setupColorPickerControl from "./controls/setup-controls/color-picker-control";

// Panels listener functions.
import listenLoginCustomizerPanelState from "./controls/listen-changes/panels/login-customizer-panel";

// Sections listener functions.
import listenBgSectionState from "./controls/listen-changes/sections/bg-section";
import listenTemplateSectionState from "./controls/listen-changes/sections/template-section";
import listenLayoutSectionState from "./controls/listen-changes/sections/layout-section";

// Controls listener functions.
import listenEnableFormShadowFieldChange from "./controls/listen-changes/fields/enable-form-shadow";
import listenBgImageFieldChange from "./controls/listen-changes/fields/bg-image";
import listenBgPositionFieldChange from "./controls/listen-changes/fields/bg-position";

declare var wp: any;
declare var udbLoginCustomizer: udbLoginCustomizerInterface;

setupPolyfills();

/**
 * Scripts within customizer control panel.
 *
 * Used global objects:
 * - jQuery
 * - wp
 * - udbLoginCustomizer
 */
(function () {
	wp.customize.bind("ready", function () {
		setupControls();
		listen();
		if (!udbLoginCustomizer.isProActive) insertProLink();
	});

	const setupControls = () => {
		setupColorPickerControl();
		setupRangeControl();
		setupColorControl();
		setupLoginTemplateControl();
	};

	const listen = () => {
		listenPanelsState();
		listenSectionsState();
		listenFieldsChange();
	};

	const listenPanelsState = () => {
		listenLoginCustomizerPanelState();
	}

	const listenSectionsState = () => {
		listenLayoutSectionState();

		listenBgSectionState({
			sectionName: "udb_login_customizer_bg_section",
			keyPrefix: ""
		});

		listenBgSectionState({
			sectionName: "udb_login_customizer_layout_section",
			keyPrefix: "form_"
		});

		if (!udbLoginCustomizer.isProActive) {
			listenTemplateSectionState();
		} else {
			document.body.classList.add("udb-pro-active");
		}
	}

	const listenFieldsChange = () => {
		listenBgImageFieldChange({
			keyPrefix: ""
		});

		listenBgImageFieldChange({
			keyPrefix: "form_"
		});

		listenBgPositionFieldChange({
			keyPrefix: ""
		});

		listenBgPositionFieldChange({
			keyPrefix: "form_"
		});

		listenEnableFormShadowFieldChange();
	}
})();
