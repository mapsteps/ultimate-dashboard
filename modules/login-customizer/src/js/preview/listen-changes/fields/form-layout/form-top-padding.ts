declare var wp: any;

const listenFormTopPaddingFieldChange = () => {
	wp.customize("udb_login[form_top_padding]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = "#loginform {padding-top: " + val + ";}";

			document.querySelector(
				'[data-listen-value="udb_login[form_top_padding]"]'
			).innerHTML = content;
		});
	});
};

export default listenFormTopPaddingFieldChange;
