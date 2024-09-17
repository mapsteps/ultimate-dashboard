(function ($) {
	var page = document.querySelector(".udb-wizard-page");
	var buttonsWrapper = document.querySelector(
		".wizard-heatbox .heatbox-footer"
	);

	var skipButton = document.querySelector(".wizard-heatbox .skip-button");
	var saveButton = document.querySelector(".wizard-heatbox .save-button");
	var skipWizardButton = document.getElementById("skip-setup-wizard");
	var subscribeButton = document.querySelector(
		".wizard-heatbox .subscribe-button"
	);
	var removeAllWidgetsCheckbox = document.querySelector(
		"#udb_widgets__remove-all"
	);
	var skipDiscount = document.querySelector(".udb-skip-discount a");
	var contentAfterSubscribe = document.querySelectorAll(
		"[data-udb-show-on='subscribe']"
	);
	var contentAfterSkipDiscount = document.querySelectorAll(
		"[data-udb-show-on='skip-discount']"
	);
	var discountNotif = document.querySelector(".wizard-heatbox .for-discount");

	var slideIndexes = [
		"modules",
		"widgets",
		"general_settings",
		"custom_login_url",
		"subscription",
		"finished",
	];
	var currentSlide = "modules";
	var doingAjax = false;
	var discountSkipped = false;
	var slider;
	var loginRedirectUnChecked = false;

	function init() {
		if (
			!page ||
			!buttonsWrapper ||
			!skipButton ||
			!saveButton ||
			!discountNotif ||
			!subscribeButton ||
			!skipDiscount
		) {
			return;
		}

		setupSlider();
	}

	function setupSlider() {
		slider = tns({
			container: ".udb-wizard-slides",
			items: 1,
			loop: false,
			autoHeight: true,
			controls: false,
			navPosition: "bottom",
			onInit: onSliderInit,
		});
	}

	function onSliderInit(instance) {
		slider.events.on("indexChanged", onSliderIndexChanged);

		var dotsWrapper = document.querySelector(".wizard-heatbox .udb-dots");

		if (dotsWrapper) {
			dotsWrapper.appendChild(instance.navContainer);

			// Hide the last two dots
			var dots = instance.navContainer.children;
			if (dots.length > 2) {
				dots[dots.length - 1].style.display = "none"; // Hide last dot
				dots[dots.length - 2].style.display = "none"; // Hide second-to-last dot
			}
		}

		skipButton.addEventListener("click", onSkipButtonClick);
		skipWizardButton.addEventListener("click", onSkipWizardButtonClick);
		saveButton.addEventListener("click", onSaveButtonClick);
		subscribeButton.addEventListener("click", onSubscribeButtonClick);
		skipDiscount.addEventListener("click", onSkipDiscountClick);
		removeAllWidgetsCheckbox.addEventListener(
			"change",
			onRemoveAllWidgetsCheckboxClick
		);
	}

	function onSliderIndexChanged(e) {
		currentSlide = slideIndexes[e.index];

		if (currentSlide === "modules") {
			onModulesSlideSelected();
		} else if (currentSlide === "widgets") {
			onWidgetsSlideSelected();
		} else if (currentSlide === "general_settings") {
			onGeneralSettingsSlideSelected();
		} else if (currentSlide === "custom_login_url") {
			onCustomLoginUrlSlideSelected();
		} else if (currentSlide === "subscription") {
			onSubscriptionSlideSelected();
		} else if (currentSlide === "finished") {
			onFinishedSlideSelected();
		}

		// Get the current active index
		var activeIndex = e.index;

		// Mark all dots before the active dot as completed
		markDotsBeforeActive();

		// Optionally, remove the completed class from dots after the current active index
		var allDots = document.querySelectorAll(".tns-nav > button");
		allDots.forEach(function (dot, index) {
			if (index >= activeIndex) {
				dot.classList.remove("completed");
			}
		});

		const skipWizardElement = document.querySelector(".skip-wizard");

		if (currentSlide === "subscription" || currentSlide === "finished") {
			skipWizardElement.classList.add("is-hidden");
		} else {
			skipWizardElement.classList.remove("is-hidden");
		}
	}

	function getDotsBeforeActive() {
		// Get all the dots (slider navigation elements)
		var dots = document.querySelectorAll(".tns-nav > button");

		// Get the index of the currently active dot
		var activeIndex = Array.from(dots).findIndex((dot) =>
			dot.classList.contains("tns-nav-active")
		);

		// Get all dots on the left of the current active dot
		var dotsBeforeActive = Array.from(dots).slice(0, activeIndex);

		return dotsBeforeActive;
	}

	function markDotsBeforeActive() {
		var dotsBeforeActive = getDotsBeforeActive();

		dotsBeforeActive.forEach(function (dot) {
			dot.classList.add("completed");
		});
	}

	function onModulesSlideSelected() {
		discountNotif.classList.add("is-hidden");
		buttonsWrapper.classList.remove("is-hidden");

		saveButton.classList.remove("js-save-widgets");
		saveButton.classList.remove("js-save-general-settings");
		saveButton.classList.remove("js-save-custom-login-url");

		saveButton.textContent = "Next";
	}

	function onWidgetsSlideSelected() {
		saveButton.classList.add("js-save-widgets");
		saveButton.classList.remove("js-save-general-settings");
		saveButton.classList.remove("js-save-custom-login-url");

		discountNotif.classList.add("is-hidden");
		buttonsWrapper.classList.remove("is-hidden");

		saveButton.textContent = "Next";
	}

	function onGeneralSettingsSlideSelected() {
		saveButton.classList.add("js-save-general-settings");
		saveButton.classList.remove("js-save-widgets");
		saveButton.classList.remove("js-save-custom-login-url");

		discountNotif.classList.add("is-hidden");
		buttonsWrapper.classList.remove("is-hidden");

		// Check if the checkbox with name udb_modules[login_redirect] is checked
		// var loginRedirectCheckbox = document.querySelector(
		// 	'.udb-modules-slide input[name="udb_modules[login_redirect]"]'
		// );
		// if (loginRedirectCheckbox && !loginRedirectCheckbox.checked) {
		// 	saveButton.textContent = "Complete Setup";
		// } else {
		// 	saveButton.textContent = "Next";
		// }
	}

	function onCustomLoginUrlSlideSelected() {
		saveButton.classList.add("js-save-custom-login-url");
		saveButton.classList.remove("js-save-widgets");
		saveButton.classList.remove("js-save-general-settings");

		discountNotif.classList.add("is-hidden");
		buttonsWrapper.classList.remove("is-hidden");

		// Change the button text to 'Complete Setup'
		// saveButton.textContent = "Complete Setup";
	}

	function onSubscriptionSlideSelected() {
		discountNotif.classList.remove("is-hidden");
		buttonsWrapper.classList.add("is-hidden");
	}

	function onFinishedSlideSelected() {
		discountNotif.classList.add("is-hidden");
		buttonsWrapper.classList.add("is-hidden");
	}

	function onSkipButtonClick(e) {
		// Define the index for the slide with index 4 (discount screen)
		var slideIndexToGo = 4;
		var loginRedirectCheckbox = document.getElementById(
			"udb_modules__login_redirect"
		);

		var dotsWrapper = document.querySelector(
			".wizard-heatbox .udb-dots .tns-nav"
		);
		var dots = dotsWrapper.children;
		// Check the current slide and handle navigation accordingly
		switch (currentSlide) {
			case "modules":
				if (loginRedirectCheckbox && !loginRedirectCheckbox.checked) {
					loginRedirectUnChecked = true;

					// hide 4th dot
					if (dots.length >= 4) {
						dots[3].classList.add("is-hidden"); // Add 'is-hidden' class to the 4th dot (index 3)
					}
				} else {
					// show 4th dot
					if (dots.length >= 4) {
						dots[3].classList.remove("is-hidden"); // Add 'is-hidden' class to the 4th dot (index 3)
					}
				}

				// Go to next slide.
				slider.goTo("next");
				break;

			case "widgets":
				// Go to next slide.
				slider.goTo("next");
				break;

			case "general_settings":
				if (loginRedirectUnChecked) {
					// If the checkbox is not checked, go to the slide with index 4
					slider.goTo(slideIndexToGo);
					// Change save button text to "Next"
					// saveButton.textContent = "Next";
				} else {
					// Otherwise, just go to the next slide
					slider.goTo("next");
					// Change save button text to "Complete Setup"
					// saveButton.textContent = "Complete Setup";
				}
				break;

			case "custom_login_url":
				// Go to next slide.
				slider.goTo("next");
				break;

			case "subscription":
				// Go to dashboard.
				window.location.href = udbWizard.adminUrl;
				break;

			default:
				break;
		}
	}

	function onSkipWizardButtonClick(e) {
		// Define the index for the slide with index 4 (discount screen)
		var slideIndexToGo = 4;

		slider.goTo(slideIndexToGo);
	}

	function onSaveButtonClick(e) {
		if (doingAjax) return;
		startLoading(saveButton);

		// Get the clicked element
		const target = e.currentTarget;

		// Initialize data object for saving modules by default
		let data = {
			action: "udb_wizard_save_modules",
			nonce: udbWizard.nonces.saveModules,
			modules: getSelectedModules(),
		};

		// Check if the clicked element has specific classes
		if (target.classList.contains("js-save-widgets")) {
			data = {
				action: "udb_wizard_save_widgets",
				nonce: udbWizard.nonces.saveWidgets,
				widgets: getSelectedWidgets(),
			};
		} else if (target.classList.contains("js-save-general-settings")) {
			data = {
				action: "udb_wizard_save_general_settings",
				nonce: udbWizard.nonces.saveGeneralSettings,
				settings: getSelectedGeneralSettings(),
			};
		} else if (target.classList.contains("js-save-custom-login-url")) {
			var customLoginUrlField = document.querySelector("#udb_login_redirect");

			data = {
				action: "udb_wizard_save_custom_login_url",
				nonce: udbWizard.nonces.saveCustomLoginUrl,
				custom_login_url: customLoginUrlField.value,
			};
		}

		$.ajax({
			url: udbWizard.ajaxUrl,
			type: "POST",
			data: data,
		})
			.done(function (r) {
				if (!r.success) return;

				// Check if login_redirect is false or does not exist.
				if (r.data.login_redirect && r.data.login_redirect === "false") {
					loginRedirectUnChecked = true;
				}

				if (currentSlide === "general_settings" && loginRedirectUnChecked) {
					// jump to the slide after custom login url.
					slider.goTo(4);
				} else {
					slider.goTo("next");
				}
			})
			.fail(onAjaxFail)
			.always(function () {
				stopLoading(saveButton);
			});
	}

	function onSubscribeButtonClick(e) {
		var nameField = document.querySelector("#udb-subscription-name");
		var emailField = document.querySelector("#udb-subscription-email");
		if (doingAjax || !nameField || !emailField) return;

		startLoading(subscribeButton);

		$.ajax({
			url: udbWizard.ajaxUrl,
			type: "POST",
			data: {
				action: "udb_wizard_subscribe",
				nonce: udbWizard.nonces.subscribe,
				name: nameField.value,
				email: emailField.value,
				referrer: page.dataset.udbReferrer,
			},
		})
			.done(function (r) {
				if (!r.success) return;

				contentAfterSkipDiscount.forEach(function (content) {
					content.style.display = "none";
				});

				slider.goTo("next");
			})
			.fail(onAjaxFail)
			.always(function () {
				stopLoading(subscribeButton);
			});
	}

	function onSkipDiscountClick(e) {
		e.preventDefault();
		if (doingAjax) return;

		startLoading(skipDiscount.parentNode);

		$.ajax({
			url: udbWizard.ajaxUrl,
			type: "POST",
			data: {
				action: "udb_wizard_skip_discount",
				nonce: udbWizard.nonces.skipDiscount,
				referrer: page.dataset.udbReferrer,
			},
		})
			.done(function (r) {
				if (!r.success) return;
				discountSkipped = true;

				contentAfterSubscribe.forEach(function (content) {
					content.style.display = "none";
				});

				slider.goTo("next");
			})
			.fail(onAjaxFail)
			.always(function () {
				stopLoading(skipDiscount.parentNode);
			});
	}

	function onRemoveAllWidgetsCheckboxClick(e) {
		// Check if the checkbox is checked
		var isChecked = removeAllWidgetsCheckbox.checked;

		// Select all checkboxes below it
		var allCheckboxes = document.querySelectorAll(
			'.widget-toggle input[type="checkbox"]'
		);

		// Iterate over each checkbox
		allCheckboxes.forEach(function (checkbox) {
			// Skip the "Remove all" checkbox itself
			if (checkbox.id !== "udb_widgets__remove-all") {
				checkbox.checked = isChecked;
			}
		});
	}

	function onAjaxFail(jqXHR) {
		var errorMesssage = "Something went wrong";

		if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
			errorMesssage = jqXHR.responseJSON.data;
		}

		alert(errorMesssage);
	}

	function startLoading(button) {
		doingAjax = true;
		button.classList.add("is-loading");
	}

	function stopLoading(button) {
		button.classList.remove("is-loading");
		doingAjax = false;
	}

	function getSelectedModules() {
		var checkboxes = document.querySelectorAll(
			'.udb-modules-slide .module-toggle input[type="checkbox"]'
		);
		if (!checkboxes.length) return [];

		var modules = [];

		[].slice.call(checkboxes).forEach(function (checkbox) {
			var module = checkbox.id.replace("udb_modules__", "");

			if (checkbox.checked) {
				modules.push(module);
			}
		});

		return modules;
	}

	function getSelectedWidgets() {
		var checkboxes = document.querySelectorAll(
			'.udb-widgets-slide .widget-toggle input[type="checkbox"]'
		);

		if (!checkboxes.length) return [];

		var widgets = [];

		[].slice.call(checkboxes).forEach(function (checkbox) {
			var widget = checkbox.id.replace("udb_widgets__", "");

			if (checkbox.checked) {
				widgets.push(widget);
			}
		});

		return widgets;
	}

	function getSelectedGeneralSettings() {
		var checkboxes = document.querySelectorAll(
			'.udb-general-settings-slide .setting-toggle input[type="checkbox"]'
		);
		if (!checkboxes.length) return [];

		var settings = [];

		[].slice.call(checkboxes).forEach(function (checkbox) {
			var setting = checkbox.id.replace("udb_settings__", "");

			if (checkbox.checked) {
				settings.push(setting);
			}
		});

		return settings;
	}

	init();
})(jQuery);
