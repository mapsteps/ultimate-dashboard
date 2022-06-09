import buildBoxShadowCssRule from "../../../css-utilities/build-box-shadow-css-rule";

declare var wp: any;

const listenFormShadowColorFieldChange = () => {
	wp.customize("udb_login[form_shadow_color]", function (setting: any) {
		setting.bind(function (val: string) {
			const shadowBlur = wp.customize("udb_login[form_shadow_blur]").get();
			let content = buildBoxShadowCssRule(shadowBlur, val);
			content = "#loginform {" + content + "}";

			document.querySelector(
				'[data-listen-value="udb_login[form_shadow]"]'
			).innerHTML = content;
		});
	});
};

export default listenFormShadowColorFieldChange;
