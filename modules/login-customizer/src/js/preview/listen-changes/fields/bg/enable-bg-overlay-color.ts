import toggleBgOverlay from "../../../helpers/toggle-bg-overlay";

declare var wp: any;

const listenEnableBgOverlayColorFieldChange = () => {
	wp.customize("udb_login[enable_bg_overlay_color]", function (setting: any) {
		setting.bind(function (val: boolean | number) {
			toggleBgOverlay(val);
		});
	});
};

export default listenEnableBgOverlayColorFieldChange;
