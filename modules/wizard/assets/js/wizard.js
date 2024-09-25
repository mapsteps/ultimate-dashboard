(function ($) {
	/**
	 * Find an HTML element by selector.
	 *
	 * @param {string} selector The selector.
	 * @returns {HTMLElement | null} The element or null if not found.
	 */
	function findHtmlEl(selector) {
		const el = document.querySelector(selector);

		if (el instanceof HTMLElement) {
			return el;
		}

		return null;
	}

	/**
	 * Find HTML elements by selector.
	 *
	 * @param {string} selector The selector.
	 * @returns {HTMLElement[]} The HTML elements.
	 */
	function findHtmlEls(selector) {
		const nodes = document.querySelectorAll(selector);
		const els = [];

		for (let i = 0; i < nodes.length; i++) {
			const el = nodes[i];

			if (el instanceof HTMLElement) {
				els.push(el);
			}
		}

		return els;
	}

	/**
	 * Find an HTML input element by selector.
	 *
	 * @param {string} selector The selector.
	 * @returns {HTMLInputElement | null} The element or null if not found.
	 */
	function findInputEl(selector) {
		const el = document.querySelector(selector);

		if (el instanceof HTMLInputElement) {
			return el;
		}

		return null;
	}

	/**
	 * Find HTML input elements by selector.
	 *
	 * @param {string} selector The selector.
	 * @returns {HTMLInputElement[]} The HTML input elements.
	 */
	function findInputEls(selector) {
		const nodes = document.querySelectorAll(selector);
		const els = [];

		for (let i = 0; i < nodes.length; i++) {
			const el = nodes[i];

			if (el instanceof HTMLInputElement) {
				els.push(el);
			}
		}

		return els;
	}

	// Cache DOM elements.
	const page = findHtmlEl(".udb-wizard-page");
	const buttonsWrapper = findHtmlEl(".wizard-heatbox .heatbox-footer");
	const skipButton = findHtmlEl(".wizard-heatbox .skip-button");
	const saveButton = findHtmlEl(".wizard-heatbox .save-button");
	const skipWizardButton = findHtmlEl("#skip-setup-wizard");
	const subscribeButton = findHtmlEl(".wizard-heatbox .subscribe-button");
	const removeAllWidgetsCheckbox = findInputEl("#udb_widgets__remove-all");
	const skipDiscount = findHtmlEl(".udb-skip-discount a");
	const contentAfterSubscribe = findHtmlEls("[data-udb-show-on='subscribe']");
	const contentAfterSkipDiscount = findHtmlEls(
		"[data-udb-show-on='skip-discount']"
	);
	const discountNotif = findHtmlEl(".wizard-heatbox .for-discount");
	const loginRedirectCheckbox = findInputEl("#udb_modules__login_redirect");
	const exploreSettingsElement = findHtmlEl("#explore-settings");
	const dotsWrapper = findHtmlEl(".wizard-heatbox .udb-dots");

	const udbWizard = window.udbWizard;

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

	/**
	 * @type {import("tiny-slider").TinySliderInstance} slider - The slider instance.
	 */
	let slider;

	let loginRedirectUnChecked = false;

	// Initialization
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
		setupEventListeners();
	}

	// Set up the slider
	function setupSlider() {
		slider = window.tns({
			container: ".udb-wizard-slides",
			items: 1,
			loop: false,
			autoHeight: true,
			controls: false,
			navPosition: "bottom",
			onInit: onSliderInit,
		});
	}

	/**
	 * Slider initialization callback
	 *
	 * @param {import("tiny-slider").TinySliderInfo} instance The slider instance.
	 */
	function onSliderInit(instance) {
		slider.events.on("indexChanged", onSliderIndexChanged);

		if (dotsWrapper && instance.navContainer) {
			dotsWrapper.appendChild(instance.navContainer);
			hideLastDots(instance);
		}
	}

	/**
	 * Hide the last two dots in the slider.
	 *
	 * @param {import("tiny-slider").TinySliderInfo} instance The slider instance.
	 */
	function hideLastDots(instance) {
		if (dotsWrapper && instance.navContainer) {
			const dots = instance.navContainer.children;
			if (dots.length > 2) {
				const lastDot = dots[dots.length - 1];
				const secondLastDot = dots[dots.length - 2];

				if (lastDot instanceof HTMLElement) {
					lastDot.style.display = "none";
				}

				if (secondLastDot instanceof HTMLElement) {
					secondLastDot.style.display = "none";
				}
			}
		} else {
			console.error("dotsWrapper is not defined.");
		}
	}

	// Set up event listeners
	function setupEventListeners() {
		skipButton?.addEventListener("click", onSkipButtonClick);
		skipWizardButton?.addEventListener("click", onSkipWizardButtonClick);
		saveButton?.addEventListener("click", onSaveButtonClick);
		subscribeButton?.addEventListener("click", onSubscribeButtonClick);
		skipDiscount?.addEventListener("click", onSkipDiscountClick);
		removeAllWidgetsCheckbox?.addEventListener(
			"change",
			onRemoveAllWidgetsCheckboxClick
		);
		loginRedirectCheckbox?.addEventListener(
			"change",
			onLoginRedirectCheckboxClick
		);
	}

	/**
	 * Event handler for when the slider index changes.
	 *
	 * @param {import("tiny-slider").TinySliderInfo} e The event object.
	 */
	function onSliderIndexChanged(e) {
		currentSlide = slideIndexes[e.index];
		handleSlideChange(currentSlide);
		markDotsBeforeActive(e.index);
		toggleSkipWizardVisibility();
		toggleExploreSettingsVisibility();
	}

	/**
	 * Handle actions based on the current slide.
	 *
	 * @param {string} slide The current slide.
	 */
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
		const skipWizardElement = findHtmlEl(".skip-wizard");
		if (["subscription", "finished"].includes(currentSlide)) {
			skipWizardElement?.classList.add("is-hidden");
		} else {
			skipWizardElement?.classList.remove("is-hidden");
		}
	}

	// Toggle the visibility of the "Explore Settings" element
	function toggleExploreSettingsVisibility() {
		if (currentSlide === "finished") {
			exploreSettingsElement?.classList.remove("is-hidden");
		}
	}

	/**
	 * Mark dots before the active one as completed.
	 *
	 * @param {number} activeIndex The active index.
	 */
	function markDotsBeforeActive(activeIndex) {
		const dots = findHtmlEls(".tns-nav > button");
		const dotsBeforeActive = Array.from(dots).slice(0, activeIndex);
		dotsBeforeActive.forEach((dot) => dot.classList.add("completed"));
		dots.forEach((dot, index) => {
			if (index >= activeIndex) dot.classList.remove("completed");
		});
	}

	// Slide-specific handlers
	function onModulesSlideSelected() {
		discountNotif?.classList.add("is-hidden");
		buttonsWrapper?.classList.remove("is-hidden");
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
		discountNotif?.classList.remove("is-hidden");
		buttonsWrapper?.classList.add("is-hidden");
	}

	function onFinishedSlideSelected() {
		discountNotif?.classList.add("is-hidden");
		buttonsWrapper?.classList.add("is-hidden");
	}

	/**
	 * Update the Save button's text and classes.
	 *
	 * @param {string} text The text.
	 * @param {string[]} removeClasses The classes to remove.
	 * @param {string} addClass The class to add.
	 */
	function updateSaveButton(text, removeClasses, addClass = "") {
		if (!saveButton) return;
		removeClasses.forEach((cls) => saveButton.classList.remove(cls));
		if (addClass) saveButton.classList.add(addClass);
		saveButton.textContent = text;
	}

	/**
	 * Skip button click handlers
	 */
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
				window.location.href = udbWizard?.adminUrl ?? "";
				break;
		}
	}

	// Handle the skip button on the modules slide
	function handleModulesSkip() {
		if (dotsWrapper) {
			const dots = dotsWrapper.children;
			if (loginRedirectCheckbox && !loginRedirectCheckbox.checked) {
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
		startLoading(saveButton);
		let data = getSaveData();
		ajaxPost(
			data,
			() => slider.goTo(loginRedirectUnChecked ? 4 : "next"),
			saveButton
		);
	}

	function onSubscribeButtonClick() {
		if (doingAjax) return;
		startLoading(subscribeButton);
		const name = findInputEl("#udb-subscription-name")?.value ?? "";
		const email = findInputEl("#udb-subscription-email")?.value ?? "";
		const data = {
			action: "udb_onboarding_wizard_subscribe",
			nonce: udbWizard?.nonces.subscribe,
			name,
			email,
		};
		ajaxPost(data, onSubscribeComplete, subscribeButton);
	}

	// Checkbox change handlers
	function onRemoveAllWidgetsCheckboxClick() {
		// Check if the checkbox is checked
		const isChecked = removeAllWidgetsCheckbox?.checked ?? false;

		// Select all checkboxes below it
		var allCheckboxes = findInputEls('.widget-toggle input[type="checkbox"]');

		// Iterate over each checkbox
		allCheckboxes.forEach(function (checkbox) {
			// Skip the "Remove all" checkbox itself
			if (checkbox.id !== "udb_widgets__remove-all") {
				checkbox.checked = isChecked;
			}
		});
	}

	function onLoginRedirectCheckboxClick() {
		const dotsWrapper = findHtmlEl(".wizard-heatbox .udb-dots .tns-nav");
		if (!dotsWrapper) return;

		const dots = dotsWrapper.children;

		if (loginRedirectCheckbox?.checked) {
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
		const target = saveButton;

		/** @type {Object} data - The data to be sent in the AJAX request. */
		let data = {
			action: "udb_onboarding_wizard_save_modules",
			nonce: udbWizard?.nonces.saveModules,
			modules: getSelectedModules(),
		};

		if (target?.classList.contains("js-save-widgets")) {
			data = {
				action: "udb_onboarding_wizard_save_widgets",
				nonce: udbWizard?.nonces.saveWidgets,
				widgets: getSelectedWidgets(),
			};
		} else if (target?.classList.contains("js-save-general-settings")) {
			data = {
				action: "udb_onboarding_wizard_save_general_settings",
				nonce: udbWizard?.nonces.saveGeneralSettings,
				settings: getGeneralSettings(),
			};
		} else if (target?.classList.contains("js-save-custom-login-url")) {
			data = {
				action: "udb_onboarding_wizard_save_custom_login_url",
				nonce: udbWizard?.nonces.saveCustomLoginUrl,
				loginUrl: getCustomLoginUrl(),
			};
		}

		return data;
	}

	function getSelectedModules() {
		// Gather selected modules data
		const checkboxes = findInputEls(
			'.udb-modules-slide .module-toggle input[type="checkbox"]'
		);
		if (!checkboxes.length) return [];

		/**
		 * @type {string[]} modules - The selected modules.
		 */
		const modules = [];

		checkboxes.forEach(function (checkbox) {
			var module = checkbox.id.replace("udb_modules__", "");

			if (checkbox.checked) {
				modules.push(module);
			}
		});

		return modules;
	}

	function getSelectedWidgets() {
		// Gather selected widgets data
		const checkboxes = findInputEls(
			'.udb-widgets-slide .widget-toggle input[type="checkbox"]'
		);
		if (!checkboxes.length) return [];

		/**
		 * @type {string[]} widgets - The selected widgets.
		 */
		const widgets = [];

		checkboxes.forEach(function (checkbox) {
			var widget = checkbox.id.replace("udb_widgets__", "");

			if (checkbox.checked) {
				widgets.push(widget);
			}
		});

		return widgets;
	}

	function getGeneralSettings() {
		// Gather general settings data
		var checkboxes = findInputEls(
			'.udb-general-settings-slide .setting-toggle input[type="checkbox"]'
		);
		if (!checkboxes.length) return [];

		/**
		 * @type {string[]} settings - The selected general settings.
		 */
		const settings = [];

		checkboxes.forEach(function (checkbox) {
			var setting = checkbox.id.replace("udb_settings__", "");

			if (checkbox.checked) {
				settings.push(setting);
			}
		});

		return settings;
	}

	function getCustomLoginUrl() {
		// Gather custom login URL data
		const customLoginUrlField = findInputEl("#udb_login_redirect");
		return customLoginUrlField?.value ?? "";
	}

	/**
	 * AJAX post.
	 *
	 * @param {Object} data The data to be sent.
	 * @param {Function} successCallback The success callback function.
	 * @param {HTMLElement|null|undefined} button The button element.
	 */
	function ajaxPost(data, successCallback, button) {
		doingAjax = true;

		$.post(udbWizard?.ajaxUrl ?? "", data, function (response) {
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

	/**
	 * Handle AJAX failure.
	 *
	 * @param {JQueryXHR} jqXHR The jQuery XHR object.
	 */
	function onAjaxFail(jqXHR) {
		var errorMesssage = "Something went wrong";

		if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
			errorMesssage = jqXHR.responseJSON.data;
		}

		alert(errorMesssage);
	}

	/**
	 * Start loading.
	 *
	 * @param {HTMLElement|null|undefined} button The button element.
	 */
	function startLoading(button) {
		button?.classList.add("is-loading");
	}

	/**
	 * Stop loading.
	 *
	 * @param {HTMLElement|null|undefined} button The button element.
	 */
	function stopLoading(button) {
		button?.classList.remove("is-loading");
	}

	/**
	 * Onboarding subscription complete.
	 */
	function onSubscribeComplete() {
		toggleContentVisibility(contentAfterSubscribe);
		toggleContentVisibility(discountNotif, false);
	}

	/**
	 * Toggle content visibility.
	 *
	 * @param {HTMLElement[]|HTMLElement|null} elements The elements to toggle.
	 * @param {boolean} visible The visibility state.
	 */
	function toggleContentVisibility(elements, visible = true) {
		// Check if elements exist
		if (!elements) return;

		if (Array.isArray(elements)) {
			elements.forEach((el) => el.classList.toggle("is-hidden", !visible));
		} else {
			// If it's a single element, apply the class directly
			elements.classList.toggle("is-hidden", !visible);
		}
	}

	/**
	 * Skip discount click handler.
	 *
	 * @param {MouseEvent} e The event object.
	 */
	function onSkipDiscountClick(e) {
		e.preventDefault();
		if (doingAjax) return;

		// Start loading on the skip discount button's parent node
		startLoading(skipDiscount?.parentElement);

		const data = {
			action: "udb_onboarding_wizard_skip_discount",
			nonce: udbWizard?.nonces.skipDiscount,
			referrer: page?.dataset.udbReferrer,
		};

		ajaxPost(data, onSkipDiscountComplete, skipDiscount?.parentElement);
	}

	/**
	 * Callback function after the AJAX request completes successfully.
	 */
	function onSkipDiscountComplete() {
		toggleContentVisibility(contentAfterSubscribe, false);
		toggleContentVisibility(discountNotif, false);
		toggleContentVisibility(contentAfterSkipDiscount);

		slider.goTo("next");
	}

	// Initialize the script
	init();
})(jQuery);
