import { tns } from "tiny-slider";
import type * as WPEscapeHtml from "@wordpress/escape-html";
import type * as WPData from "@wordpress/data";

export {};

declare global {
	interface Wp {
		escapeHtml: typeof WPEscapeHtml;
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
	}

	const wp: Wp;

	interface JQuery {
		dashiconsPicker: () => JQuery;
	}

	interface Window {
		ajaxurl?: string;

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

		udbAdminMenu?: {
			nonces: {
				getMenu: string;
				getUsers: string;
			};
			roles: {
				key: string;
				name: string;
			}[];
			templates: {
				menuList: string;
				submenuList: string;
				menuSeparator: string;
				userTabMenu: string;
				userTabContent: string;
			};
		};
	}

	type UdbAdminMenuItem = {
		is_hidden: number;
		was_added: number;
		title_default: string;
		title: string;
		url_default: string;
		url: string;
		class_default: string;
		class: string;
		type: "menu" | "separator";
		dashicon_default: string;
		dashicon: string;
		icon_svg_default: string;
		icon_svg: string;
		id_default: string;
		id: string;
		icon_type_default: "dashicon" | "icon_svg" | "";
		icon_type: "dashicon" | "icon_svg" | "";
		submenu?: UdbAdminMenuSubmenuItem[];
	};

	type UdbAdminMenuSubmenuItem = {
		is_hidden: number;
		was_added: number;
		title_default: string;
		title: string;
		url_default: string;
		url: string;
	};

	type UdbAdminMenuUser = {
		id: number;
		text: string;
		disabled?: boolean;
	};

	type UdbAdminMenuUserListResponse = {
		success: boolean;
		message: string;
		data: UdbAdminMenuUser[];
	};

	type UdbAdminMenuGetMenuParams = {
		action: "udb_admin_menu_get_menu";
		nonce: string;
		role?: string;
		user_id?: number;
	};

	type UdbAdminMenuGetMenuResponse = {
		success: boolean;
		message: string;
		data: UdbAdminMenuItem[];
	};
}
