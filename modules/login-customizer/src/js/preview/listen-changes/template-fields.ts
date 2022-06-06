import proNotice from "../pro-notice";

declare var wp: any;

const listenTemplateFieldsChange = () => {
	wp.customize("udb_login[template]", function (setting: any) {
		setting.bind(function (val:any) {
			if (val !== "default") {
				proNotice.show();
			} else {
				proNotice.hide();
			}
		});
	});
};

export default listenTemplateFieldsChange;