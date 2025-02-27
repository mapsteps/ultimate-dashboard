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
		iconPicker: () => JQuery;
		wpColorPicker(opts: {
			defaultColor?: string;
			change?: (event: JQuery.Event, ui: { color: string }) => void;
			clear?: (event: JQuery.Event) => void;
			hide?: boolean;
			palettes?: string[] | boolean;
		}): this;
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

		iconPickerIcons: string[];

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

		udbAdminBar?: {
			nonces: {
				getUsers: string;
			};
			roles: UdbSelect2Option[];
			templates: {
				menuList: string;
				submenuList: string;
			};
		};

		udbAdminBarBuilder?: {
			existingMenu: Record<string, UdbAdminBarMenuItem>;
			parsedMenu: Record<string, UdbAdminBarMenuItem>;
			builderItems: Record<string, UdbAdminBarMenuItem>;
		};

		udbAdminBarVisibility?: {
			action: string;
			nonce: string;
			roles: string[];
		};

		udbBrandingInstantPreview?: {
			isProActive: boolean;
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

	type UdbSelect2Option = {
		id: number | string;
		text: string;
		disabled?: boolean;
		selected?: boolean;
	};

	type UdbAdminMenuUserListResponse = {
		success: boolean;
		message: string;
		data: UdbSelect2Option[];
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

	type UdbAdminBarUser = UdbSelect2Option;

	type UdbAdminBarMenuItemMeta = {
		class?: string;
		rel?: string;
		target?: string;
		// There's no title attribute inside of this meta type  (maybe deprecated/changed to menu_title).
		// title?: string;
		menu_title?: string;
		onclick?: string;
		tabindex?: number;
		"aria-haspopup": boolean | number;
		html: string;
	};

	type UdbAdminBarMenuItem = {
		title: string;
		title_default: string;
		id: string;
		id_default: string;
		parent: string;
		parent_default: string;
		href: string;
		href_default: string;
		group: string;
		group_default: string;
		meta: UdbAdminBarMenuItemMeta;
		meta_default: UdbAdminBarMenuItemMeta;
		was_added: number;
		is_hidden: number;
		frontend_only?: number;
		title_encoded?: string;
		title_clean?: string;
		title_default_encoded?: string;
		title_default_clean?: string;
		icon?: string;
		disallowed_roles?: string[];
		disallowed_users?: number[];
		submenu?: Record<string, UdbAdminBarMenuItem>;
	};
}
