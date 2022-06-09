import { LogoLinkOpts } from "../../../../interfaces";

declare var wp: any;

const listenLogoTitleFieldChange = (opts: LogoLinkOpts) => {
	const logoLink = opts.logoLink;

	wp.customize("udb_login[logo_title]", function (setting: any) {
		setting.bind(function (val: string) {
			logoLink.innerHTML = val;
		});
	});
};

export default listenLogoTitleFieldChange;
