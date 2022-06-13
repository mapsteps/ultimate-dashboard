declare var wp: any;

const listenEnableBgOverlayFieldChange = () => {
	wp.customize("udb_login[enable_bg_overlay_color]", function (setting: any) {
		setting.bind(function (val: boolean | number) {
			if (val) {
				wp.customize.control("udb_login[bg_overlay_color]").activate();
			} else {
				wp.customize.control("udb_login[bg_overlay_color]").deactivate();
			}
		});
	});
};

export default listenEnableBgOverlayFieldChange;
