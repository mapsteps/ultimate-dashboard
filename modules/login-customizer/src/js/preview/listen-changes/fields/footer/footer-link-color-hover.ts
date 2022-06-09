declare var wp: any;

const listenFooterLinkColorHoverFieldChange = () => {
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
};

export default listenFooterLinkColorHoverFieldChange;
