import { udbLoginCustomizerInterface } from "../../global";

declare var wp: any;
declare var udbLoginCustomizer: udbLoginCustomizerInterface;

const listenLogoFieldsChange = () => {
	const logoLink = document.querySelector(
		".udb-login--logo-link"
	) as HTMLAnchorElement;

	wp.customize("udb_login[logo_image]", function (setting: any) {
		setting.bind(function (val: string) {
			if (val) {
				logoLink.style.backgroundImage = "url(" + val + ")";
			} else {
				logoLink.style.backgroundImage = "url(" + udbLoginCustomizer.wpLogoUrl + ")";
			}
		});
	});

	wp.customize("udb_login[logo_url]", function (setting: any) {
		setting.bind(function (val: string) {
			val = val.replace("{home_url}", udbLoginCustomizer.homeUrl);

			logoLink.href = val;
		});
	});

	wp.customize("udb_login[logo_title]", function (setting: any) {
		setting.bind(function (val: string) {
			logoLink.innerHTML = val;
		});
	});

	wp.customize("udb_login[logo_height]", function (setting: any) {
		setting.bind(function (val: string) {
			document.querySelector(
				'[data-listen-value="udb_login[logo_height]"]'
			).innerHTML = ".login h1 a {background-size: auto " + val + ";}";
		});
	});
};

export default listenLogoFieldsChange;