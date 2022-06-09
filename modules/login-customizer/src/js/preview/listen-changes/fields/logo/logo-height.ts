declare var wp: any;

const listenLogoHeightFieldChange = () => {
	wp.customize("udb_login[logo_height]", function (setting: any) {
		setting.bind(function (val: string) {
			document.querySelector(
				'[data-listen-value="udb_login[logo_height]"]'
			).innerHTML = ".login h1 a {background-size: auto " + val + ";}";
		});
	});
};

export default listenLogoHeightFieldChange;