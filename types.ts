export {};

declare global {
	interface Window {
		udbWizard?: {
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
	}
}
