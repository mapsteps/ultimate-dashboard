import { tns } from "tiny-slider";
import type * as WPEscapeHtml from "@wordpress/escape-html";
import type * as WPData from "@wordpress/data";

export {};

declare global {
	interface WPCodeEditor {
		defaultSettings: WPCodeEditorSettings;
		initialize: (
			textarea: HTMLTextAreaElement,
			settings?: Partial<WPCodeEditorSettings>
		) => CodeMirrorEditor;
	}

	interface WPCodeEditorSettings {
		codemirror: CodeMirrorSettings | {};
	}

	interface CodeMirrorSettings {
		indentUnit: number;
		indentWithTabs: boolean;
		inputStyle: "contenteditable" | "textarea";
		lineNumbers: boolean;
		lineWrapping: boolean;
		styleActiveLine: boolean;
		continueComments: boolean;
		extraKeys: {
			[key: string]: string;
		};
		direction: "ltr" | "rtl";
		gutters: string[];
		mode: string;
		lint: boolean;
		autoCloseBrackets: boolean;
		autoCloseTags: boolean;
		matchTags: {
			bothTags: boolean;
		};
		autoRefresh: boolean;
	}

	// CodeMirror Editor Interface
	interface CodeMirrorEditor {
		getValue(): string;
		setValue(content: string): void;
		getWrapperElement(): HTMLElement;
		codemirror: CodeMirror;
		on(event: string, handler: Function): void;
		off(event: string, handler: Function): void;
	}

	// Additional CodeMirror type (simplified for this context)
	interface CodeMirror {
		doc: CodeMirrorDoc;
		display: any;
		options: CodeMirrorSettings;
		state: any;
		refresh(): void;
		focus(): void;
		getCursor(start?: boolean): { line: number; ch: number };
		setCursor(pos: { line: number; ch: number }): void;
		getLine(n: number): string;
		getSelection(): string;
		replaceSelection(replacement: string): void;
		somethingSelected(): boolean;
		setOption(option: string, value: any): void;
		getOption(option: string): any;
		execCommand(command: string): void;
		addKeyMap(map: object): void;
		removeKeyMap(map: object): void;
	}

	interface CodeMirrorDoc {
		getValue(): string;
		setValue(content: string): void;
		getSelection(): string;
		replaceSelection(replacement: string): void;
		lineCount(): number;
		getCursor(): { line: number; ch: number };
		setCursor(pos: { line: number; ch: number }): void;
	}

	interface Wp {
		escapeHtml: typeof WPEscapeHtml;
		data: typeof WPData;
		codeEditor: WPCodeEditor;
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
