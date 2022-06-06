declare var wp: any;

const listenLabelFieldsChange = () => {
	wp.customize("udb_login[labels_color]", function (setting: any) {
		setting.bind(function (val: string) {
			val = val ? val : "#444444";

			document.querySelector(
				'[data-listen-value="udb_login[labels_color]"]'
			).innerHTML = "#loginform label {color: " + val + ";}";
		});
	});

	wp.customize("udb_login[labels_font_size]", function (setting: any) {
		setting.bind(function (val: string) {
			val = val ? val : "14px";

			document.querySelector(
				'[data-listen-value="udb_login[labels_font_size]"]'
			).innerHTML = "#loginform label {font-size: " + val + ";}";
		});
	});
};

export default listenLabelFieldsChange;