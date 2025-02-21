import { tns } from "tiny-slider";
import type {
	escapeAttribute,
	escapeHTML,
	escapeEditableHTML,
} from "@wordpress/escape-html";

export {};

declare global {
	interface Window {
		wp: {
			escapeHtml: {
				escapeAttribute: typeof escapeAttribute;
				escapeHTML: typeof escapeHTML;
				escapeEditableHTML: typeof escapeEditableHTML;
			};
			data: any;
		};

		udbPluginOnboarding?: {
			adminUrl: string;
			ajaxUrl: string;
			nonces: {
				saveModules: string;
				subscribe: string;
				skipDiscount: string;
			};
		};

		udbOnboardingWizard?: {
			adminUrl: string;
			ajaxUrl: string;
			nonces: {
				saveModules: string;
				saveWidgets: string;
				saveGeneralSettings: string;
				saveCustomLoginUrl: string;
				subscribe: string;
				skipDiscount: string;
			};
		};
		tns: typeof tns;
	}
}
