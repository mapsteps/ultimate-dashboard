import proNotice from "./pro-notice";

declare var wp: any;

const listenPreviewer = () => {
	wp.customize.preview.bind("pro_notice", function (action: string) {
		if (action === "show") {
			proNotice.show();
		} else {
			proNotice.hide();
		}
	});
};

export default listenPreviewer;