declare var wp: any;

const listenButtonBorderRadiusFieldChange = () => {
	wp.customize("udb_login[button_border_radius]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".wp-core-ui .button.button-large {border-radius: " + val + ";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[button_border_radius]"]'
			).innerHTML = content;
		});
	});
};

export default listenButtonBorderRadiusFieldChange;
