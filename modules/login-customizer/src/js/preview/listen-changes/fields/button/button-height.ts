declare var wp: any;

const listenButtonHeightFieldChange = () => {
	wp.customize("udb_login[button_height]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val
				? ".wp-core-ui .button.button-large {height: " +
				  val +
				  "; line-height: " +
				  val +
				  ";}"
				: "";

			document.querySelector(
				'[data-listen-value="udb_login[button_height]"]'
			).innerHTML = content;
		});
	});
};

export default listenButtonHeightFieldChange;
