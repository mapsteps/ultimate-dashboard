import buildBoxShadowCssRule from "../../../css-utilities/build-box-shadow-css-rule";

declare var wp: any;

const listenFormShadowBlurFieldChange = () => {
	wp.customize("udb_login[form_shadow_blur]", function (setting: any) {
		setting.bind(function (val: string) {
			const shadowColor = wp.customize("udb_login[form_shadow_color]").get();
			let content = buildBoxShadowCssRule(val, shadowColor);
			content = "#loginform {" + content + "}";

			document.querySelector(
				'[data-listen-value="udb_login[form_shadow]"]'
			).innerHTML = content;
		});
	});
};

export default listenFormShadowBlurFieldChange;
