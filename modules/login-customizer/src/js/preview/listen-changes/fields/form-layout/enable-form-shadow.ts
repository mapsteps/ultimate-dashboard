import buildBoxShadowCssRule from "../../../css-utilities/build-box-shadow-css-rule";

declare var wp: any;

const listenEnableFormShadowFieldChange = () => {
	wp.customize("udb_login[enable_form_shadow]", function (setting: any) {
		setting.bind(function (val: boolean | number) {
			let shadowBlur: string;
			let shadowColor: string;
			let content: string;

			if (val) {
				shadowBlur = wp.customize("udb_login[form_shadow_blur]").get();
				shadowColor = wp.customize("udb_login[form_shadow_color]").get();
				content = buildBoxShadowCssRule(shadowBlur, shadowColor);
			} else {
				content = "box-shadow: none;";
			}

			content = "#loginform {" + content + "}";
			document.querySelector(
				'[data-listen-value="udb_login[form_shadow]"]'
			).innerHTML = content;
		});
	});
};

export default listenEnableFormShadowFieldChange;
