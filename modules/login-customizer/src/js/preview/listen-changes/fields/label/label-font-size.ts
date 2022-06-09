declare var wp: any;

const listenLabelFontSizeFieldChange = () => {
	wp.customize("udb_login[labels_font_size]", function (setting: any) {
		setting.bind(function (val: string) {
			val = val ? val : "14px";

			document.querySelector(
				'[data-listen-value="udb_login[labels_font_size]"]'
			).innerHTML = "#loginform label {font-size: " + val + ";}";
		});
	});
};

export default listenLabelFontSizeFieldChange;
