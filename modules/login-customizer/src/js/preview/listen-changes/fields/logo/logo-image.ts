import {
	LogoLinkOpts,
	udbLoginCustomizerInterface,
} from "../../../../interfaces";

declare var wp: any;
declare var udbLoginCustomizer: udbLoginCustomizerInterface;

const listenLogoImageFieldChange = (opts: LogoLinkOpts) => {
	const logoLink = opts.logoLink;

	wp.customize("udb_login[logo_image]", function (setting: any) {
		setting.bind(function (val: string) {
			if (val) {
				logoLink.style.backgroundImage = "url(" + val + ")";
			} else {
				logoLink.style.backgroundImage =
					"url(" + udbLoginCustomizer.wpLogoUrl + ")";
			}
		});
	});
};

export default listenLogoImageFieldChange;
