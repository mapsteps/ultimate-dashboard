import jQuery from 'jquery';

const insertProLink = () => {
	var proLink =
		'\
		<li class="accordion-section control-section udb-pro-control-section">\
			<a href="https://ultimatedashboard.io/docs/login-customizer/?utm_source=plugin&utm_medium=login_customizer_link&utm_campaign=udb" class="accordion-section-title" target="_blank" tabindex="0">\
				PRO Features available! ›\
			</a>\
		</li>\
		';

	jQuery(proLink).insertBefore(
		"#accordion-section-udb_login_customizer_template_section"
	);
}

export default insertProLink;