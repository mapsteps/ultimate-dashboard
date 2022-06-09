declare var wp: any;

const listenFieldsTextColorFocusFieldChange = () => {
	wp.customize("udb_login[fields_text_color_focus]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".login input[type=text]:focus, .login input[type=password]:focus {color: " +
				  val +
				  ";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_text_color_focus]"]'
			).innerHTML = content;
		});
	});
};

export default listenFieldsTextColorFocusFieldChange;
