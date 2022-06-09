export interface udbLoginCustomizerInterface {
	isProActive: boolean;
	loginPageUrl: string;
	wpLogoUrl: string;
	homeUrl: string;
}

export interface OptsWithKeyPrefix {
	keyPrefix: string;
}

export interface BgFieldsOpts extends OptsWithKeyPrefix {
	cssSelector: string;
}

export interface OptsWithSectionNameAndKeyPrefix {
	sectionName: string;
	keyPrefix: string;
}

export interface OptsWithStyleTag {
	styleTag: HTMLStyleElement;
}

export interface LogoLinkOpts {
	logoLink: HTMLAnchorElement;
}
