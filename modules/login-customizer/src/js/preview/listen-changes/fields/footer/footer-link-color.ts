declare var wp: any;

const listenFooterLinkColorFieldChange = () => {
	wp.customize("udb_login[footer_link_color]", function (setting: any) {
		setting.bind(function (val: string) {
			val = val ? val : "#555d66";

			document.querySelector(
				'[data-listen-value="udb_login[footer_link_color]"]'
			).innerHTML = ".login #nav a, .login #backtoblog a {color: " + val + ";}";
		});
	});
};

export default listenFooterLinkColorFieldChange;
