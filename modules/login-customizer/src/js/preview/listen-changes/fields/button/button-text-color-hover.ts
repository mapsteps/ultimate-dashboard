declare var wp: any;

const listenButtonTextColorHoverFieldChange = () => {
	wp.customize("udb_login[button_text_color_hover]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".wp-core-ui .button.button-large:hover, .wp-core-ui .button.button-large:focus {color: " +
				  val +
				  ";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[button_text_color_hover]"]'
			).innerHTML = content;
		});
	});
};

export default listenButtonTextColorHoverFieldChange;
