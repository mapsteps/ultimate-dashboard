declare var wp: any;

const listenFieldsBgColorFocusFieldChange = () => {
	wp.customize("udb_login[fields_bg_color_focus]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".login input[type=text]:focus, .login input[type=password]:focus {background-color: " +
				  val +
				  ";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_bg_color_focus]"]'
			).innerHTML = content;
		});
	});
};

export default listenFieldsBgColorFocusFieldChange;
