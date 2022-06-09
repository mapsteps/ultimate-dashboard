import writeStyle from "../../../css-utilities/write-style";

declare var wp: any;

const listenFieldsFontSizeFieldChange = () => {
	wp.customize("udb_login[fields_font_size]", function (setting: any) {
		setting.bind(function (val: string) {
			val = val ? val : "24px";

			writeStyle({
				styleEl: '[data-listen-value="udb_login[fields_font_size]"]',
				cssSelector: ".login input[type=text], .login input[type=password]",
				cssRules: "font-size: " + val + ";",
			});
		});
	});
};

export default listenFieldsFontSizeFieldChange;
