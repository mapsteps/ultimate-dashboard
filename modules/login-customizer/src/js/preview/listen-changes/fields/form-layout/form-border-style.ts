declare var wp: any;

const listenFormBorderStyleFieldChange = () => {
	wp.customize("udb_login[form_border_style]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val ? "#loginform {border-style: " + val + ";}" : "";

			document.querySelector(
				'[data-listen-value="udb_login[form_border_style]"]'
			).innerHTML = content;
		});
	});
};

export default listenFormBorderStyleFieldChange;
