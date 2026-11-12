/**
 * Login customizer: Customizer controls.
 *
 * Plain JS, no build step. Globals: jQuery, wp, udbLoginCustomizer.
 */
(function ($) {

	// controls/helpers/insert-pro-link

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

	// controls/setup-controls/login-template-control

	const setupLoginTemplateControl = () => {
		const controls = document.querySelectorAll(
			".udb-customize-control-login-template"
		);

		if (!controls.length) return;

		[].slice.call(controls).forEach(function (control) {
			const controlName = control.dataset.controlName;
			const images = control.querySelectorAll(
				".udb-customize-control-template img"
			);

			if (!images.length) return;

			[].slice.call(images).forEach(function (image) {
				image.addEventListener("click", function () {
					const selected = this;

					[].slice.call(images).forEach(function (img) {
						const parentNode = img.parentNode;

						if (img == selected) {
							parentNode.classList.add("is-selected");
						} else {
							parentNode.classList.remove("is-selected");
						}
					});

					wp.customize(controlName).set(this.dataset.templateName);
				});
			});
		});
	}

	// controls/setup-controls/color-control

	const setupColorControl = () => {
		const controls = document.querySelectorAll(".udb-customize-control-color");
		if (!controls.length) return;

		[].slice.call(controls).forEach(function (control) {
			var clearColor = control.querySelector(".wp-picker-clear");
			if (!clearColor) return;
			clearColor.classList.remove("button-small");
		});
	}

	// controls/setup-controls/range-control

	const setupRangeControl = () => {
		const controls = document.querySelectorAll(".udb-customize-control-range");
		if (!controls.length) return;

		[].slice.call(controls).forEach(function (control) {
			var controlName = control.dataset.controlName;
			var slider = control.querySelector(
				'[data-slider-for="' + controlName + '"]'
			);

			let rawValue = wp.customize(controlName).get() + "";

			let unitValue = rawValue.replace(/\d+/g, "");
			unitValue = unitValue ? unitValue : "%";

			wp.customize(controlName, function (setting) {
				setting.bind(function (val) {
					rawValue = val + "";

					unitValue = rawValue.replace(/\d+/g, "");
					unitValue = unitValue ? unitValue : "%";

					const numberValue = rawValue.replace(unitValue, "").trim();

					slider.value = numberValue;
				});
			});

			slider.addEventListener("input", function (e) {
				const numberValue = this.value;

				wp.customize(controlName).set(numberValue + unitValue);
			});

			control
				.querySelector(".udb-customize-control-reset")
				.addEventListener("click", function (e) {
					wp.customize(controlName).set(this.dataset.resetValue);
				});
		});
	}

	// controls/setup-controls/color-picker-control

	const setupColorPickerControl = () => {
		var colorFields = document.querySelectorAll(
			".udb-customize-color-picker-field"
		);

		if (colorFields.length) {
			[].slice.call(colorFields).forEach(function (el) {
				var valueField = document.getElementById(el.dataset.pickerFor);

				var opts = {
					change: function (event, ui) {
						var rgb = ui.color.toRgb();
						var rgba =
							"rgba(" +
							rgb.r +
							", " +
							rgb.g +
							", " +
							rgb.b +
							", " +
							ui.color._alpha +
							")";

						valueField.value = rgba;
						valueField.dispatchEvent(new Event("change"));
					},
					clear: function () {
						valueField.value = "";
						valueField.dispatchEvent(new Event("change"));
					},
					hide: true,
					palettes: true,
				};

				const $el = jQuery(el);

				$el.wpColorPicker(opts);
			});
		}
	}

	// controls/listen-changes/panels/login-customizer-panel

	/**
	 * Listen to "Login Customizer" panel state.
	 * Change the preview page when it is expanded or collapsed.
	 */
	const listenLoginCustomizerPanelState = () => {
		wp.customize.panel("udb_login_customizer_panel", function (section) {
			section.expanded.bind(function (isExpanded) {
				var currentUrl = wp.customize.previewer.previewUrl();

				if (isExpanded) {
					if (!currentUrl.includes(udbLoginCustomizer.loginPageUrl)) {
						wp.customize.previewer.send(
							"udb-login-customizer-goto-login-page",
							{ expanded: isExpanded }
						);
					}
				} else {
					// Head back to the home page, if we leave the "Login Customizer" panel.
					wp.customize.previewer.send("udb-login-customizer-goto-home-page", {
						url: wp.customize.settings.url.home,
					});
				}
			});
		});
	};

	// controls/helpers/handle-bg-custom-position

	const handleBgCustomPostion = (keyPrefix, position) => {
		if (position == "custom") {
			wp.customize
				.control("udb_login[" + keyPrefix + "bg_custom_position]")
				.activate();
		} else {
			wp.customize
				.control("udb_login[" + keyPrefix + "bg_custom_position]")
				.deactivate();
		}
	};

	// controls/helpers/handle-bg-custom-size

	const handleBgCustomSize = (keyPrefix, size) => {
		if (size == "custom") {
			wp.customize
				.control("udb_login[" + keyPrefix + "bg_custom_size]")
				.activate();
		} else {
			wp.customize
				.control("udb_login[" + keyPrefix + "bg_custom_size]")
				.deactivate();
		}
	};

	// controls/listen-changes/sections/bg-section

	const listenBgSectionState = (opts) => {
		const sectionName = opts.sectionName;
		const keyPrefix = opts.keyPrefix;

		wp.customize.section(sectionName, function (section) {
			section.expanded.bind(function (isExpanded) {
				if (isExpanded) {
					const bgPosition = wp
						.customize("udb_login[" + keyPrefix + "bg_position]")
						.get();

					const bgSize = wp
						.customize("udb_login[" + keyPrefix + "bg_size]")
						.get();

					if (wp.customize("udb_login[" + keyPrefix + "bg_image]").get()) {
						wp.customize
							.control("udb_login[" + keyPrefix + "bg_position]")
							.activate();

						handleBgCustomPostion(keyPrefix, bgPosition);
						handleBgCustomSize(keyPrefix, bgSize);

						wp.customize
							.control("udb_login[" + keyPrefix + "bg_repeat]")
							.activate();

						// The overlay feature is only for bg_image, not for for form_bg_image.
						if (!keyPrefix) {
							wp.customize
								.control("udb_login[enable_bg_overlay_color]")
								.activate();

							if (wp.customize("udb_login[enable_bg_overlay_color]").get()) {
								wp.customize.control("udb_login[bg_overlay_color]").activate();
							} else {
								wp.customize.control("udb_login[bg_overlay_color]").deactivate();
							}
						}
					} else {
						wp.customize
							.control("udb_login[" + keyPrefix + "bg_position]")
							.deactivate();

						wp.customize
							.control("udb_login[" + keyPrefix + "bg_custom_position]")
							.deactivate();

						wp.customize
							.control("udb_login[" + keyPrefix + "bg_size]")
							.deactivate();

						wp.customize
							.control("udb_login[" + keyPrefix + "bg_custom_size]")
							.deactivate();

						wp.customize
							.control("udb_login[" + keyPrefix + "bg_repeat]")
							.deactivate();

						// The overlay feature is only for bg_image, not for for form_bg_image.
						if (!keyPrefix) {
							wp.customize
								.control("udb_login[enable_bg_overlay_color]")
								.deactivate();

							wp.customize.control("udb_login[bg_overlay_color]").deactivate();
						}
					}
				}
			});
		});
	};

	// controls/listen-changes/sections/template-section

	const listenTemplateSectionState = () => {
		wp.customize.section(
			"udb_login_customizer_template_section",
			function (section) {
				section.expanded.bind(function (isExpanded) {
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

	// controls/listen-changes/sections/layout-section

	const listenLayoutSectionState = () => {
		wp.customize.section(
			"udb_login_customizer_layout_section",
			function (section) {
				section.expanded.bind(function (isExpanded) {
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

	// controls/listen-changes/fields/enable-form-shadow

	const listenEnableFormShadowFieldChange = () => {
		wp.customize("udb_login[enable_form_shadow]", function (setting) {
			setting.bind(function (val) {
				if (val) {
					wp.customize.control("udb_login[form_shadow_blur]").activate();
					wp.customize.control("udb_login[form_shadow_color]").activate();
				} else {
					wp.customize.control("udb_login[form_shadow_blur]").deactivate();
					wp.customize.control("udb_login[form_shadow_color]").deactivate();
				}
			});
		});
	};

	// controls/listen-changes/fields/bg-image

	const listenBgImageFieldChange = (opts) => {
		const keyPrefix = opts.keyPrefix;

		wp.customize("udb_login[" + keyPrefix + "bg_image]", function (setting) {
			const bgImageField = document.querySelector(
				'[data-control-name="udb_login[' + keyPrefix + 'bg_image]"]'
			);

			setting.bind(function (val) {
				const bgPosition = wp
					.customize("udb_login[" + keyPrefix + "bg_position]")
					.get();

				const bgSize = wp.customize("udb_login[" + keyPrefix + "bg_size]").get();

				if (val) {
					bgImageField.classList.remove("is-empty");

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_position]")
						.activate();

					handleBgCustomPostion(keyPrefix, bgPosition);

					wp.customize.control("udb_login[" + keyPrefix + "bg_size]").activate();

					handleBgCustomSize(keyPrefix, bgSize);

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_repeat]")
						.activate();

					// The overlay feature is only for bg_image, not for for form_bg_image.
					if (!keyPrefix) {
						wp.customize.control("udb_login[enable_bg_overlay_color]").activate();

						if (wp.customize("udb_login[enable_bg_overlay_color]").get()) {
							wp.customize.control("udb_login[bg_overlay_color]").activate();
						} else {
							wp.customize.control("udb_login[bg_overlay_color]").deactivate();
						}
					}
				} else {
					bgImageField.classList.add("is-empty");

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_position]")
						.deactivate();

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_custom_position]")
						.deactivate();

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_size]")
						.deactivate();

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_custom_size]")
						.deactivate();

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_repeat]")
						.deactivate();

					// The overlay feature is only for bg_image, not for for form_bg_image.
					if (!keyPrefix) {
						wp.customize
							.control("udb_login[enable_bg_overlay_color]")
							.deactivate();

						wp.customize.control("udb_login[bg_overlay_color]").deactivate();
					}
				}
			});
		});
	};

	// controls/listen-changes/fields/enable-bg-overlay

	const listenEnableBgOverlayFieldChange = () => {
		wp.customize("udb_login[enable_bg_overlay_color]", function (setting) {
			setting.bind(function (val) {
				if (val) {
					wp.customize.control("udb_login[bg_overlay_color]").activate();
				} else {
					wp.customize.control("udb_login[bg_overlay_color]").deactivate();
				}
			});
		});
	};

	// controls/listen-changes/fields/bg-position

	const listenBgPositionFieldChange = (opts) => {
		const keyPrefix = opts.keyPrefix;

		wp.customize(
			"udb_login[" + keyPrefix + "bg_position]",
			function (setting) {
				setting.bind(function (val) {
					handleBgCustomPostion(keyPrefix, val);
				});
			}
		);
	};

	// controls/listen-changes/fields/bg-size

	const listenBgSizeFieldChange = (opts) => {
		const keyPrefix = opts.keyPrefix;

		wp.customize("udb_login[" + keyPrefix + "bg_size]", function (setting) {
			setting.bind(function (val) {
				handleBgCustomSize(keyPrefix, val);
			});
		});
	};

	// controls

	/**
	 * Scripts within customizer control panel.
	 *
	 * Used global objects:
	 * - jQuery
	 * - wp
	 * - udbLoginCustomizer
	 */
	(function () {
		wp.customize.bind("ready", function () {
			setupControls();
			listen();
			if (!udbLoginCustomizer.isProActive) insertProLink();
		});

		const setupControls = () => {
			setupColorPickerControl();
			setupRangeControl();
			setupColorControl();
			setupLoginTemplateControl();
		};

		const listen = () => {
			listenPanelsState();
			listenSectionsState();
			listenFieldsChange();
		};

		const listenPanelsState = () => {
			listenLoginCustomizerPanelState();
		};

		const listenSectionsState = () => {
			listenLayoutSectionState();

			listenBgSectionState({
				sectionName: "udb_login_customizer_bg_section",
				keyPrefix: "",
			});

			listenBgSectionState({
				sectionName: "udb_login_customizer_layout_section",
				keyPrefix: "form_",
			});

			if (!udbLoginCustomizer.isProActive) {
				listenTemplateSectionState();
			} else {
				document.body.classList.add("udb-pro-active");
			}
		};

		const listenFieldsChange = () => {
			listenBgImageFieldChange({
				keyPrefix: "",
			});

			listenEnableBgOverlayFieldChange();

			listenBgImageFieldChange({
				keyPrefix: "form_",
			});

			listenBgPositionFieldChange({
				keyPrefix: "",
			});

			listenBgPositionFieldChange({
				keyPrefix: "form_",
			});

			listenBgSizeFieldChange({
				keyPrefix: "",
			});

			listenBgSizeFieldChange({
				keyPrefix: "form_",
			});

			listenEnableFormShadowFieldChange();
		};
	})();
})(jQuery);
