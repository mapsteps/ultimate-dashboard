declare var wp: any;

const listenFormBorderWidthFieldChange = () => {
	wp.customize("udb_login[form_border_width]", function (setting: any) {
		setting.bind(function (val: string) {
			var content = val ? "#loginform {border-width: " + val + ";}" : "";

			document.querySelector(
				'[data-listen-value="udb_login[form_border_width]"]'
			).innerHTML = content;
		});
	});
};

export default listenFormBorderWidthFieldChange;
