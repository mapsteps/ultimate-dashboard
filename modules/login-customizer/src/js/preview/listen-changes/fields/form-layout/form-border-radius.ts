declare var wp: any;

const listenFormBorderRadiusFieldChange = () => {
	wp.customize("udb_login[form_border_radius]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val ? "#loginform {border-radius: " + val + ";}" : "";

			document.querySelector(
				'[data-listen-value="udb_login[form_border_radius]"]'
			).innerHTML = content;
		});
	});
};

export default listenFormBorderRadiusFieldChange;
