declare var wp: any;

const listenRegisterAndLostPwLinksToggle = () => {
	wp.customize(
		"udb_login[remove_register_lost_pw_link]",
		function (setting: any) {
			setting.bind(function (val: boolean | number) {
				const display: string = val ? "none" : "block";

				document.querySelector(
					'[data-listen-value="udb_login[remove_register_lost_pw_link]"]'
				).innerHTML = ".login #nav {display: " + display + ";}";
			});
		}
	);
};

export default listenRegisterAndLostPwLinksToggle;
