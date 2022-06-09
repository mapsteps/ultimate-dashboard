declare var wp: any;

const listenButtonHorizontalPaddingFieldChange = () => {
	wp.customize("udb_login[button_horizontal_padding]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".wp-core-ui .button.button-large {padding: 0 " + val + ";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[button_horizontal_padding]"]'
			).innerHTML = content;
		});
	});
};

export default listenButtonHorizontalPaddingFieldChange;
