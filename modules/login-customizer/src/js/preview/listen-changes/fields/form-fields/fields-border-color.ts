declare var wp: any;

const listenFieldsBorderColorFieldChange = () => {
	wp.customize("udb_login[fields_border_color]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".login input[type=text], .login input[type=password] {border-color: " +
				  val +
				  ";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_border_color]"]'
			).innerHTML = content;
		});
	});
};

export default listenFieldsBorderColorFieldChange;
