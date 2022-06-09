import proNotice from "../helpers/pro-notice";

declare var wp: any;

const listenProNoticeEvent = () => {
	wp.customize.preview.bind("pro_notice", function (action: string) {
		if (action === "show") {
			proNotice.show();
		} else {
			proNotice.hide();
		}
	});
};

export default listenProNoticeEvent;
