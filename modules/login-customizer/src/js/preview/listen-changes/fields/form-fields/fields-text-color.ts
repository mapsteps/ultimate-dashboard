declare var wp: any;

const listenFieldsTextColorFieldChange = () => {
	wp.customize("udb_login[fields_text_color]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".login input[type=text], .login input[type=password] {color: " +
				  val +
				  ";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_text_color]"]'
			).innerHTML = content;
		});
	});
};

export default listenFieldsTextColorFieldChange;
