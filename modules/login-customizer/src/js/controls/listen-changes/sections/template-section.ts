declare var wp: any;

const listenTemplateSectionState = () => {
	wp.customize.section(
		"udb_login_customizer_template_section",
		function (section: any) {
			section.expanded.bind(function (isExpanded: boolean | number) {
				if (isExpanded) {
					var value = wp.customize("udb_login[template]").get();

					if (value && value !== "default") {
						wp.customize.previewer.send("pro_notice", "show");
					} else {
						wp.customize.previewer.send("pro_notice", "hide");
					}
				} else {
					wp.customize.previewer.send("pro_notice", "hide");
				}
			});
		}
	);
};

export default listenTemplateSectionState;