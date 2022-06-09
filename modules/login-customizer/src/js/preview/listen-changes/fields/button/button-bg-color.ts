declare var wp: any;

const listenButtonBgColorFieldChange = () => {
	wp.customize("udb_login[button_bg_color]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".wp-core-ui .button.button-large {background-color: " + val + ";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[button_bg_color]"]'
			).innerHTML = content;
		});
	});
};

export default listenButtonBgColorFieldChange;
