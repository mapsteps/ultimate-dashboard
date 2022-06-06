declare var wp: any;

const listenCustomCssFieldChange = () => {
	wp.customize("udb_login[custom_css]", function (setting: any) {
		setting.bind(function (val: string) {
			val = val ? val : "";

			document.querySelector(
				'[data-listen-value="udb_login[custom_css]"]'
			).innerHTML = val;
		});
	});
};

export default listenCustomCssFieldChange;