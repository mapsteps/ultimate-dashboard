import { tns } from "tiny-slider";

export {};

declare global {
	interface Window {
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
