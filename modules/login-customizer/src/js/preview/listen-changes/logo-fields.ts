import listenLogoHeightFieldChange from "./fields/logo/logo-height";
import listenLogoImageFieldChange from "./fields/logo/logo-image";
import listenLogoTitleFieldChange from "./fields/logo/logo-title";
import listenLogoUrlFieldChange from "./fields/logo/logo-url";

const listenLogoFieldsChange = () => {
	const logoLink = document.querySelector(
		".udb-login--logo-link"
	) as HTMLAnchorElement;

	listenLogoImageFieldChange({
		logoLink: logoLink,
	});

	listenLogoUrlFieldChange({
		logoLink: logoLink,
	});

	listenLogoTitleFieldChange({
		logoLink: logoLink,
	});

	listenLogoHeightFieldChange();
};

export default listenLogoFieldsChange;
