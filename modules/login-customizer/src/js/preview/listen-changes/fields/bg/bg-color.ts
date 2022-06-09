declare var wp: any;

const listenBgColorFieldChange = () => {
	wp.customize("udb_login[bg_color]", function (setting: any) {
		const bgColorStyleTag = document.querySelector(
			'[data-listen-value="udb_login[bg_color]"]'
		) as HTMLStyleElement;

		setting.bind(function (val: string) {
			val = val ? val : "#f1f1f1";

			bgColorStyleTag.innerHTML = "body.login {background-color: " + val + ";}";
		});
	});
};

export default listenBgColorFieldChange;