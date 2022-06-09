import listenFooterLinkColorFieldChange from "./fields/footer/footer-link-color";
import listenFooterLinkColorHoverFieldChange from "./fields/footer/footer-link-color-hover";
import listenBackToSiteToggle from "./fields/footer/remove-back-to-site-link";
import listenLangSwitcherToggle from "./fields/footer/remove-lang-switcher";
import listenRegisterAndLostPwLinksToggle from "./fields/footer/remove-register-lost-pw-link";

const listenFooterFieldsChange = () => {
	listenFooterLinkColorFieldChange();
	listenFooterLinkColorHoverFieldChange();

	listenRegisterAndLostPwLinksToggle();
	listenBackToSiteToggle();
	listenLangSwitcherToggle();
};

export default listenFooterFieldsChange;
