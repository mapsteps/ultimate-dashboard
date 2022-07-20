(function ($) {
	var buttonsWrapper = document.querySelector(
		".onboarding-heatbox .heatbox-footer"
	);
	var skipButton = document.querySelector(".onboarding-heatbox .skip-button");
	var saveButton = document.querySelector(".onboarding-heatbox .save-button");
	var subscribeButton = document.querySelector(
		".onboarding-heatbox .subscribe-button"
	);
	var skipDiscount = document.querySelector(".udb-skip-discount a");
	var contentAfterSubscribe = document.querySelectorAll(
		"[data-udb-show-on='subscribe']"
	);
	var contentAfterSkipDiscount = document.querySelectorAll(
		"[data-udb-show-on='skip-discount']"
	);
	var discountNotif = document.querySelector(
		".onboarding-heatbox .udb-discount-notif"
	);
	var slideIndexes = ["modules", "subscription", "finished"];
	var currentSlide = "modules";
	var doingAjax = false;
	var discountSkipped = false;
	var slider;

	function init() {
		if (
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
			container: ".udb-onboarding-slides",
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

		var dotsWrapper = document.querySelector(".onboarding-heatbox .udb-dots");

		if (dotsWrapper) {
			dotsWrapper.appendChild(instance.navContainer);
		}

		skipButton.addEventListener("click", onSkipButtonClick);
		saveButton.addEventListener("click", onSaveButtonClick);
		subscribeButton.addEventListener("click", onSubscribeButtonClick);
		skipDiscount.addEventListener("click", onSkipDiscountClick);
	}

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
		discountNotif.classList.add("is-hidden");
		buttonsWrapper.classList.remove("is-hidden");
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
		switch (currentSlide) {
			case "modules":
				// Go to next slide.
				slider.goTo("next");
				break;

			case "subscription":
				// Go to dashboard.
				window.location.href = udbPluginOnboarding.adminUrl;
				break;

			default:
				break;
		}
	}

	function onSaveButtonClick(e) {
		if (doingAjax) return;
		startLoading(saveButton);

		$.ajax({
			url: udbPluginOnboarding.ajaxUrl,
			type: "POST",
			data: {
				action: "udb_plugin_onboarding_save_modules",
				nonce: udbPluginOnboarding.nonces.saveModules,
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

	function onSubscribeButtonClick(e) {
		var nameField = document.querySelector("#udb-subscription-name");
		var emailField = document.querySelector("#udb-subscription-email");
		if (doingAjax || !nameField || !emailField) return;

		startLoading(subscribeButton);

		$.ajax({
			url: udbPluginOnboarding.ajaxUrl,
			type: "POST",
			data: {
				action: "udb_plugin_onboarding_subscribe",
				nonce: udbPluginOnboarding.nonces.subscribe,
				name: nameField.value,
				email: emailField.value,
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
			url: udbPluginOnboarding.ajaxUrl,
			type: "POST",
			data: {
				action: "udb_plugin_onboarding_skip_discount",
				nonce: udbPluginOnboarding.nonces.skipDiscount,
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

	init();
})(jQuery);
