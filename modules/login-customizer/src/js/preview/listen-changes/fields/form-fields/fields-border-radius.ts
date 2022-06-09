declare var wp: any;

const listenFieldsBorderRadiusFieldChange = () => {
	wp.customize("udb_login[fields_border_radius]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".login input[type=text], .login input[type=password] {border-radius: " +
				  val +
				  ";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_border_radius]"]'
			).innerHTML = content;
		});
	});
};

export default listenFieldsBorderRadiusFieldChange;
