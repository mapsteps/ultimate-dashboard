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

	wp.customize.bind("preview-ready", function () {
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
		wp.customize.preview.bind("pro_notice", function (action) {
			if (action === "show") {
				showProNotice();
			} else {
				hideProNotice();
			}
		});
	};

	events.toggleLoginPreview = function () {
		wp.customize.preview.bind(
			"udb-login-customizer-goto-login-page",
			function (data) {
				// When the section is expanded, open the login customizer page.
				if (data.expanded) {
					api.preview.send("url", udbLoginCustomizer.loginPageUrl);
				}
			}
		);

		wp.customize.preview.bind(
			"udb-login-customizer-goto-home-page",
			function (data) {
				api.preview.send("url", data.url);
			}
		);
	};

	events.logoFieldsChange = function () {
		wp.customize("udb_login[logo_image]", function (setting) {
			setting.bind(function (val) {
				if (val) {
					document.querySelector(
						".udb-login--logo-link"
					).style.backgroundImage = "url(" + val + ")";
				} else {
					document.querySelector(
						".udb-login--logo-link"
					).style.backgroundImage = "url(" + udbLoginCustomizer.wpLogoUrl + ")";
				}
			});
		});

		wp.customize("udb_login[logo_url]", function (setting) {
			setting.bind(function (val) {
				val = val.replace("{home_url}", udbLoginCustomizer.homeUrl);

				document.querySelector(".udb-login--logo-link").href = val;
			});
		});

		wp.customize("udb_login[logo_title]", function (setting) {
			setting.bind(function (val) {
				document.querySelector(".udb-login--logo-link").innerHTML = val;
			});
		});

		wp.customize("udb_login[logo_height]", function (setting) {
			setting.bind(function (val) {
				document.querySelector(
					'[data-listen-value="udb_login[logo_height]"]'
				).innerHTML = ".login h1 a {background-size: auto " + val + ";}";
			});
		});
	};

	events.bgFieldsChange = function () {
		wp.customize("udb_login[bg_color]", function (setting) {
			setting.bind(function (val) {
				val = val ? val : "#f1f1f1";

				document.querySelector(
					'[data-listen-value="udb_login[bg_color]"]'
				).innerHTML = "body.login {background-color: " + val + ";}";
			});
		});

		handleBgFieldsChange("", "body.login");
		handleBgFieldsChange("form_", ".login form, #loginform");
	};

	function handleBgFieldsChange(keyPrefix, selector) {
		wp.customize("udb_login[" + keyPrefix + "bg_image]", function (setting) {
			var bgImageStyleTag = document.querySelector(
				'[data-listen-value="udb_login[' + keyPrefix + 'bg_image]"]'
			);

			setting.bind(function (val) {
				var formPosition = wp.customize("udb_login[form_position]").get();

				if (keyPrefix === "form_" && formPosition !== "default") {
					selector = "#login";
				}

				writeStyleContent({
					el: bgImageStyleTag,
					selector: selector,
					rules: "background-image: url(" + val + ");",
				});
			});
		});

		wp.customize("udb_login[" + keyPrefix + "bg_repeat]", function (setting) {
			var bgRepeatStyleTag = document.querySelector(
				'[data-listen-value="udb_login[' + keyPrefix + 'bg_repeat]"]'
			);

			setting.bind(function (val) {
				var formPosition = wp.customize("udb_login[form_position]").get();

				if (keyPrefix === "form_" && formPosition !== "default") {
					selector = "#login";
				}

				writeStyleContent({
					el: bgRepeatStyleTag,
					selector: selector,
					rules: "background-repeat: " + val + ";",
				});
			});
		});

		wp.customize("udb_login[" + keyPrefix + "bg_position]", function (setting) {
			setting.bind(function (val) {
				var formPosition = wp.customize("udb_login[form_position]").get();

				if (keyPrefix === "form_" && formPosition !== "default") {
					selector = "#login";
				}

				var rule = "background-position: " + val + ";";

				document.querySelector(
					'[data-listen-value="udb_login[' + keyPrefix + 'bg_position]"]'
				).innerHTML = selector + " {" + rule + "}";
			});
		});

		wp.customize("udb_login[" + keyPrefix + "bg_size]", function (setting) {
			setting.bind(function (val) {
				var formPosition = wp.customize("udb_login[form_position]").get();

				if (keyPrefix === "form_" && formPosition !== "default") {
					selector = "#login";
				}

				var rule = "background-size: " + val + ";";

				document.querySelector(
					'[data-listen-value="udb_login[' + keyPrefix + 'bg_size]"]'
				).innerHTML = selector + " {" + rule + "}";
			});
		});
	}

	events.templateFieldsChange = function () {
		wp.customize("udb_login[template]", function (setting) {
			setting.bind(function (val) {
				if (val !== "default") {
					showProNotice();
				} else {
					hideProNotice();
				}
			});
		});
	};

	events.layoutFieldsChange = function () {
		wp.customize("udb_login[form_position]", function (setting) {
			var formPositionStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_position]"]'
			);

			var formBgColorStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_bg_color]"]'
			);

			var formBgImageStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_bg_image]"]'
			);

			var formBgRepeatStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_bg_repeat]"]'
			);

			var formBgPositionStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_bg_position]"]'
			);

			var formBgSizeStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_bg_size]"]'
			);

			var formWidthStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_width]"]'
			);

			var formHorizontalPaddingStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_horizontal_padding]"]'
			);

			var formBorderWidthStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_border_width]"]'
			);

			var formBorderStyleStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_border_style]"]'
			);

			var formBorderColorStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_border_color]"]'
			);

			setting.bind(function (val) {
				var formBgColor = wp.customize("udb_login[form_bg_color]").get();
				var formBgImage = wp.customize("udb_login[form_bg_image]").get();
				var formBgRepeat = wp.customize("udb_login[form_bg_repeat]").get();
				var formBgPosition = wp.customize("udb_login[form_bg_position]").get();
				var formBgSize = wp.customize("udb_login[form_bg_size]").get();
				var formWidth = wp.customize("udb_login[form_width]").get();

				var formHorizontalPadding = wp
					.customize("udb_login[form_horizontal_padding]")
					.get();

				formWidth = formWidth ? formWidth : "320px";

				formHorizontalPadding = formHorizontalPadding
					? formHorizontalPadding
					: "24px";

				// The non "default" value is handled in the pro version.
				if (val === "default") {
					if (formBgColor) {
						writeStylesContent({
							el: formBgColorStyleTag,
							styles: [
								{
									selector: "#login",
									rules: "background-color: transparent;",
								},
								{
									selector: ".login form, #loginform",
									rules: "background-color: " + formBgColor + ";",
								},
							],
						});
					}

					if (formBgImage) {
						writeStylesContent({
							el: formBgImageStyleTag,
							styles: [
								{
									selector: "#login",
									rules: "background-image: none;",
								},
								{
									selector: ".login form, #loginform",
									rules: "background-image: url(" + formBgImage + ");",
								},
							],
						});
					}

					if (formBgRepeat) {
						writeStyleContent({
							el: formBgRepeatStyleTag,
							selector: ".login form, #loginform",
							rules: "background-repeat: " + formBgRepeat + ";",
						});
					}

					if (formBgPosition) {
						writeStyleContent({
							el: formBgPositionStyleTag,
							selector: ".login form, #loginform",
							rules: "background-position: " + formBgPosition + ";",
						});
					}

					if (formBgSize) {
						writeStyleContent({
							el: formBgSizeStyleTag,
							selector: ".login form, #loginform",
							rules: "background-size: " + formBgSize + ";",
						});
					}

					formWidthStyleTag.innerHTML = formWidthStyleTag.innerHTML.replace(
						"#loginform {max-width:",
						"#login {width:"
					);

					formPositionStyleTag.innerHTML =
						"#login {margin-left: auto; margin-right: auto; width: " +
						formWidth +
						"; min-height: 0; background-color: transparent;} #loginform {min-width: 0; max-width: none;}";

					formHorizontalPaddingStyleTag.innerHTML =
						"#loginform {padding-left: " +
						formHorizontalPadding +
						"; padding-right: " +
						formHorizontalPadding +
						";}";

					var formBorderWidth = wp
						.customize("udb_login[form_border_width]")
						.get();

					formBorderWidth = formBorderWidth ? formBorderWidth : "2px";

					formBorderWidthStyleTag.innerHTML =
						"#loginform {border-width: " + formBorderWidth + ";}";

					var formBorderStyle = wp
						.customize("udb_login[form_border_style]")
						.get();

					formBorderStyle = formBorderStyle ? formBorderStyle : "solid";

					formBorderStyleStyleTag.innerHTML =
						"#loginform {border-style: " + formBorderStyle + ";}";

					var formBorderColor = wp
						.customize("udb_login[form_border_color]")
						.get();

					formBorderColor = formBorderColor ? formBorderColor : "#dddddd";

					formBorderColorStyleTag.innerHTML =
						"#loginform {border-color: " + formBorderColor + ";}";
				}
			});
		});

		wp.customize("udb_login[form_bg_color]", function (setting) {
			var formBgColorStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_bg_color]"]'
			);

			setting.bind(function (val) {
				var formPosition = wp.customize("udb_login[form_position]").get();

				val = val ? val : "#ffffff";
				formPosition = formPosition ? formPosition : "default";

				// The non "default" value is handled in the pro version.
				if (formPosition === "default") {
					writeStylesContent({
						el: formBgColorStyleTag,
						styles: [
							{
								selector: "#login",
								rules: "background-color: transparent;",
							},
							{
								selector: ".login form, #loginform",
								rules: "background-color: " + val + ";",
							},
						],
					});
				}
			});
		});

		wp.customize("udb_login[form_width]", function (setting) {
			setting.bind(function (val) {
				var formPosition = wp.customize("udb_login[form_position]").get();
				var content = "";

				formPosition = formPosition ? formPosition : "default";

				if (formPosition === "default") {
					content = "#login {width: " + val + ";}";
				} else {
					content = "#loginform {max-width: " + val + ";}";
				}

				document.querySelector(
					'[data-listen-value="udb_login[form_width]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[form_top_padding]", function (setting) {
			setting.bind(function (val) {
				var content = "#loginform {padding-top: " + val + ";}";

				document.querySelector(
					'[data-listen-value="udb_login[form_top_padding]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[form_bottom_padding]", function (setting) {
			setting.bind(function (val) {
				var content = "#loginform {padding-bottom: " + val + ";}";

				document.querySelector(
					'[data-listen-value="udb_login[form_bottom_padding]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[form_horizontal_padding]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? "#loginform {padding-left: " +
					  val +
					  "; padding-right: " +
					  val +
					  ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[form_horizontal_padding]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[form_border_width]", function (setting) {
			setting.bind(function (val) {
				var content = val ? "#loginform {border-width: " + val + ";}" : "";

				document.querySelector(
					'[data-listen-value="udb_login[form_border_width]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[form_border_style]", function (setting) {
			setting.bind(function (val) {
				var content = val ? "#loginform {border-style: " + val + ";}" : "";

				document.querySelector(
					'[data-listen-value="udb_login[form_border_style]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[form_border_color]", function (setting) {
			setting.bind(function (val) {
				val = val ? val : "#dddddd";

				document.querySelector(
					'[data-listen-value="udb_login[form_border_color]"]'
				).innerHTML = "#loginform {border-color: " + val + ";}";
			});
		});

		wp.customize("udb_login[form_border_radius]", function (setting) {
			setting.bind(function (val) {
				var content = val ? "#loginform {border-radius: " + val + ";}" : "";

				document.querySelector(
					'[data-listen-value="udb_login[form_border_radius]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[enable_form_shadow]", function (setting) {
			setting.bind(function (val) {
				var shadowBlur;
				var shadowColor;
				var content;

				if (val) {
					shadowBlur = wp.customize("udb_login[form_shadow_blur]").get();
					shadowColor = wp.customize("udb_login[form_shadow_color]").get();
					content = buildBoxShadowCssRule(shadowBlur, shadowColor);
				} else {
					content = "box-shadow: none;";
				}

				content = "#loginform {" + content + "}";
				document.querySelector(
					'[data-listen-value="udb_login[form_shadow]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[form_shadow_blur]", function (setting) {
			setting.bind(function (val) {
				var shadowColor = wp.customize("udb_login[form_shadow_color]").get();
				var content = buildBoxShadowCssRule(val, shadowColor);
				content = "#loginform {" + content + "}";

				document.querySelector(
					'[data-listen-value="udb_login[form_shadow]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[form_shadow_color]", function (setting) {
			setting.bind(function (val) {
				var shadowBlur = wp.customize("udb_login[form_shadow_blur]").get();
				var content = buildBoxShadowCssRule(shadowBlur, val);
				content = "#loginform {" + content + "}";

				document.querySelector(
					'[data-listen-value="udb_login[form_shadow]"]'
				).innerHTML = content;
			});
		});
	};

	events.formFieldsChange = function () {
		wp.customize("udb_login[fields_height]", function (setting) {
			setting.bind(function (val) {
				var valueUnit = val.replace(/\d+/g, "");
				valueUnit = valueUnit ? valueUnit : "px";

				var valueNumber = val.replace(valueUnit, "");
				valueNumber = parseInt(valueNumber.trim(), 10);

				var hidePwTop = valueNumber / 2 - 20;

				var content = val
					? ".login input[type=text], .login input[type=password] {height: " +
					  val +
					  ";} .login .button.wp-hide-pw {margin-top: " +
					  hidePwTop.toString() +
					  valueUnit +
					  ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[fields_height]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[fields_horizontal_padding]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? ".login input[type=text], .login input[type=password] {padding: 0 " +
					  val +
					  ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[fields_horizontal_padding]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[fields_border_width]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? ".login input[type=text], .login input[type=password] {border-width: " +
					  val +
					  ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[fields_border_width]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[fields_border_radius]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? ".login input[type=text], .login input[type=password] {border-radius: " +
					  val +
					  ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[fields_border_radius]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[fields_font_size]", function (setting) {
			setting.bind(function (val) {
				val = val ? val : "24px";

				writeStyleContent({
					el: '[data-listen-value="udb_login[fields_font_size]"]',
					selector: ".login input[type=text], .login input[type=password]",
					rules: "font-size: " + val + ";",
				});
			});
		});

		wp.customize("udb_login[fields_text_color]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? ".login input[type=text], .login input[type=password] {color: " +
					  val +
					  ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[fields_text_color]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[fields_text_color_focus]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? ".login input[type=text]:focus, .login input[type=password]:focus {color: " +
					  val +
					  ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[fields_text_color_focus]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[fields_bg_color]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? ".login input[type=text], .login input[type=password] {background-color: " +
					  val +
					  ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[fields_bg_color]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[fields_bg_color_focus]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? ".login input[type=text]:focus, .login input[type=password]:focus {background-color: " +
					  val +
					  ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[fields_bg_color_focus]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[fields_border_color]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? ".login input[type=text], .login input[type=password] {border-color: " +
					  val +
					  ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[fields_border_color]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[fields_border_color_focus]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? ".login input[type=text]:focus, .login input[type=password]:focus {border-color: " +
					  val +
					  ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[fields_border_color_focus]"]'
				).innerHTML = content;
			});
		});
	};

	events.labelFieldsChange = function () {
		wp.customize("udb_login[labels_color]", function (setting) {
			setting.bind(function (val) {
				val = val ? val : "#444444";

				document.querySelector(
					'[data-listen-value="udb_login[labels_color]"]'
				).innerHTML = "#loginform label {color: " + val + ";}";
			});
		});

		wp.customize("udb_login[labels_font_size]", function (setting) {
			setting.bind(function (val) {
				val = val ? val : "14px";

				document.querySelector(
					'[data-listen-value="udb_login[labels_font_size]"]'
				).innerHTML = "#loginform label {font-size: " + val + ";}";
			});
		});
	};

	events.buttonFieldsChange = function () {
		wp.customize("udb_login[button_height]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? ".wp-core-ui .button.button-large {height: " +
					  val +
					  "; line-height: " +
					  val +
					  ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[button_height]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[button_horizontal_padding]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? ".wp-core-ui .button.button-large {padding: 0 " + val + ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[button_horizontal_padding]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[button_text_color]", function (setting) {
			setting.bind(function (val) {
				val = val ? val : "#ffffff";

				document.querySelector(
					'[data-listen-value="udb_login[button_text_color]"]'
				).innerHTML = ".wp-core-ui .button.button-large {color: " + val + ";}";
			});
		});

		wp.customize("udb_login[button_text_color_hover]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? ".wp-core-ui .button.button-large:hover, .wp-core-ui .button.button-large:focus {color: " +
					  val +
					  ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[button_text_color_hover]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[button_bg_color]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? ".wp-core-ui .button.button-large {background-color: " + val + ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[button_bg_color]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[button_bg_color_hover]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? ".wp-core-ui .button.button-large:hover, .wp-core-ui .button.button-large:focus {background-color: " +
					  val +
					  ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[button_bg_color_hover]"]'
				).innerHTML = content;
			});
		});

		wp.customize("udb_login[button_border_radius]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? ".wp-core-ui .button.button-large {border-radius: " + val + ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[button_border_radius]"]'
				).innerHTML = content;
			});
		});
	};

	events.footerFieldsChange = function () {
		wp.customize("udb_login[footer_link_color]", function (setting) {
			setting.bind(function (val) {
				val = val ? val : "#555d66";

				document.querySelector(
					'[data-listen-value="udb_login[footer_link_color]"]'
				).innerHTML =
					".login #nav a, .login #backtoblog a {color: " + val + ";}";
			});
		});

		wp.customize("udb_login[footer_link_color_hover]", function (setting) {
			setting.bind(function (val) {
				val = val ? val : "#00a0d2";

				document.querySelector(
					'[data-listen-value="udb_login[footer_link_color_hover]"]'
				).innerHTML =
					".login #nav a:hover, .login #nav a:focus, .login #backtoblog a:hover, .login #backtoblog a:focus {color: " +
					val +
					";}";
			});
		});

		wp.customize("udb_login[remove_lang_switcher]", function (setting) {
			setting.bind(function (val) {
				val = val ? "none" : "block";

				document.querySelector(
					'[data-listen-value="udb_login[remove_lang_switcher]"]'
				).innerHTML = "#login .language-switcher {display: " + val + ";}";
			});
		});
	};

	events.customCSSChange = function () {
		wp.customize("udb_login[custom_css]", function (setting) {
			setting.bind(function (val) {
				val = val ? val : "";

				document.querySelector(
					'[data-listen-value="udb_login[custom_css]"]'
				).innerHTML = val;
			});
		});
	};

	function buildBoxShadowCssRule(blur, color) {
		var rule = "box-shadow: none;";
		if (!blur || !color) return rule;

		rule = "box-shadow: 0 0 " + blur + " 0 " + color + ";";

		return rule;
	}

	function writeStyleContent(opts) {
		var el = opts.el;
		var selector = opts.selector;
		var rules = opts.rules;

		if (!el.tagName) {
			el = document.querySelector(el);
		}

		if (!el) return;

		el.innerHTML = selector + " {" + rules + "}";
	}

	function writeStylesContent(opts) {
		var el = opts.el;
		var styles = opts.styles;

		if (!Array.isArray(styles)) return;

		if (!el.tagName) {
			el = document.querySelector(el);
		}

		if (!el) return;

		var output = "";

		styles.forEach(function (style) {
			output += style.selector + " {" + style.rules + "}";
		});

		el.innerHTML = output;
	}

	function showProNotice(autoHide) {
		var notice = document.querySelector(".udb-pro-login-customizer-notice");
		if (!notice) return;

		notice.classList.add("is-shown");

		if (autoHide) setTimeout(hideProNotice, 3000);
	}

	function hideProNotice() {
		var notice = document.querySelector(".udb-pro-login-customizer-notice");
		if (!notice) return;

		notice.classList.remove("is-shown");
	}
})(jQuery, wp.customize);
