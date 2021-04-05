/**
 * Scripts within customizer preview window.
 *
 * Used global objects:
 * - jQuery
 * - wp
 * - udbLoginCustomizer
 */
(function ($, api) {
	var events = {};
	var state = {};

	wp.customize.bind('preview-ready', function () {
		listen();
	});

	function listen() {
		if (!udbLoginCustomizer.isProActive) events.previewerBinding();
		events.toggleLoginPreview();
		events.logoFieldsChange();
		events.bgFieldsChange();
		if (!udbLoginCustomizer.isProActive) events.templateFieldsChange();
		events.layoutFieldsChange();
		events.formFieldsChange();
		events.labelFieldsChange();
		events.buttonFieldsChange();
		events.footerFieldsChange();
		events.customCSSChange();
	}

	events.previewerBinding = function () {
		wp.customize.preview.bind('pro_notice', function (action) {
			if (action === 'show') {
				showProNotice();
			} else {
				hideProNotice();
			}
		});
	};

	events.toggleLoginPreview = function () {
		wp.customize.preview.bind('udb-login-customizer-goto-login-page', function (data) {
			// When the section is expanded, open the login customizer page.
			if (data.expanded) {
				api.preview.send('url', udbLoginCustomizer.loginPageUrl);
			}
		});

		wp.customize.preview.bind('udb-login-customizer-goto-home-page', function (data) {
			api.preview.send('url', data.url);
		});
	}

	events.logoFieldsChange = function () {
		wp.customize('udb_login[logo_image]', function (setting) {
			setting.bind(function (val) {

				if (val) {
					document.querySelector('.udb-login--logo-link').style.backgroundImage = 'url(' + val + ')';
				} else {
					document.querySelector('.udb-login--logo-link').style.backgroundImage = 'url(' + udbLoginCustomizer.wpLogoUrl + ')';
				}

			});
		});

		wp.customize('udb_login[logo_url]', function (setting) {
			setting.bind(function (val) {

				val = val.replace('{home_url}', udbLoginCustomizer.homeUrl);

				document.querySelector('.udb-login--logo-link').href = val;

			});
		});

		wp.customize('udb_login[logo_title]', function (setting) {
			setting.bind(function (val) {

				document.querySelector('.udb-login--logo-link').innerHTML = val;

			});
		});

		wp.customize('udb_login[logo_height]', function (setting) {
			setting.bind(function (val) {
				document.querySelector('[data-listen-value="udb_login[logo_height]"]').innerHTML = '.login h1 a {background-size: auto ' + val + ';}';
			});
		});
	};

	events.bgFieldsChange = function () {
		wp.customize('udb_login[bg_color]', function (setting) {
			setting.bind(function (val) {
				val = val ? val : '#f1f1f1';

				document.querySelector('[data-listen-value="udb_login[bg_color]"]').innerHTML = 'body.login {background-color: ' + val + ';}';
			});
		});
	};

	events.templateFieldsChange = function () {
		wp.customize('udb_login[template]', function (setting) {
			setting.bind(function (val) {

				if (val !== 'default') {
					showProNotice();
				} else {
					hideProNotice();
				}

			});
		});
	}

	events.layoutFieldsChange = function () {
		wp.customize('udb_login[form_position]', function (setting) {
			var formPositionStyleTag = document.querySelector('[data-listen-value="udb_login[form_position]"]');
			var formWidthStyleTag = document.querySelector('[data-listen-value="udb_login[form_width]"]');
			var formHorizontalPaddingStyleTag = document.querySelector('[data-listen-value="udb_login[form_horizontal_padding]"]');
			var formBorderWidthStyleTag = document.querySelector('[data-listen-value="udb_login[form_border_width]"]');

			setting.bind(function (val) {
				var formWidth = wp.customize('udb_login[form_width]').get();
				var formHorizontalPadding = wp.customize('udb_login[form_horizontal_padding]').get();
				var formBorderWidth = wp.customize('udb_login[form_border_width]').get();

				formWidth = formWidth ? formWidth : '320px';
				formHorizontalPadding = formHorizontalPadding ? formHorizontalPadding : '24px';
				formBorderWidth = formBorderWidth ? formBorderWidth : '2px';

				if (val === 'default') {
					formWidthStyleTag.innerHTML = formWidthStyleTag.innerHTML.replace('#loginform {max-width:', '#login {width:');

					formPositionStyleTag.innerHTML = '#login {margin-left: auto; margin-right: auto; width: ' + formWidth + '; min-height: 0; background-color: transparent;} #loginform {min-width: 0; max-width: none;}';

					formHorizontalPaddingStyleTag.innerHTML = '#loginform {padding-left: ' + formHorizontalPadding + '; padding-right: ' + formHorizontalPadding + ';}';

					formBorderWidthStyleTag.innerHTML = '#loginform {border-width: ' + formBorderWidth + ';}';

				}

			});
		});

		wp.customize('udb_login[form_bg_color]', function (setting) {
			setting.bind(function (val) {
				var formPosition = wp.customize('udb_login[form_position]').get();
				var content = '';

				val = val ? val : '#ffffff';
				formPosition = formPosition ? formPosition : 'default';

				if (formPosition === 'default') {
					content = '#login {background-color: transparent;} #loginform {background-color: ' + val + ';}';
				} else {
					content = '#login {background-color: ' + val + ';} #loginform {background-color: ' + val + ';}';
				}

				document.querySelector('[data-listen-value="udb_login[form_bg_color]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[form_width]', function (setting) {
			setting.bind(function (val) {
				var formPosition = wp.customize('udb_login[form_position]').get();
				var content = '';

				formPosition = formPosition ? formPosition : 'default';

				if (formPosition === 'default') {
					content = '#login {width: ' + val + ';}';
				} else {
					content = '#loginform {max-width: ' + val + ';}';
				}

				document.querySelector('[data-listen-value="udb_login[form_width]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[form_top_padding]', function (setting) {
			setting.bind(function (val) {
				var content = '#loginform {padding-top: ' + val + ';}';

				document.querySelector('[data-listen-value="udb_login[form_top_padding]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[form_bottom_padding]', function (setting) {
			setting.bind(function (val) {
				var content = '#loginform {padding-bottom: ' + val + ';}';

				document.querySelector('[data-listen-value="udb_login[form_bottom_padding]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[form_horizontal_padding]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '#loginform {padding-left: ' + val + '; padding-right: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[form_horizontal_padding]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[form_border_width]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '#loginform {border-width: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[form_border_width]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[form_border_color]', function (setting) {
			setting.bind(function (val) {
				val = val ? val : '#dddddd';

				document.querySelector('[data-listen-value="udb_login[form_border_color]"]').innerHTML = '#loginform {border-color: ' + val + ';}';
			});
		});

		wp.customize('udb_login[form_border_radius]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '#loginform {border-radius: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[form_border_radius]"]').innerHTML = content;
			});
		});
	};

	events.formFieldsChange = function () {
		wp.customize('udb_login[fields_height]', function (setting) {
			setting.bind(function (val) {
				var valueUnit = val.replace(/\d+/g, '');
				valueUnit = valueUnit ? valueUnit : 'px';

				var valueNumber = val.replace(valueUnit, '');
				valueNumber = parseInt(valueNumber.trim(), 10);

				var hidePwTop = (valueNumber / 2) - 20;

				var content = val ? '.login input[type=text], .login input[type=password] {height: ' + val + ';} .login .button.wp-hide-pw {margin-top: ' + hidePwTop.toString() + valueUnit + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[fields_height]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[fields_horizontal_padding]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '.login input[type=text], .login input[type=password] {padding: 0 ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[fields_horizontal_padding]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[fields_border_width]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '.login input[type=text], .login input[type=password] {border-width: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[fields_border_width]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[fields_border_radius]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '.login input[type=text], .login input[type=password] {border-radius: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[fields_border_radius]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[fields_text_color]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '.login input[type=text], .login input[type=password] {color: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[fields_text_color]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[fields_text_color_focus]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '.login input[type=text]:focus, .login input[type=password]:focus {color: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[fields_text_color_focus]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[fields_bg_color]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '.login input[type=text], .login input[type=password] {background-color: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[fields_bg_color]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[fields_bg_color_focus]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '.login input[type=text]:focus, .login input[type=password]:focus {background-color: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[fields_bg_color_focus]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[fields_border_color]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '.login input[type=text], .login input[type=password] {border-color: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[fields_border_color]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[fields_border_color_focus]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '.login input[type=text]:focus, .login input[type=password]:focus {border-color: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[fields_border_color_focus]"]').innerHTML = content;
			});
		});
	};

	events.labelFieldsChange = function () {
		wp.customize('udb_login[labels_color]', function (setting) {
			setting.bind(function (val) {
				val = val ? val : '#444444';

				document.querySelector('[data-listen-value="udb_login[labels_color]"]').innerHTML = '#loginform label {color: ' + val + ';}';
			});
		});
	};

	events.buttonFieldsChange = function () {
		wp.customize('udb_login[button_height]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '.wp-core-ui .button.button-large {height: ' + val + '; line-height: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[button_height]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[button_horizontal_padding]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '.wp-core-ui .button.button-large {padding: 0 ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[button_horizontal_padding]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[button_text_color]', function (setting) {
			setting.bind(function (val) {
				val = val ? val : '#ffffff';

				document.querySelector('[data-listen-value="udb_login[button_text_color]"]').innerHTML = '.wp-core-ui .button.button-large {color: ' + val + ';}';
			});
		});

		wp.customize('udb_login[button_text_color_hover]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '.wp-core-ui .button.button-large:hover, .wp-core-ui .button.button-large:focus {color: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[button_text_color_hover]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[button_bg_color]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '.wp-core-ui .button.button-large {background-color: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[button_bg_color]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[button_bg_color_hover]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '.wp-core-ui .button.button-large:hover, .wp-core-ui .button.button-large:focus {background-color: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[button_bg_color_hover]"]').innerHTML = content;
			});
		});

		wp.customize('udb_login[button_border_radius]', function (setting) {
			setting.bind(function (val) {
				var content = val ? '.wp-core-ui .button.button-large {border-radius: ' + val + ';}' : '';

				document.querySelector('[data-listen-value="udb_login[button_border_radius]"]').innerHTML = content;
			});
		});
	};

	events.footerFieldsChange = function () {
		wp.customize('udb_login[footer_link_color]', function (setting) {
			setting.bind(function (val) {
				val = val ? val : '#555d66';

				document.querySelector('[data-listen-value="udb_login[footer_link_color]"]').innerHTML = '.login #nav a, .login #backtoblog a {color: ' + val + ';}';
			});
		});

		wp.customize('udb_login[footer_link_color_hover]', function (setting) {
			setting.bind(function (val) {
				val = val ? val : '#00a0d2';

				document.querySelector('[data-listen-value="udb_login[footer_link_color_hover]"]').innerHTML = '.login #nav a:hover, .login #nav a:focus, .login #backtoblog a:hover, .login #backtoblog a:focus {color: ' + val + ';}';
			});
		});
	};

	events.customCSSChange = function () {
		wp.customize('udb_login[custom_css]', function (setting) {
			setting.bind(function (val) {
				val = val ? val : '';

				document.querySelector('[data-listen-value="udb_login[custom_css]"]').innerHTML = val;
			});
		});
	};

	function showProNotice(autoHide) {
		var notice = document.querySelector('.udb-pro-login-customizer-notice');
		if (!notice) return;

		notice.classList.add('is-shown');

		if (autoHide) setTimeout(hideProNotice, 3000);
	}

	function hideProNotice() {
		var notice = document.querySelector('.udb-pro-login-customizer-notice');
		if (!notice) return;

		notice.classList.remove('is-shown');
	}
})(jQuery, wp.customize);
