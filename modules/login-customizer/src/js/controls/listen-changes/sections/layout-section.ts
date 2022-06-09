declare var wp: any;

const listenLayoutSectionState = () => {
	wp.customize.section(
		"udb_login_customizer_layout_section",
		function (section: any) {
			section.expanded.bind(function (isExpanded: boolean | number) {
				const formShadowEnabled = wp
					.customize("udb_login[enable_form_shadow]")
					.get();

				if (isExpanded) {
					if (wp.customize("udb_login[form_position]").get() === "default") {
						wp.customize
							.control("udb_login[form_horizontal_padding]")
							.activate();

						wp.customize.control("udb_login[form_border_width]").activate();
						wp.customize.control("udb_login[form_border_style]").activate();
						wp.customize.control("udb_login[form_border_color]").activate();
						wp.customize.control("udb_login[form_border_radius]").activate();
						wp.customize.control("udb_login[enable_form_shadow]").activate();

						if (formShadowEnabled) {
							wp.customize.control("udb_login[form_shadow_blur]").activate();
							wp.customize.control("udb_login[form_shadow_color]").activate();
						} else {
							wp.customize.control("udb_login[form_shadow_blur]").deactivate();
							wp.customize.control("udb_login[form_shadow_color]").deactivate();
						}
					}
				}
			});
		}
	);
};

export default listenLayoutSectionState;
