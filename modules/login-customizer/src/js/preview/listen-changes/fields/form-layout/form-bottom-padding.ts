declare var wp: any;

const listenFormBottomPaddingFieldChange = () => {
	wp.customize("udb_login[form_bottom_padding]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = "#loginform {padding-bottom: " + val + ";}";

			document.querySelector(
				'[data-listen-value="udb_login[form_bottom_padding]"]'
			).innerHTML = content;
		});
	});
};

export default listenFormBottomPaddingFieldChange;
