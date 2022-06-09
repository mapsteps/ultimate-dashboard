declare var wp: any;

const listenFieldsBorderWidthFieldChange = () => {
	wp.customize("udb_login[fields_border_width]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".login input[type=text], .login input[type=password] {border-width: " +
				  val +
				  ";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_border_width]"]'
			).innerHTML = content;
		});
	});
};

export default listenFieldsBorderWidthFieldChange;
