declare var wp: any;

const listenBgOverlayColorFieldChange = () => {
	wp.customize("udb_login[bg_overlay_color]", function (setting: any) {
		const bgOverlayColorStyleTag = document.querySelector(
			'[data-listen-value="udb_login[bg_overlay_color]"]'
		) as HTMLElement;

		setting.bind(function (val: string) {
			let rule: string;

			if (val) {
				rule = "background-color: " + val + ";";
				bgOverlayColorStyleTag.innerHTML = ".udb-bg-overlay {" + rule + "}";
			} else {
				rule = "background-color: transparent;";
				bgOverlayColorStyleTag.innerHTML = ".udb-bg-overlay {" + rule + "}";
			}
		});
	});
};

export default listenBgOverlayColorFieldChange;
