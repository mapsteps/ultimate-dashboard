declare var wp: any;

const listenFooterFieldsChange = () => {
	wp.customize("udb_login[footer_link_color]", function (setting: any) {
		setting.bind(function (val: string) {
			val = val ? val : "#555d66";

			document.querySelector(
				'[data-listen-value="udb_login[footer_link_color]"]'
			).innerHTML =
				".login #nav a, .login #backtoblog a {color: " + val + ";}";
		});
	});

	wp.customize("udb_login[footer_link_color_hover]", function (setting: any) {
		setting.bind(function (val: string) {
			val = val ? val : "#00a0d2";

			document.querySelector(
				'[data-listen-value="udb_login[footer_link_color_hover]"]'
			).innerHTML =
				".login #nav a:hover, .login #nav a:focus, .login #backtoblog a:hover, .login #backtoblog a:focus {color: " +
				val +
				";}";
		});
	});

	wp.customize("udb_login[remove_register_lost_pw_link]", function (setting: any) {
		setting.bind(function (val: boolean | number) {
			const display: string = val ? "none" : "block";

			document.querySelector(
				'[data-listen-value="udb_login[remove_register_lost_pw_link]"]'
			).innerHTML = ".login #nav {display: " + display + ";}";
		});
	});

	wp.customize("udb_login[remove_back_to_site_link]", function (setting: any) {
		setting.bind(function (val: boolean | number) {
			const display: string = val ? "none" : "block";

			document.querySelector(
				'[data-listen-value="udb_login[remove_back_to_site_link]"]'
			).innerHTML = ".login #backtoblog {display: " + display + ";}";
		});
	});

	wp.customize("udb_login[remove_lang_switcher]", function (setting: any) {
		setting.bind(function (val: boolean | number) {
			const display: string = val ? "none" : "block";

			document.querySelector(
				'[data-listen-value="udb_login[remove_lang_switcher]"]'
			).innerHTML = "#login .language-switcher {display: " + display + ";}";
		});
	});
};

export default listenFooterFieldsChange;