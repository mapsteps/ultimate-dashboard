declare var wp: any;

const listenFieldsHeightFieldChange = () => {
	wp.customize("udb_login[fields_height]", function (setting: any) {
		setting.bind(function (val: string) {
			let valueUnit = val.replace(/\d+/g, "");
			valueUnit = valueUnit ? valueUnit : "px";

			let valueNumber: number = parseInt(val.replace(valueUnit, "").trim(), 10);

			const hidePwTop: number = valueNumber / 2 - 20;

			const content = val
				? ".login input[type=text], .login input[type=password] {height: " +
				  val +
				  ";} .login .button.wp-hide-pw {margin-top: " +
				  hidePwTop.toString() +
				  valueUnit +
				  ";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[fields_height]"]'
			).innerHTML = content;
		});
	});
};

export default listenFieldsHeightFieldChange;
