declare var wp: any;

const listenFormBorderColorFieldChange = () => {
	wp.customize("udb_login[form_border_color]", function (setting: any) {
		setting.bind(function (val: string) {
			val = val ? val : "#dddddd";

			document.querySelector(
				'[data-listen-value="udb_login[form_border_color]"]'
			).innerHTML = "#loginform {border-color: " + val + ";}";
		});
	});
};

export default listenFormBorderColorFieldChange;
