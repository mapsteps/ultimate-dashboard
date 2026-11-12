/**
 * Login customizer: live preview.
 *
 * Plain JS, no build step. Globals: jQuery, wp, udbLoginCustomizer.
 */
(function ($) {

	// preview/helpers/pro-notice

	const proNotice = {
		show: (autoHide) => {
			const notice = document.querySelector(
				".udb-pro-login-customizer-notice"
			);

			if (!notice) return;

			notice.classList.add("is-shown");

			if (autoHide) setTimeout(proNotice.hide, 3000);
		},

		hide: () => {
			var notice = document.querySelector(
				".udb-pro-login-customizer-notice"
			);

			if (!notice) return;

			notice.classList.remove("is-shown");
		},
	};

	// preview/listen-changes/pro-notice-event

	const listenProNoticeEvent = () => {
		wp.customize.preview.bind("pro_notice", function (action) {
			if (action === "show") {
				proNotice.show();
			} else {
				proNotice.hide();
			}
		});
	};

	// preview/listen-changes/switch-preview-event

	const listenPreviewSwitchEvent = () => {
		wp.customize.preview.bind(
			"udb-login-customizer-goto-login-page",
			function (data) {
				// When the section is expanded, open the login customizer page.
				if (data.expanded) {
					wp.customize.preview.send("url", udbLoginCustomizer.loginPageUrl);
				}
			}
		);

		wp.customize.preview.bind(
			"udb-login-customizer-goto-home-page",
			function (data) {
				wp.customize.preview.send("url", data.url);
			}
		);
	};

	// preview/listen-changes/fields/logo/logo-height

	const listenLogoHeightFieldChange = () => {
		wp.customize("udb_login[logo_height]", function (setting) {
			setting.bind(function (val) {
				const el = document.querySelector(
					'[data-listen-value="udb_login[logo_height]"]'
				);

				if (!el) return;

				el.innerHTML =
					".login h1 a, .login .wp-login-logo a {background-size: auto " +
					val +
					";}";
			});
		});
	};

	// preview/listen-changes/fields/logo/logo-image

	const listenLogoImageFieldChange = (opts) => {
		const logoLink = opts.logoLink;

		wp.customize("udb_login[logo_image]", function (setting) {
			setting.bind(function (val) {
				if (val) {
					logoLink.style.backgroundImage = "url(" + val + ")";
				} else {
					logoLink.style.backgroundImage =
						"url(" + udbLoginCustomizer.wpLogoUrl + ")";
				}
			});
		});
	};

	// preview/listen-changes/fields/logo/logo-title

	const listenLogoTitleFieldChange = (opts) => {
		const logoLink = opts.logoLink;

		wp.customize("udb_login[logo_title]", function (setting) {
			setting.bind(function (val) {
				logoLink.innerHTML = val;
			});
		});
	};

	// preview/listen-changes/fields/logo/logo-url

	const listenLogoUrlFieldChange = (opts) => {
		const logoLink = opts.logoLink;

		wp.customize("udb_login[logo_url]", function (setting) {
			setting.bind(function (val) {
				val = val.replace("{home_url}", udbLoginCustomizer.homeUrl);

				logoLink.href = val;
			});
		});
	};

	// preview/listen-changes/logo-fields

	const listenLogoFieldsChange = () => {
		const logoLink = document.querySelector(
			".udb-login--logo-link"
		);

		listenLogoImageFieldChange({
			logoLink: logoLink,
		});

		listenLogoUrlFieldChange({
			logoLink: logoLink,
		});

		listenLogoTitleFieldChange({
			logoLink: logoLink,
		});

		listenLogoHeightFieldChange();
	};

	// preview/css-utilities/write-style

	const writeStyle = (opts) => {
		let el = opts.styleEl;
		const selector = opts.cssSelector;
		const rules = opts.cssRules;

		if (typeof el === 'string') {
			el = document.querySelector(el);
		}

		if (!el) return;

		el.innerHTML = selector + " {" + rules + "}";
	};

	// preview/css-utilities/write-bg-position-style

	const writeBgPositionStyle = (opts) => {
		let el = opts.styleEl;
		const selector = opts.cssSelector;
		const keyPrefix = opts.keyPrefix ? opts.keyPrefix : "";
		const bgPosition = opts.bgPosition;
		let bgCustomPosition = opts.bgCustomPosition ? opts.bgCustomPosition : "";

		if (typeof el === "string") {
			el = document.querySelector(el);
		}

		if (!el) return;

		bgCustomPosition = bgCustomPosition
			? bgCustomPosition
			: wp.customize("udb_login[" + keyPrefix + "bg_custom_position]").get();

		writeStyle({
			styleEl: el,
			cssSelector: selector,
			cssRules:
				"background-position: " +
				(bgPosition === "custom" ? bgCustomPosition : bgPosition) +
				";",
		});
	};

	// preview/helpers/get-form-position

	const getFormPosition = () => {
		let formPosition = wp.customize("udb_login[form_position]").get();
		formPosition = !udbLoginCustomizer.isProActive ? "default" : formPosition;

		return formPosition;
	};

	// preview/helpers/toggle-bg-overlay

	const toggleBgOverlay = (show) => {
		const target = document.querySelector(".udb-bg-overlay");
		if (!target) return;

		if (show) {
			target.style.display = "block";
		} else {
			target.style.display = "none";
		}
	};

	// preview/listen-changes/fields/bg/bg-image

	const listenBgImageFieldChange = (opts) => {
		const keyPrefix = opts.keyPrefix;

		wp.customize("udb_login[" + keyPrefix + "bg_image]", function (setting) {
			let cssSelector = opts.cssSelector;

			const bgImageStyleTag = document.querySelector(
				'[data-listen-value="udb_login[' + keyPrefix + 'bg_image]"]'
			);

			setting.bind(function (val) {
				const formPosition = getFormPosition();

				if (keyPrefix === "form_" && formPosition !== "default") {
					cssSelector = "#login";
				}

				if (val) {
					writeStyle({
						styleEl: bgImageStyleTag,
						cssSelector: cssSelector,
						cssRules: "background-image: url(" + val + ");",
					});

					// The overlay feature is only for bg_image, not for for form_bg_image.
					if (!keyPrefix) {
						const enableBgOverlay = wp
							.customize("udb_login[enable_bg_overlay_color]")
							.get();

						if (enableBgOverlay) {
							toggleBgOverlay(true);
						} else {
							toggleBgOverlay(false);
						}
					}
				} else {
					writeStyle({
						styleEl: bgImageStyleTag,
						cssSelector: cssSelector,
						cssRules: "background-image: none;",
					});

					// The overlay feature is only for bg_image, not for for form_bg_image.
					if (!keyPrefix) {
						toggleBgOverlay(false);
					}
				}

				const bgRepeat = wp
					.customize("udb_login[" + keyPrefix + "bg_repeat]")
					.get();

				writeStyle({
					styleEl: '[data-listen-value="udb_login[' + keyPrefix + 'bg_repeat]"]',
					cssSelector: cssSelector,
					cssRules: "background-repeat: " + bgRepeat + ";",
				});

				const bgPosition = wp
					.customize("udb_login[" + keyPrefix + "bg_position]")
					.get();

				writeBgPositionStyle({
					styleEl:
						'[data-listen-value="udb_login[' + keyPrefix + 'bg_position]"]',
					keyPrefix: keyPrefix,
					cssSelector: cssSelector,
					bgPosition: bgPosition,
				});

				const bgSize = wp.customize("udb_login[" + keyPrefix + "bg_size]").get();

				writeStyle({
					styleEl: '[data-listen-value="udb_login[' + keyPrefix + 'bg_size]"]',
					cssSelector: cssSelector,
					cssRules: "background-size: " + bgSize + ";",
				});
			}); // End of setting.bind();
		}); // End of wp.customize();
	};

	// preview/listen-changes/fields/bg/bg-position

	const listenBgPositionFieldChange = (opts) => {
		const keyPrefix = opts.keyPrefix;

		wp.customize(
			"udb_login[" + keyPrefix + "bg_position]",
			function (setting) {
				let cssSelector = opts.cssSelector;

				setting.bind(function (val) {
					const formPosition = getFormPosition();

					if (keyPrefix === "form_" && formPosition !== "default") {
						cssSelector = "#login";
					}

					writeBgPositionStyle({
						styleEl:
							'[data-listen-value="udb_login[' + keyPrefix + 'bg_position]"]',
						keyPrefix: keyPrefix,
						cssSelector: cssSelector,
						bgPosition: val,
					});
				});
			}
		);
	};

	// preview/listen-changes/fields/bg/bg-custom-position

	const listenBgCustomPositionFieldChange = (opts) => {
		const keyPrefix = opts.keyPrefix;

		wp.customize(
			"udb_login[" + keyPrefix + "bg_custom_position]",
			function (setting) {
				let cssSelector = opts.cssSelector;

				setting.bind(function (val) {
					const formPosition = getFormPosition();

					if (keyPrefix === "form_" && formPosition !== "default") {
						cssSelector = "#login";
					}

					const bgPosition = wp
						.customize("udb_login[" + keyPrefix + "bg_position]")
						.get();

					writeBgPositionStyle({
						styleEl:
							'[data-listen-value="udb_login[' + keyPrefix + 'bg_position]"]',
						keyPrefix: keyPrefix,
						cssSelector: cssSelector,
						bgPosition: bgPosition,
						bgCustomPosition: val,
					});
				});
			}
		);
	};

	// preview/listen-changes/fields/bg/bg-repeat

	const listenBgRepeatFieldChange = (opts) => {
		const keyPrefix = opts.keyPrefix;

		wp.customize(
			"udb_login[" + keyPrefix + "bg_repeat]",
			function (setting) {
				let cssSelector = opts.cssSelector;

				const bgRepeatStyleTag = document.querySelector(
					'[data-listen-value="udb_login[' + keyPrefix + 'bg_repeat]"]'
				);

				setting.bind(function (val) {
					const formPosition = getFormPosition();

					if (keyPrefix === "form_" && formPosition !== "default") {
						cssSelector = "#login";
					}

					writeStyle({
						styleEl: bgRepeatStyleTag,
						cssSelector: cssSelector,
						cssRules: "background-repeat: " + val + ";",
					});
				});
			}
		);
	};

	// preview/css-utilities/write-bg-size-style

	const writeBgSizeStyle = (opts) => {
		let el = opts.styleEl;
		const selector = opts.cssSelector;
		const keyPrefix = opts.keyPrefix ? opts.keyPrefix : "";
		const bgSize = opts.bgSize;
		let bgCustomSize = opts.bgCustomSize ? opts.bgCustomSize : "";

		if (typeof el === "string") {
			el = document.querySelector(el);
		}

		if (!el) return;

		bgCustomSize = bgCustomSize
			? bgCustomSize
			: wp.customize("udb_login[" + keyPrefix + "bg_custom_size]").get();

		writeStyle({
			styleEl: el,
			cssSelector: selector,
			cssRules:
				"background-size: " +
				(bgSize === "custom" ? bgCustomSize : bgSize) +
				";",
		});
	};

	// preview/listen-changes/fields/bg/bg-size

	const listenBgSizeFieldChange = (opts) => {
		const keyPrefix = opts.keyPrefix;

		wp.customize("udb_login[" + keyPrefix + "bg_size]", function (setting) {
			let cssSelector = opts.cssSelector;

			setting.bind(function (val) {
				const formPosition = getFormPosition();

				if (keyPrefix === "form_" && formPosition !== "default") {
					cssSelector = "#login";
				}

				writeBgSizeStyle({
					styleEl: '[data-listen-value="udb_login[' + keyPrefix + 'bg_size]"]',
					keyPrefix: keyPrefix,
					cssSelector: cssSelector,
					bgSize: val,
				});
			});
		});
	};

	// preview/listen-changes/fields/bg/bg-custom-size

	const listenBgCustomSizeFieldChange = (opts) => {
		const keyPrefix = opts.keyPrefix;

		wp.customize(
			"udb_login[" + keyPrefix + "bg_custom_size]",
			function (setting) {
				let cssSelector = opts.cssSelector;

				setting.bind(function (val) {
					const formPosition = getFormPosition();

					if (keyPrefix === "form_" && formPosition !== "default") {
						cssSelector = "#login";
					}

					const bgSize = wp
						.customize("udb_login[" + keyPrefix + "bg_size]")
						.get();

					writeBgSizeStyle({
						styleEl: '[data-listen-value="udb_login[' + keyPrefix + 'bg_size]"]',
						keyPrefix: keyPrefix,
						cssSelector: cssSelector,
						bgSize: bgSize,
						bgCustomSize: val,
					});
				});
			}
		);
	};

	// preview/listen-changes/fields/bg/bg-overlay-color

	const listenBgOverlayColorFieldChange = () => {
		wp.customize("udb_login[bg_overlay_color]", function (setting) {
			const bgOverlayColorStyleTag = document.querySelector(
				'[data-listen-value="udb_login[bg_overlay_color]"]'
			);

			setting.bind(function (val) {
				let rule;

				if (val) {
					rule = "background-color: " + val + ";";
					bgOverlayColorStyleTag.innerHTML = ".udb-bg-overlay {" + rule + "}";
				} else {
					rule = "background-color: transparent;";
					bgOverlayColorStyleTag.innerHTML = ".udb-bg-overlay {" + rule + "}";
				}
			});
		});
	};

	// preview/listen-changes/fields/bg/enable-bg-overlay-color

	const listenEnableBgOverlayColorFieldChange = () => {
		wp.customize("udb_login[enable_bg_overlay_color]", function (setting) {
			setting.bind(function (val) {
				toggleBgOverlay(val);
			});
		});
	};

	// preview/listen-changes/bg-fields

	const listenBgFieldsChange = (opts) => {
		const keyPrefix = opts.keyPrefix;

		listenBgImageFieldChange({
			keyPrefix: keyPrefix,
			cssSelector: opts.cssSelector,
		});

		listenBgRepeatFieldChange({
			keyPrefix: keyPrefix,
			cssSelector: opts.cssSelector,
		});

		listenBgPositionFieldChange({
			keyPrefix: keyPrefix,
			cssSelector: opts.cssSelector,
		});

		listenBgCustomPositionFieldChange({
			keyPrefix: keyPrefix,
			cssSelector: opts.cssSelector,
		});

		listenBgSizeFieldChange({
			keyPrefix: keyPrefix,
			cssSelector: opts.cssSelector,
		});

		listenBgCustomSizeFieldChange({
			keyPrefix: keyPrefix,
			cssSelector: opts.cssSelector,
		});

		// The overlay feature is only for bg_image, not for for form_bg_image.
		if (!keyPrefix) {
			listenEnableBgOverlayColorFieldChange();
			listenBgOverlayColorFieldChange();
		}
	};

	// preview/css-utilities/write-styles

	const writeStyles = (opts) => {
		let el = opts.styleEl;
		const styles = opts.styles;

		if (!Array.isArray(styles)) return;

		if (typeof el === 'string') {
			el = document.querySelector(el);
		}

		if (!el) return;

		let output = "";

		styles.forEach(function (style) {
			output += style.cssSelector + " {" + style.cssRules + "}";
		});

		el.innerHTML = output;
	};

	// preview/listen-changes/fields/form-layout/form-position

	const listenFormPositionFieldChange = () => {
		wp.customize("udb_login[form_position]", function (setting) {
			const formPositionStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_position]"]'
			);

			const formBgColorStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_bg_color]"]'
			);

			const formBgImageStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_bg_image]"]'
			);

			const formBgRepeatStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_bg_repeat]"]'
			);

			const formBgPositionStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_bg_position]"]'
			);

			const formBgSizeStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_bg_size]"]'
			);

			const formWidthStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_width]"]'
			);

			const formHorizontalPaddingStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_horizontal_padding]"]'
			);

			const formBorderWidthStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_border_width]"]'
			);

			const formBorderStyleStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_border_style]"]'
			);

			const formBorderColorStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_border_color]"]'
			);

			setting.bind(function (val) {
				const formBgColor = wp.customize("udb_login[form_bg_color]").get();
				const formBgImage = wp.customize("udb_login[form_bg_image]").get();
				const formBgRepeat = wp.customize("udb_login[form_bg_repeat]").get();
				const formBgPosition = wp.customize("udb_login[form_bg_position]").get();
				const formBgSize = wp.customize("udb_login[form_bg_size]").get();
				let formWidth = wp.customize("udb_login[form_width]").get();

				let formHorizontalPadding = wp
					.customize("udb_login[form_horizontal_padding]")
					.get();

				formWidth = formWidth ? formWidth : "320px";

				formHorizontalPadding = formHorizontalPadding
					? formHorizontalPadding
					: "24px";

				// The non "default" value is handled in the pro version.
				if (val === "default") {
					if (formBgColor) {
						writeStyles({
							styleEl: formBgColorStyleTag,
							styles: [
								{
									cssSelector: "#login",
									cssRules: "background-color: transparent;",
								},
								{
									cssSelector: ".login form, #loginform",
									cssRules: "background-color: " + formBgColor + ";",
								},
							],
						});
					}

					if (formBgImage) {
						writeStyles({
							styleEl: formBgImageStyleTag,
							styles: [
								{
									cssSelector: "#login",
									cssRules: "background-image: none;",
								},
								{
									cssSelector: ".login form, #loginform",
									cssRules: "background-image: url(" + formBgImage + ");",
								},
							],
						});
					}

					if (formBgRepeat) {
						writeStyle({
							styleEl: formBgRepeatStyleTag,
							cssSelector: ".login form, #loginform",
							cssRules: "background-repeat: " + formBgRepeat + ";",
						});
					}

					if (formBgPosition) {
						writeBgPositionStyle({
							styleEl: formBgPositionStyleTag,
							keyPrefix: "form_",
							cssSelector: ".login form, #loginform",
							bgPosition: formBgPosition,
						});
					}

					if (formBgSize) {
						writeBgSizeStyle({
							styleEl: formBgSizeStyleTag,
							keyPrefix: "form_",
							cssSelector: ".login form, #loginform",
							bgSize: formBgSize,
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

					let formBorderWidth = wp
						.customize("udb_login[form_border_width]")
						.get();

					formBorderWidth = formBorderWidth ? formBorderWidth : "2px";

					formBorderWidthStyleTag.innerHTML =
						"#loginform {border-width: " + formBorderWidth + ";}";

					let formBorderStyle = wp
						.customize("udb_login[form_border_style]")
						.get();

					formBorderStyle = formBorderStyle ? formBorderStyle : "solid";

					formBorderStyleStyleTag.innerHTML =
						"#loginform {border-style: " + formBorderStyle + ";}";

					let formBorderColor = wp
						.customize("udb_login[form_border_color]")
						.get();

					formBorderColor = formBorderColor ? formBorderColor : "#dddddd";

					formBorderColorStyleTag.innerHTML =
						"#loginform {border-color: " + formBorderColor + ";}";
				}
			});
		});
	};

	// preview/listen-changes/fields/form-layout/form-bg-color

	const listenFormBgColorFieldChange = () => {
		wp.customize("udb_login[form_bg_color]", function (setting) {
			const formBgColorStyleTag = document.querySelector(
				'[data-listen-value="udb_login[form_bg_color]"]'
			);

			setting.bind(function (val) {
				let formPosition = wp.customize("udb_login[form_position]").get();

				formPosition = !udbLoginCustomizer.isProActive ? "default" : formPosition;

				val = val ? val : "#ffffff";
				formPosition = formPosition ? formPosition : "default";

				// The non "default" value is handled in the pro version.
				if (formPosition === "default") {
					writeStyles({
						styleEl: formBgColorStyleTag,
						styles: [
							{
								cssSelector: "#login",
								cssRules: "background-color: transparent;",
							},
							{
								cssSelector: ".login form, #loginform",
								cssRules: "background-color: " + val + ";",
							},
						],
					});
				}
			});
		});
	};

	// preview/listen-changes/fields/form-layout/form-width

	const listenFormWidthFieldChange = () => {
		wp.customize("udb_login[form_width]", function (setting) {
			setting.bind(function (val) {
				var formPosition = wp.customize("udb_login[form_position]").get();

				formPosition = !udbLoginCustomizer.isProActive ? "default" : formPosition;

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
	};

	// preview/listen-changes/fields/form-layout/form-top-padding

	const listenFormTopPaddingFieldChange = () => {
		wp.customize("udb_login[form_top_padding]", function (setting) {
			setting.bind(function (val) {
				var content = "#loginform {padding-top: " + val + ";}";

				document.querySelector(
					'[data-listen-value="udb_login[form_top_padding]"]'
				).innerHTML = content;
			});
		});
	};

	// preview/listen-changes/fields/form-layout/form-bottom-padding

	const listenFormBottomPaddingFieldChange = () => {
		wp.customize("udb_login[form_bottom_padding]", function (setting) {
			setting.bind(function (val) {
				var content = "#loginform {padding-bottom: " + val + ";}";

				document.querySelector(
					'[data-listen-value="udb_login[form_bottom_padding]"]'
				).innerHTML = content;
			});
		});
	};

	// preview/listen-changes/fields/form-layout/form-horizontal-padding

	const listenFormHorizontalPaddingFieldChange = () => {
		wp.customize("udb_login[form_horizontal_padding]", function (setting) {
			setting.bind(function (val) {
				var content = val
					? "#loginform {padding-left: " + val + "; padding-right: " + val + ";}"
					: "";

				document.querySelector(
					'[data-listen-value="udb_login[form_horizontal_padding]"]'
				).innerHTML = content;
			});
		});
	};

	// preview/listen-changes/fields/form-layout/form-border-width

	const listenFormBorderWidthFieldChange = () => {
		wp.customize("udb_login[form_border_width]", function (setting) {
			setting.bind(function (val) {
				var content = val ? "#loginform {border-width: " + val + ";}" : "";

				document.querySelector(
					'[data-listen-value="udb_login[form_border_width]"]'
				).innerHTML = content;
			});
		});
	};

	// preview/listen-changes/fields/form-layout/form-border-style

	const listenFormBorderStyleFieldChange = () => {
		wp.customize("udb_login[form_border_style]", function (setting) {
			setting.bind(function (val) {
				var content = val ? "#loginform {border-style: " + val + ";}" : "";

				document.querySelector(
					'[data-listen-value="udb_login[form_border_style]"]'
				).innerHTML = content;
			});
		});
	};

	// preview/listen-changes/fields/form-layout/form-border-color

	const listenFormBorderColorFieldChange = () => {
		wp.customize("udb_login[form_border_color]", function (setting) {
			setting.bind(function (val) {
				val = val ? val : "#dddddd";

				document.querySelector(
					'[data-listen-value="udb_login[form_border_color]"]'
				).innerHTML = "#loginform {border-color: " + val + ";}";
			});
		});
	};

	// preview/listen-changes/fields/form-layout/form-border-radius

	const listenFormBorderRadiusFieldChange = () => {
		wp.customize("udb_login[form_border_radius]", function (setting) {
			setting.bind(function (val) {
				var content = val ? "#loginform {border-radius: " + val + ";}" : "";

				document.querySelector(
					'[data-listen-value="udb_login[form_border_radius]"]'
				).innerHTML = content;
			});
		});
	};

	// preview/css-utilities/build-box-shadow-css-rule

	const buildBoxShadowCssRule = (blur, color) => {
		let rule = "box-shadow: none;";
		if (!blur || !color) return rule;

		rule = "box-shadow: 0 0 " + blur + " 0 " + color + ";";

		return rule;
	};

	// preview/listen-changes/fields/form-layout/enable-form-shadow

	const listenEnableFormShadowFieldChange = () => {
		wp.customize("udb_login[enable_form_shadow]", function (setting) {
			setting.bind(function (val) {
				let shadowBlur;
				let shadowColor;
				let content;

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
	};

	// preview/listen-changes/fields/form-layout/form-shadow-blur

	const listenFormShadowBlurFieldChange = () => {
		wp.customize("udb_login[form_shadow_blur]", function (setting) {
			setting.bind(function (val) {
				const shadowColor = wp.customize("udb_login[form_shadow_color]").get();
				let content = buildBoxShadowCssRule(val, shadowColor);
				content = "#loginform {" + content + "}";

				document.querySelector(
					'[data-listen-value="udb_login[form_shadow]"]'
				).innerHTML = content;
			});
		});
	};

	// preview/listen-changes/fields/form-layout/form-shadow-color

	const listenFormShadowColorFieldChange = () => {
		wp.customize("udb_login[form_shadow_color]", function (setting) {
			setting.bind(function (val) {
				const shadowBlur = wp.customize("udb_login[form_shadow_blur]").get();
				let content = buildBoxShadowCssRule(shadowBlur, val);
				content = "#loginform {" + content + "}";

				document.querySelector(
					'[data-listen-value="udb_login[form_shadow]"]'
				).innerHTML = content;
			});
		});
	};

	// preview/listen-changes/form-layout-fields

	const listenFormLayoutFieldsChange = () => {
		listenFormPositionFieldChange();
		listenFormBgColorFieldChange();
		listenFormWidthFieldChange();
		listenFormTopPaddingFieldChange();
		listenFormBottomPaddingFieldChange();
		listenFormHorizontalPaddingFieldChange();
		listenFormBorderWidthFieldChange();
		listenFormBorderStyleFieldChange();
		listenFormBorderColorFieldChange();
		listenFormBorderRadiusFieldChange();
		listenEnableFormShadowFieldChange();
		listenFormShadowBlurFieldChange();
		listenFormShadowColorFieldChange();
	};

	// preview/listen-changes/fields/form-fields/fields-height

	const listenFieldsHeightFieldChange = () => {
		wp.customize("udb_login[fields_height]", function (setting) {
			setting.bind(function (val) {
				let valueUnit = val.replace(/\d+/g, "");
				valueUnit = valueUnit ? valueUnit : "px";

				let valueNumber = parseInt(val.replace(valueUnit, "").trim(), 10);

				const hidePwTop = valueNumber / 2 - 20;

				const content = val
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
	};

	// preview/listen-changes/fields/form-fields/fields-horizontal-padding

	const listenFieldsHorizontalPaddingFieldChange = () => {
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
	};

	// preview/listen-changes/fields/form-fields/fields-border-width

	const listenFieldsBorderWidthFieldChange = () => {
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
	};

	// preview/listen-changes/fields/form-fields/fields-border-radius

	const listenFieldsBorderRadiusFieldChange = () => {
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
	};

	// preview/listen-changes/fields/form-fields/fields-font-size

	const listenFieldsFontSizeFieldChange = () => {
		wp.customize("udb_login[fields_font_size]", function (setting) {
			setting.bind(function (val) {
				val = val ? val : "24px";

				writeStyle({
					styleEl: '[data-listen-value="udb_login[fields_font_size]"]',
					cssSelector: ".login input[type=text], .login input[type=password]",
					cssRules: "font-size: " + val + ";",
				});
			});
		});
	};

	// preview/listen-changes/fields/form-fields/fields-text-color

	const listenFieldsTextColorFieldChange = () => {
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
	};

	// preview/listen-changes/fields/form-fields/fields-text-color-focus

	const listenFieldsTextColorFocusFieldChange = () => {
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
	};

	// preview/listen-changes/fields/form-fields/fields-bg-color

	const listenFieldsBgColorFieldChange = () => {
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
	};

	// preview/listen-changes/fields/form-fields/fields-bg-color-focus

	const listenFieldsBgColorFocusFieldChange = () => {
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
	};

	// preview/listen-changes/fields/form-fields/fields-border-color

	const listenFieldsBorderColorFieldChange = () => {
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
	};

	// preview/listen-changes/fields/form-fields/fields-border-color-focus

	const listenFieldsBorderColorFocusFieldChange = () => {
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

	// preview/listen-changes/form-fields

	const listenFormFieldsChange = () => {
		listenFieldsHeightFieldChange();
		listenFieldsHorizontalPaddingFieldChange();
		listenFieldsBorderWidthFieldChange();
		listenFieldsBorderRadiusFieldChange();
		listenFieldsFontSizeFieldChange();
		listenFieldsTextColorFieldChange();
		listenFieldsTextColorFocusFieldChange();
		listenFieldsBgColorFieldChange();
		listenFieldsBgColorFocusFieldChange();
		listenFieldsBorderColorFieldChange();
		listenFieldsBorderColorFocusFieldChange();
	};

	// preview/listen-changes/fields/label/label-color

	const listenLabelColorFieldChange = () => {
		wp.customize("udb_login[labels_color]", function (setting) {
			setting.bind(function (val) {
				val = val ? val : "#444444";

				document.querySelector(
					'[data-listen-value="udb_login[labels_color]"]'
				).innerHTML = "#loginform label {color: " + val + ";}";
			});
		});
	};

	// preview/listen-changes/fields/label/label-font-size

	const listenLabelFontSizeFieldChange = () => {
		wp.customize("udb_login[labels_font_size]", function (setting) {
			setting.bind(function (val) {
				val = val ? val : "14px";

				document.querySelector(
					'[data-listen-value="udb_login[labels_font_size]"]'
				).innerHTML = "#loginform label {font-size: " + val + ";}";
			});
		});
	};

	// preview/listen-changes/label-fields

	const listenLabelFieldsChange = () => {
		listenLabelColorFieldChange();
		listenLabelFontSizeFieldChange();
	};

	// preview/listen-changes/fields/button/button-bg-color

	const listenButtonBgColorFieldChange = () => {
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
	};

	// preview/listen-changes/fields/button/button-bg-color-hover

	const listenButtonBgColorHoverFieldChange = () => {
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
	};

	// preview/listen-changes/fields/button/button-border-radius

	const listenButtonBorderRadiusFieldChange = () => {
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

	// preview/listen-changes/fields/button/button-height

	const listenButtonHeightFieldChange = () => {
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
	};

	// preview/listen-changes/fields/button/button-horizontal-padding

	const listenButtonHorizontalPaddingFieldChange = () => {
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
	};

	// preview/listen-changes/fields/button/button-text-color

	const listenButtonTextColorFieldChange = () => {
		wp.customize("udb_login[button_text_color]", function (setting) {
			setting.bind(function (val) {
				val = val ? val : "#ffffff";

				document.querySelector(
					'[data-listen-value="udb_login[button_text_color]"]'
				).innerHTML = ".wp-core-ui .button.button-large {color: " + val + ";}";
			});
		});
	};

	// preview/listen-changes/fields/button/button-text-color-hover

	const listenButtonTextColorHoverFieldChange = () => {
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
	};

	// preview/listen-changes/button-fields

	const listenButtonFieldsChange = () => {
		listenButtonHeightFieldChange();
		listenButtonHorizontalPaddingFieldChange();
		listenButtonTextColorFieldChange();
		listenButtonTextColorHoverFieldChange();
		listenButtonBgColorFieldChange();
		listenButtonBgColorHoverFieldChange();
		listenButtonBorderRadiusFieldChange();
	};

	// preview/listen-changes/fields/footer/footer-link-color

	const listenFooterLinkColorFieldChange = () => {
		wp.customize("udb_login[footer_link_color]", function (setting) {
			setting.bind(function (val) {
				val = val ? val : "#555d66";

				document.querySelector(
					'[data-listen-value="udb_login[footer_link_color]"]'
				).innerHTML = ".login #nav a, .login #backtoblog a {color: " + val + ";}";
			});
		});
	};

	// preview/listen-changes/fields/footer/footer-link-color-hover

	const listenFooterLinkColorHoverFieldChange = () => {
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
	};

	// preview/listen-changes/fields/footer/remove-back-to-site-link

	const listenBackToSiteToggle = () => {
		wp.customize("udb_login[remove_back_to_site_link]", function (setting) {
			setting.bind(function (val) {
				const display = val ? "none" : "block";

				document.querySelector(
					'[data-listen-value="udb_login[remove_back_to_site_link]"]'
				).innerHTML = ".login #backtoblog {display: " + display + ";}";
			});
		});
	};

	// preview/listen-changes/fields/footer/remove-lang-switcher

	const listenLangSwitcherToggle = () => {
		wp.customize("udb_login[remove_lang_switcher]", function (setting) {
			setting.bind(function (val) {
				const display = val ? "none" : "block";

				document.querySelector(
					'[data-listen-value="udb_login[remove_lang_switcher]"]'
				).innerHTML = "#login .language-switcher {display: " + display + ";}";
			});
		});
	};

	// preview/listen-changes/fields/footer/remove-register-lost-pw-link

	const listenRegisterAndLostPwLinksToggle = () => {
		wp.customize(
			"udb_login[remove_register_lost_pw_link]",
			function (setting) {
				setting.bind(function (val) {
					const display = val ? "none" : "block";

					document.querySelector(
						'[data-listen-value="udb_login[remove_register_lost_pw_link]"]'
					).innerHTML = ".login #nav {display: " + display + ";}";
				});
			}
		);
	};

	// preview/listen-changes/footer-fields

	const listenFooterFieldsChange = () => {
		listenFooterLinkColorFieldChange();
		listenFooterLinkColorHoverFieldChange();

		listenRegisterAndLostPwLinksToggle();
		listenBackToSiteToggle();
		listenLangSwitcherToggle();
	};

	// preview/listen-changes/fields/custom-css

	const listenCustomCssFieldChange = () => {
		wp.customize("udb_login[custom_css]", function (setting) {
			setting.bind(function (val) {
				val = val ? val : "";

				document.querySelector(
					'[data-listen-value="udb_login[custom_css]"]'
				).innerHTML = val;
			});
		});
	};

	// preview/listen-changes/fields/template

	const listenTemplateFieldChange = () => {
		wp.customize("udb_login[template]", function (setting) {
			setting.bind(function (val) {
				if (val !== "default") {
					proNotice.show();
				} else {
					proNotice.hide();
				}
			});
		});
	};

	// preview/listen-changes/fields/bg/bg-color

	const listenBgColorFieldChange = () => {
		wp.customize("udb_login[bg_color]", function (setting) {
			const bgColorStyleTag = document.querySelector(
				'[data-listen-value="udb_login[bg_color]"]'
			);

			setting.bind(function (val) {
				val = val ? val : "#f1f1f1";

				bgColorStyleTag.innerHTML = "body.login {background-color: " + val + ";}";
			});
		});
	};

	// preview

	/**
	 * Scripts within customizer preview window.
	 *
	 * Used global objects:
	 * - jQuery
	 * - wp
	 * - udbLoginCustomizer
	 */
	(function ($) {
		wp.customize.bind("preview-ready", function () {
			listen();
		});

		const listen = () => {
			listenCustomEvents();
			listenFieldsChanges();
		};

		const listenCustomEvents = () => {
			listenPreviewSwitchEvent();
			if (!udbLoginCustomizer.isProActive) listenProNoticeEvent();
		};

		const listenFieldsChanges = () => {
			if (!udbLoginCustomizer.isProActive) listenTemplateFieldChange();

			listenLogoFieldsChange();
			listenBgColorFieldChange();

			listenBgFieldsChange({
				keyPrefix: "",
				cssSelector: "body.login",
			});

			listenBgFieldsChange({
				keyPrefix: "form_",
				cssSelector: ".login form, #loginform",
			});

			listenFormLayoutFieldsChange();
			listenFormFieldsChange();
			listenLabelFieldsChange();
			listenButtonFieldsChange();
			listenFooterFieldsChange();
			listenCustomCssFieldChange();
		};
	})(jQuery);
})(jQuery);
