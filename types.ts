import { tns } from "tiny-slider";
import type {
	escapeAttribute,
	escapeHTML,
	escapeEditableHTML,
} from "@wordpress/escape-html";
import type * as WPData from "@wordpress/data";

export {};

declare global {
	interface Window {
		ajaxurl: string;
		wp: {
			escapeHtml: {
				escapeAttribute: typeof escapeAttribute;
				escapeHTML: typeof escapeHTML;
				escapeEditableHTML: typeof escapeEditableHTML;
			};
			data: typeof WPData;
			codeEditor: {
				initialize: (
					element: string | HTMLTextAreaElement,
					settings?: {
						codemirror?: {
							mode?: string;
							lineNumbers?: boolean;
							lineWrapping?: boolean;
							styleActiveLine?: boolean;
							continueComments?: boolean;
							extraKeys?: Record<string, unknown>;
							direction?: string;
							rtlMoveVisually?: boolean;
							autoCloseBrackets?: boolean;
							autoCloseTags?: boolean;
							matchBrackets?: boolean;
							matchTags?: boolean;
							continuousScanning?: boolean;
							lint?: boolean;
							gutters?: string[];
							[key: string]: unknown;
						};
						csslint?: Record<string, unknown>;
						jshint?: Record<string, unknown>;
						htmlhint?: Record<string, unknown>;
					}
				) => {
					codemirror: {
						on: (event: string, callback: () => void) => void;
						off: (event: string, callback: () => void) => void;
						getValue: () => string;
						setValue: (value: string) => void;
						save: () => void;
						toTextArea: () => void;
						refresh: () => void;
						[key: string]: unknown;
					};
				};
				defaultSettings: {
					codemirror: Record<string, unknown>;
				};
			};
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
