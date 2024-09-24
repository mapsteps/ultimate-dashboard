(function ($) {
	// Cache DOM elements
	const domElements = {
		page: document.querySelector(".udb-wizard-page"),
		buttonsWrapper: document.querySelector(".wizard-heatbox .heatbox-footer"),
		skipButton: document.querySelector(".wizard-heatbox .skip-button"),
		saveButton: document.querySelector(".wizard-heatbox .save-button"),
		skipWizardButton: document.getElementById("skip-setup-wizard"),
		subscribeButton: document.querySelector(
			".wizard-heatbox .subscribe-button"
		),
		removeAllWidgetsCheckbox: document.querySelector(
			"#udb_widgets__remove-all"
		),
		skipDiscount: document.querySelector(".udb-skip-discount a"),
		contentAfterSubscribe: document.querySelectorAll(
			"[data-udb-show-on='subscribe']"
		),
		contentAfterSkipDiscount: document.querySelectorAll(
			"[data-udb-show-on='skip-discount']"
		),
		discountNotif: document.querySelector(".wizard-heatbox .for-discount"),
		loginRedirectCheckbox: document.getElementById(
			"udb_modules__login_redirect"
		),
		exploreSettingsElement: document.getElementById("explore-settings"),
		dotsWrapper: document.querySelector(".wizard-heatbox .udb-dots"),
	};

	// Slider-related variables
	const slideIndexes = [
		"modules",
		"widgets",
		"general_settings",
		"custom_login_url",
		"subscription",
		"finished",
	];
	let currentSlide = "modules";
	let doingAjax = false;
	let discountSkipped = false;
	let slider;
	let loginRedirectUnChecked = false;

	// Initialization
	function init() {
		if (
			!domElements.page ||
			!domElements.buttonsWrapper ||
			!domElements.skipButton ||
			!domElements.saveButton ||
			!domElements.discountNotif ||
			!domElements.subscribeButton ||
			!domElements.skipDiscount
		) {
			return;
		}

		setupSlider();
		setupEventListeners();
	}

	// Set up the slider
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

	// Slider initialization callback
	function onSliderInit(instance) {
		slider.events.on("indexChanged", onSliderIndexChanged);

		if (domElements.dotsWrapper) {
			domElements.dotsWrapper.appendChild(instance.navContainer);
			hideLastDots(instance);
		}
	}

	// Hide the last two dots in the slider
	function hideLastDots(instance) {
		if (domElements.dotsWrapper) {
			const dots = instance.navContainer.children;
			if (dots.length > 2) {
				dots[dots.length - 1].style.display = "none";
				dots[dots.length - 2].style.display = "none";
			}
		} else {
			console.error("domElements.dotsWrapper is not defined.");
		}
	}

	// Set up event listeners
	function setupEventListeners() {
		domElements.skipButton.addEventListener("click", onSkipButtonClick);
		domElements.skipWizardButton.addEventListener(
			"click",
			onSkipWizardButtonClick
		);
		domElements.saveButton.addEventListener("click", onSaveButtonClick);
		domElements.subscribeButton.addEventListener(
			"click",
			onSubscribeButtonClick
		);
		domElements.skipDiscount.addEventListener("click", onSkipDiscountClick);
		domElements.removeAllWidgetsCheckbox.addEventListener(
			"change",
			onRemoveAllWidgetsCheckboxClick
		);
		domElements.loginRedirectCheckbox.addEventListener(
			"change",
			onLoginRedirectCheckboxClick
		);
	}

	// Event handler for when the slider index changes
	function onSliderIndexChanged(e) {
		currentSlide = slideIndexes[e.index];
		handleSlideChange(currentSlide);
		markDotsBeforeActive(e.index);
		toggleSkipWizardVisibility();
		toggleExploreSettingsVisibility();
	}

	// Handle actions based on the current slide
	function handleSlideChange(slide) {
		switch (slide) {
			case "modules":
				onModulesSlideSelected();
				break;
			case "widgets":
				onWidgetsSlideSelected();
				break;
			case "general_settings":
				onGeneralSettingsSlideSelected();
				break;
			case "custom_login_url":
				onCustomLoginUrlSlideSelected();
				break;
			case "subscription":
				onSubscriptionSlideSelected();
				break;
			case "finished":
				onFinishedSlideSelected();
				break;
		}
	}

	// Toggle the visibility of the "Skip Wizard" button
	function toggleSkipWizardVisibility() {
		const skipWizardElement = document.querySelector(".skip-wizard");
		if (["subscription", "finished"].includes(currentSlide)) {
			skipWizardElement.classList.add("is-hidden");
		} else {
			skipWizardElement.classList.remove("is-hidden");
		}
	}

	// Toggle the visibility of the "Explore Settings" element
	function toggleExploreSettingsVisibility() {
		if (currentSlide === "finished") {
			domElements.exploreSettingsElement.classList.remove("is-hidden");
		}
	}

	// Mark dots before the active one as completed
	function markDotsBeforeActive(activeIndex) {
		const dots = document.querySelectorAll(".tns-nav > button");
		const dotsBeforeActive = Array.from(dots).slice(0, activeIndex);
		dotsBeforeActive.forEach((dot) => dot.classList.add("completed"));
		dots.forEach((dot, index) => {
			if (index >= activeIndex) dot.classList.remove("completed");
		});
	}

	// Slide-specific handlers
	function onModulesSlideSelected() {
		domElements.discountNotif.classList.add("is-hidden");
		domElements.buttonsWrapper.classList.remove("is-hidden");
		updateSaveButton("Next", [
			"js-save-widgets",
			"js-save-general-settings",
			"js-save-custom-login-url",
		]);
	}

	function onWidgetsSlideSelected() {
		updateSaveButton(
			"Next",
			["js-save-general-settings", "js-save-custom-login-url"],
			"js-save-widgets"
		);
	}

	function onGeneralSettingsSlideSelected() {
		updateSaveButton(
			"Next",
			["js-save-widgets", "js-save-custom-login-url"],
			"js-save-general-settings"
		);
	}

	function onCustomLoginUrlSlideSelected() {
		updateSaveButton(
			"Next",
			["js-save-widgets", "js-save-general-settings"],
			"js-save-custom-login-url"
		);
	}

	function onSubscriptionSlideSelected() {
		domElements.discountNotif.classList.remove("is-hidden");
		domElements.buttonsWrapper.classList.add("is-hidden");
	}

	function onFinishedSlideSelected() {
		domElements.discountNotif.classList.add("is-hidden");
		domElements.buttonsWrapper.classList.add("is-hidden");
	}

	// Update the Save button's text and classes
	function updateSaveButton(text, removeClasses, addClass = "") {
		removeClasses.forEach((cls) =>
			domElements.saveButton.classList.remove(cls)
		);
		if (addClass) domElements.saveButton.classList.add(addClass);
		domElements.saveButton.textContent = text;
	}

	// Button click handlers
	function onSkipButtonClick() {
		switch (currentSlide) {
			case "modules":
				handleModulesSkip();
				break;
			case "widgets":
			case "general_settings":
			case "custom_login_url":
				slider.goTo("next");
				break;
			case "subscription":
				window.location.href = udbWizard.adminUrl;
				break;
		}
	}

	// Handle the skip button on the modules slide
	function handleModulesSkip() {
		if (domElements.dotsWrapper) {
			const dots = domElements.dotsWrapper.children;
			if (
				domElements.loginRedirectCheckbox &&
				!domElements.loginRedirectCheckbox.checked
			) {
				loginRedirectUnChecked = true;
				dots[3]?.classList.add("is-hidden");
			} else {
				dots[3]?.classList.remove("is-hidden");
			}
		}

		slider.goTo("next");
	}

	function onSkipWizardButtonClick() {
		slider.goTo(4); // Jump to discount screen
	}

	function onSaveButtonClick() {
		if (doingAjax) return;
		startLoading(domElements.saveButton);
		let data = getSaveData();
		ajaxPost(
			data,
			() => slider.goTo(loginRedirectUnChecked ? 4 : "next"),
			domElements.saveButton
		);
	}

	function onSubscribeButtonClick() {
		if (doingAjax) return;
		startLoading(domElements.subscribeButton);
		const name = document.querySelector("#udb-subscription-name").value;
		const email = document.querySelector("#udb-subscription-email").value;
		const data = {
			action: "udb_onboarding_wizard_subscribe",
			nonce: udbWizard.nonces.subscribe,
			name,
			email,
		};
		ajaxPost(data, onSubscribeComplete, domElements.subscribeButton);
	}

	// Checkbox change handlers
	function onRemoveAllWidgetsCheckboxClick() {
		// Check if the checkbox is checked
		var isChecked = domElements.removeAllWidgetsCheckbox.checked;

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

	function onLoginRedirectCheckboxClick() {
		var dotsWrapper = document.querySelector(
			".wizard-heatbox .udb-dots .tns-nav"
		);
		var dots = dotsWrapper.children;

		if (domElements.loginRedirectCheckbox.checked) {
			// hide 4th dot
			if (dots.length >= 4) {
				dots[3].classList.remove("is-hidden");
			}
		} else {
			// hide 4th dot
			if (dots.length >= 4) {
				dots[3].classList.add("is-hidden");
			}
		}
	}

	// AJAX helpers
	function getSaveData() {
		// Gather the data to be sent in the AJAX request
		const target = domElements.saveButton;
		let data = {
			action: "udb_onboarding_wizard_save_modules",
			nonce: udbWizard.nonces.saveModules,
			modules: getSelectedModules(),
		};

		if (target.classList.contains("js-save-widgets")) {
			data = {
				action: "udb_onboarding_wizard_save_widgets",
				nonce: udbWizard.nonces.saveWidgets,
				widgets: getSelectedWidgets(),
			};
		} else if (target.classList.contains("js-save-general-settings")) {
			data = {
				action: "udb_onboarding_wizard_save_general_settings",
				nonce: udbWizard.nonces.saveGeneralSettings,
				settings: getGeneralSettings(),
			};
		} else if (target.classList.contains("js-save-custom-login-url")) {
			data = {
				action: "udb_onboarding_wizard_save_custom_login_url",
				nonce: udbWizard.nonces.saveCustomLoginUrl,
				loginUrl: getCustomLoginUrl(),
			};
		}

		return data;
	}

	function getSelectedModules() {
		// Gather selected modules data
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
		// Gather selected widgets data
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

	function getGeneralSettings() {
		// Gather general settings data
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

	function getCustomLoginUrl() {
		// Gather custom login URL data
		var customLoginUrlField = document.querySelector("#udb_login_redirect");
		return customLoginUrlField.value;
	}

	function ajaxPost(data, successCallback, button) {
		doingAjax = true;

		$.post(udbWizard.ajaxUrl, data, function (response) {
			doingAjax = false;
			stopLoading(button);

			if (response.success) {
				successCallback();
			} else {
				alert(response.data); // Handle unsuccessful response
			}
		})
			.fail(onAjaxFail)
			.always(function () {
				stopLoading(button);
			});
	}

	function onAjaxFail(jqXHR) {
		var errorMesssage = "Something went wrong";

		if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
			errorMesssage = jqXHR.responseJSON.data;
		}

		alert(errorMesssage);
	}

	// Loading state handling
	function startLoading(button) {
		button.classList.add("is-loading");
	}

	function stopLoading(button) {
		button.classList.remove("is-loading");
	}

	// Onboarding subscription complete
	function onSubscribeComplete() {
		toggleContentVisibility(domElements.contentAfterSubscribe);
		toggleContentVisibility(domElements.discountNotif, false);
	}

	// Toggle content visibility
	function toggleContentVisibility(elements, visible = true) {
		// Check if elements exist
		if (!elements) return;

		// If it's a single element, treat it as an array of one item
		if (NodeList.prototype.isPrototypeOf(elements) || Array.isArray(elements)) {
			elements.forEach((el) => el.classList.toggle("is-hidden", !visible));
		} else if (elements instanceof Element) {
			// If it's a single element, apply the class directly
			elements.classList.toggle("is-hidden", !visible);
		}
	}

	function onSkipDiscountClick(e) {
		e.preventDefault();
		if (doingAjax) return;

		// Start loading on the skip discount button's parent node
		startLoading(domElements.skipDiscount.parentNode);

		const data = {
			action: "udb_onboarding_wizard_skip_discount",
			nonce: udbWizard.nonces.skipDiscount,
			referrer: domElements.page.dataset.udbReferrer,
		};

		ajaxPost(data, onSkipDiscountComplete, domElements.skipDiscount.parentNode);
	}

	// Callback function after the AJAX request completes successfully
	function onSkipDiscountComplete() {
		discountSkipped = true;

		toggleContentVisibility(domElements.contentAfterSubscribe, false);
		toggleContentVisibility(domElements.discountNotif, false);
		toggleContentVisibility(domElements.contentAfterSkipDiscount);

		slider.goTo("next");
	}

	// Initialize the script
	init();
})(jQuery);
