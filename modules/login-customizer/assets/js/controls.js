/**
 * String.prototype.includes polyfill.
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/includes
 */
if (!String.prototype.includes) {
	String.prototype.includes = function (search, start) {
		"use strict";

		if (search instanceof RegExp) {
			throw TypeError("first argument must not be a RegExp");
		}
		if (start === undefined) {
			start = 0;
		}
		return this.indexOf(search, start) !== -1;
	};
}

/**
 * Scripts within customizer control panel.
 *
 * Used global objects:
 * - jQuery
 * - wp
 * - udbLoginCustomizer
 */
(function ($) {
	var events = {};

	wp.customize.bind("ready", function () {
		setupControls();
		listen();
		if (!udbLoginCustomizer.isProActive) insertProLink();
	});

	function setupControls() {
		colorPickerControl();
		rangeControl();
		colorControl();
		loginTemplateControl();
	}

	function listen() {
		events.switchLoginPreview();
		events.bgFieldsChange();
		events.layoutFieldsChange();

		if (!udbLoginCustomizer.isProActive) {
			events.templateFieldsChange();
		} else {
			document.body.classList.add("udb-pro-active");
		}
	}

	function colorPickerControl() {
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

				$(el).wpColorPicker(opts);
			});
		}
	}

	function rangeControl() {
		controls = document.querySelectorAll(".udb-customize-control-range");
		if (!controls.length) return;

		[].slice.call(controls).forEach(function (control) {
			var controlName = control.dataset.controlName;
			var slider = control.querySelector(
				'[data-slider-for="' + controlName + '"]'
			);
			var value = {};

			value.raw = wp.customize(controlName).get() + "";

			value.unit = value.raw.replace(/\d+/g, "");
			value.unit = value.unit ? value.unit : "%";
			value.number = value.raw.replace(value.unit, "");
			value.number = parseInt(value.number.trim(), 10);

			wp.customize(controlName, function (setting) {
				setting.bind(function (val) {
					value.raw = val + "";

					value.unit = value.raw.replace(/\d+/g, "");
					value.unit = value.unit ? value.unit : "%";
					value.number = value.raw.replace(value.unit, "");
					value.number = parseInt(value.number.trim(), 10);

					slider.value = value.number;
				});
			});

			slider.addEventListener("input", function (e) {
				value.number = this.value;

				wp.customize(controlName).set(value.number + value.unit);
			});

			control
				.querySelector(".udb-customize-control-reset")
				.addEventListener("click", function (e) {
					wp.customize(controlName).set(this.dataset.resetValue);
				});
		});
	}

	function colorControl() {
		controls = document.querySelectorAll(".udb-customize-control-color");
		if (!controls.length) return;

		[].slice.call(controls).forEach(function (control) {
			var clearColor = control.querySelector(".wp-picker-clear");
			if (!clearColor) return;
			clearColor.classList.remove("button-small");
		});
	}

	function loginTemplateControl() {
		controls = document.querySelectorAll(
			".udb-customize-control-login-template"
		);
		if (!controls.length) return;

		[].slice.call(controls).forEach(function (control) {
			var controlName = control.dataset.controlName;
			var images = control.querySelectorAll(
				".udb-customize-control-template img"
			);

			if (!images.length) return;

			[].slice.call(images).forEach(function (image) {
				image.addEventListener("click", function (e) {
					var selected = this;

					[].slice.call(images).forEach(function (img) {
						if (img == selected) {
							img.parentNode.classList.add("is-selected");
						} else {
							img.parentNode.classList.remove("is-selected");
						}
					});

					wp.customize(controlName).set(this.dataset.templateName);
				});
			});
		});
	}

	/**
	 * Change the page when the "Login Customizer" panel is expanded (or collapsed).
	 */
	events.switchLoginPreview = function () {
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

	events.bgFieldsChange = function () {
		handleBgFieldsChange("udb_login_customizer_bg_section", "");
		handleBgFieldsChange("udb_login_customizer_layout_section", "form_");
	};

	function handleBgFieldsChange(sectionName, keyPrefix) {
		wp.customize.section(sectionName, function (section) {
			section.expanded.bind(function (isExpanded) {
				if (isExpanded) {
					var bgPosition = wp
						.customize("udb_login[" + keyPrefix + "bg_position]")
						.get();

					if (wp.customize("udb_login[" + keyPrefix + "bg_image]").get()) {
						wp.customize
							.control("udb_login[" + keyPrefix + "bg_position]")
							.activate();

						handleBgCustomPostion(keyPrefix, bgPosition);

						wp.customize
							.control("udb_login[" + keyPrefix + "bg_size]")
							.activate();

						wp.customize
							.control("udb_login[" + keyPrefix + "bg_repeat]")
							.activate();
					} else {
						wp.customize
							.control("udb_login[" + keyPrefix + "bg_position]")
							.deactivate();

						wp.customize
							.control("udb_login[" + keyPrefix + "bg_horizontal_position]")
							.deactivate();

						wp.customize
							.control("udb_login[" + keyPrefix + "bg_vertical_position]")
							.deactivate();

						wp.customize
							.control("udb_login[" + keyPrefix + "bg_size]")
							.deactivate();

						wp.customize
							.control("udb_login[" + keyPrefix + "bg_repeat]")
							.deactivate();
					}
				}
			});
		});

		wp.customize("udb_login[" + keyPrefix + "bg_image]", function (setting) {
			setting.bind(function (val) {
				var bgPosition = wp
					.customize("udb_login[" + keyPrefix + "bg_position]")
					.get();

				if (val) {
					document
						.querySelector(
							'[data-control-name="udb_login[' + keyPrefix + 'bg_image]"]'
						)
						.classList.remove("is-empty");

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_position]")
						.activate();

					handleBgCustomPostion(keyPrefix, bgPosition);

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_size]")
						.activate();

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_repeat]")
						.activate();
				} else {
					document
						.querySelector(
							'[data-control-name="udb_login[' + keyPrefix + 'bg_image]"]'
						)
						.classList.add("is-empty");

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_position]")
						.deactivate();

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_size]")
						.deactivate();

					wp.customize
						.control("udb_login[" + keyPrefix + "bg_repeat]")
						.deactivate();
				}
			});
		});

		wp.customize("udb_login[" + keyPrefix + "bg_position]", function (setting) {
			setting.bind(function (val) {
				handleBgCustomPostion(keyPrefix, val);
			});
		});
	}

	events.templateFieldsChange = function () {
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

	events.layoutFieldsChange = function () {
		wp.customize.section(
			"udb_login_customizer_layout_section",
			function (section) {
				section.expanded.bind(function (isExpanded) {
					var formShadowEnabled = wp
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
								wp.customize
									.control("udb_login[form_shadow_blur]")
									.deactivate();

								wp.customize
									.control("udb_login[form_shadow_color]")
									.deactivate();
							}
						}
					}
				});
			}
		);

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

	function insertProLink() {
		var proLink =
			'\
		<li class="accordion-section control-section udb-pro-control-section">\
			<a href="https://ultimatedashboard.io/docs/login-customizer/?utm_source=plugin&utm_medium=login_customizer_link&utm_campaign=udb" class="accordion-section-title" target="_blank" tabindex="0">\
				PRO Features available! ›\
			</a>\
		</li>\
		';

		$(proLink).insertBefore(
			"#accordion-section-udb_login_customizer_template_section"
		);
	}

	function handleBgCustomPostion(keyPrefix, position) {
		if (position == "custom") {
			wp.customize
				.control("udb_login[" + keyPrefix + "bg_horizontal_position]")
				.activate();

			wp.customize
				.control("udb_login[" + keyPrefix + "bg_vertical_position]")
				.activate();
		} else {
			wp.customize
				.control("udb_login[" + keyPrefix + "bg_horizontal_position]")
				.deactivate();

			wp.customize
				.control("udb_login[" + keyPrefix + "bg_vertical_position]")
				.deactivate();
		}
	}
})(jQuery, wp.customize);
