import {
	LogoLinkOpts,
	udbLoginCustomizerInterface,
} from "../../../../interfaces";

declare var wp: any;
declare var udbLoginCustomizer: udbLoginCustomizerInterface;

const listenLogoUrlFieldChange = (opts: LogoLinkOpts) => {
	const logoLink = opts.logoLink;

	wp.customize("udb_login[logo_url]", function (setting: any) {
		setting.bind(function (val: string) {
			val = val.replace("{home_url}", udbLoginCustomizer.homeUrl);

			logoLink.href = val;
		});
	});
};

export default listenLogoUrlFieldChange;
