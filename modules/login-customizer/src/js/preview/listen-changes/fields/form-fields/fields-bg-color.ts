declare var wp: any;

const listenFieldsBgColorFieldChange = () => {
	wp.customize("udb_login[fields_bg_color]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".login input[type=text], .login input[type=password] {background-color: " +
				  val +
				  ";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_bg_color]"]'
			).innerHTML = content;
		});
	});
};

export default listenFieldsBgColorFieldChange;
