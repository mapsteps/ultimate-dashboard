import {
	findHtmlEl,
	findHtmlEls,
	findInputEl,
	findInputEls,
} from "../../base/assets/js/dom-utils";

(function ($) {
	const page = findHtmlEl(".udb-onboarding-page");
	const buttonsWrapper = findHtmlEl(".onboarding-heatbox .heatbox-footer");
	const skipButton = findHtmlEl(".onboarding-heatbox .skip-button");
	const saveButton = findHtmlEl(".onboarding-heatbox .save-button");
	const subscribeButton = findHtmlEl(".onboarding-heatbox .subscribe-button");
	const skipDiscount = findHtmlEl(".udb-skip-discount a");
	const contentAfterSubscribe = findHtmlEls("[data-udb-show-on='subscribe']");
	const contentAfterSkipDiscount = findHtmlEls(
		"[data-udb-show-on='skip-discount']"
	);
	const discountNotif = findHtmlEl(".onboarding-heatbox .udb-discount-notif");
	const slideIndexes = ["modules", "subscription", "finished"];

	let currentSlide = "modules";
	let doingAjax = false;

	const udbPluginOnboarding = window.udbPluginOnboarding;

	/**
	 * @type {import("tiny-slider").TinySliderInstance} slider - The slider instance.
	 */
	let slider;

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
		slider = window.tns({
			container: ".udb-onboarding-slides",
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

		const dotsWrapper = document.querySelector(".onboarding-heatbox .udb-dots");

		if (dotsWrapper && instance.navContainer) {
			dotsWrapper.appendChild(instance.navContainer);
		}

		skipButton?.addEventListener("click", onSkipButtonClick);
		saveButton?.addEventListener("click", onSaveButtonClick);
		subscribeButton?.addEventListener("click", onSubscribeButtonClick);
		skipDiscount?.addEventListener("click", onSkipDiscountClick);
	}

	/**
	 * Event handler for when the slider index changes.
	 *
	 * @param {import("tiny-slider").TinySliderInfo} e The event object.
	 */
	function onSliderIndexChanged(e) {
		currentSlide = slideIndexes[e.index];

		if (currentSlide === "modules") {
			onModulesSlideSelected();
		} else if (currentSlide === "subscription") {
			onSubscriptionSlideSelected();
		} else if (currentSlide === "finished") {
			onFinishedSlideSelected();
		}
	}

	function onModulesSlideSelected() {
		discountNotif?.classList.add("is-hidden");
		buttonsWrapper?.classList.remove("is-hidden");
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
	 * Function to handle the skip button click event.
	 *
	 * @param {MouseEvent} e The event object.
	 */
	function onSkipButtonClick(e) {
		switch (currentSlide) {
			case "modules":
				// Go to next slide.
				slider.goTo("next");
				break;

			case "subscription":
				// Go to dashboard.
				window.location.href = udbPluginOnboarding?.adminUrl ?? "";
				break;

			default:
				break;
		}
	}

	/**
	 * Function to handle the save button click event.
	 *
	 * @param {MouseEvent} e The event object.
	 */
	function onSaveButtonClick(e) {
		if (doingAjax) return;
		startLoading(saveButton);

		$.ajax({
			url: udbPluginOnboarding?.ajaxUrl,
			type: "POST",
			data: {
				action: "udb_plugin_onboarding_save_modules",
				nonce: udbPluginOnboarding?.nonces.saveModules,
				modules: getSelectedModules(),
			},
		})
			.done(function (r) {
				if (!r.success) return;
				slider.goTo("next");
			})
			.fail(onAjaxFail)
			.always(function () {
				stopLoading(saveButton);
			});
	}

	/**
	 * Function to handle the subscribe button click event.
	 *
	 * @param {MouseEvent} e The event object.
	 */
	function onSubscribeButtonClick(e) {
		const nameField = findInputEl("#udb-subscription-name");
		const emailField = findInputEl("#udb-subscription-email");
		if (doingAjax || !nameField || !emailField) return;

		startLoading(subscribeButton);

		$.ajax({
			url: udbPluginOnboarding?.ajaxUrl,
			type: "POST",
			data: {
				action: "udb_plugin_onboarding_subscribe",
				nonce: udbPluginOnboarding?.nonces.subscribe,
				name: nameField.value,
				email: emailField.value,
				referrer: page?.dataset.udbReferrer ?? "",
			},
		})
			.done(function (r) {
				if (!r.success) return;

				contentAfterSkipDiscount.forEach(function (content) {
					content.style.display = "none";
				});

				hideSetupSubmenuItem();
				slider.goTo("next");
			})
			.fail(onAjaxFail)
			.always(function () {
				stopLoading(subscribeButton);
			});
	}

	/**
	 * Function to handle the skip discount button click event.
	 *
	 * @param {MouseEvent} e The event object.
	 */
	function onSkipDiscountClick(e) {
		e.preventDefault();
		if (doingAjax) return;

		startLoading(skipDiscount?.parentElement);

		$.ajax({
			url: udbPluginOnboarding?.ajaxUrl,
			type: "POST",
			data: {
				action: "udb_plugin_onboarding_skip_discount",
				nonce: udbPluginOnboarding?.nonces.skipDiscount,
				referrer: page?.dataset.udbReferrer,
			},
		})
			.done(function (r) {
				if (!r.success) return;

				contentAfterSubscribe.forEach(function (content) {
					content.style.display = "none";
				});

				hideSetupSubmenuItem();
				slider.goTo("next");
			})
			.fail(onAjaxFail)
			.always(function () {
				stopLoading(skipDiscount?.parentElement);
			});
	}

	/**
	 * Function to handle ajax request failure.
	 *
	 * @param {JQueryXHR} jqXHR The ajax request object.
	 */
	function onAjaxFail(jqXHR) {
		let errorMesssage = "Something went wrong";

		if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
			errorMesssage = jqXHR.responseJSON.data;
		}

		alert(errorMesssage);
	}

	/**
	 * Function to start loading.
	 *
	 * @param {HTMLElement|null|undefined} button The button element.
	 */
	function startLoading(button) {
		doingAjax = true;
		button?.classList.add("is-loading");
	}

	/**
	 * Function to stop loading.
	 *
	 * @param {HTMLElement|null|undefined} button The button element.
	 */
	function stopLoading(button) {
		button?.classList.remove("is-loading");
		doingAjax = false;
	}

	function getSelectedModules() {
		const checkboxes = findInputEls(
			'.udb-modules-slide .module-toggle input[type="checkbox"]'
		);
		if (!checkboxes.length) return [];

		/**
		 * @type {string[]} modules - The selected modules.
		 */
		const modules = [];

		checkboxes.forEach(function (checkbox) {
			const module = checkbox.id.replace("udb_modules__", "");

			if (checkbox.checked) {
				modules.push(module);
			}
		});

		return modules;
	}

	/**
	 * Hide the "Setup" submenu item.
	 */
	function hideSetupSubmenuItem() {
		const submenuItems = findHtmlEls(
			"#menu-posts-udb_widgets .wp-submenu > li > a"
		);
		if (!submenuItems.length) return;

		for (let i = 0; i < submenuItems.length; i++) {
			const link = submenuItems[i];
			if (!(link instanceof HTMLAnchorElement)) continue;

			if (link.href.includes("page=udb_plugin_onboarding")) {
				link.parentElement?.remove();
				break;
			}
		}
	}

	init();
})(jQuery);
