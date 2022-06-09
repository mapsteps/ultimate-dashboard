declare var wp: any;

const listenButtonTextColorFieldChange = () => {
	wp.customize("udb_login[button_text_color]", function (setting: any) {
		setting.bind(function (val: string) {
			val = val ? val : "#ffffff";

			document.querySelector(
				'[data-listen-value="udb_login[button_text_color]"]'
			).innerHTML = ".wp-core-ui .button.button-large {color: " + val + ";}";
		});
	});
};

export default listenButtonTextColorFieldChange;
