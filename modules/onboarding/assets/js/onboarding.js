/**
 * Onboarding (Setup Wizard & the short "Setup" for users coming from other plugins).
 *
 * The steps come from the page: each slide has data-udb-step (modules, widgets, settings, login_url, subscription, finished).
 * Every step is saved through the one "udb_onboarding" AJAX action.
 */
(function ($) {
	"use strict";

	const config = window.udbOnboarding;
	const page = document.querySelector(".udb-onboarding-wizard-page");

	if (!config || !page || typeof window.tns !== "function") return;

	const box = page.querySelector(".onboarding-wizard-heatbox");
	const slides = Array.from(page.querySelectorAll(".udb-onboarding-wizard-slide"));
	const steps = slides.map((slide) => slide.dataset.udbStep);
	const footer = box.querySelector(".heatbox-footer");
	const saveButton = box.querySelector(".save-button");
	const skipButton = box.querySelector(".skip-button");
	const subscribeButton = box.querySelector(".subscribe-button");
	const skipDiscountLink = box.querySelector(".udb-onboarding-wizard-skip-discount a");
	const discountNotif = box.querySelector(".udb-discount-notif");
	const skipWizard = page.querySelector(".skip-onboarding-wizard");
	const exploreSettings = page.querySelector("#onboarding-wizard-explore-settings");
	const loginRedirectToggle = page.querySelector("#udb_modules__login_redirect");
	const roleSelect = page.querySelector("#remove_by_roles");

	// Steps without a dot of their own.
	const stepsWithoutDot = ["subscription", "finished"];

	let current = 0;
	let busy = false;

	const slider = window.tns({
		container: ".udb-onboarding-wizard-slides",
		items: 1,
		loop: false,
		autoHeight: true,
		controls: false,
		navPosition: "bottom",
		onInit: function (info) {
			const dotsWrapper = box.querySelector(".udb-dots");

			if (dotsWrapper && info.navContainer) {
				dotsWrapper.appendChild(info.navContainer);
			}

			updateUi();
		},
	});

	slider.events.on("indexChanged", function (info) {
		current = info.index;
		updateUi();
	});

	setupRoleSelect();
	updateUi();

	saveButton?.addEventListener("click", onSave);
	skipButton?.addEventListener("click", goNext);
	subscribeButton?.addEventListener("click", onSubscribe);
	skipDiscountLink?.addEventListener("click", onSkipDiscount);
	loginRedirectToggle?.addEventListener("change", updateUi);

	page.querySelector("#skip-setup-onboarding-wizard")?.addEventListener("click", function (e) {
		e.preventDefault();
		slider.goTo(steps.indexOf("subscription"));
	});

	/**
	 * Whether a step is part of this run (the login URL step only when Login Redirect is on).
	 *
	 * @param {string} step The step.
	 * @returns {boolean}
	 */
	function isActive(step) {
		return "login_url" !== step || !loginRedirectToggle || loginRedirectToggle.checked;
	}

	/**
	 * Go to the next active step.
	 */
	function goNext() {
		let next = current + 1;

		while (next < steps.length && !isActive(steps[next])) {
			next++;
		}

		slider.goTo(next);
	}

	/**
	 * Show/hide the parts that depend on the current step.
	 */
	function updateUi() {
		const step = steps[current];
		const atEnd = stepsWithoutDot.includes(step);

		footer?.classList.toggle("is-hidden", atEnd);
		discountNotif?.classList.toggle("is-hidden", "subscription" !== step);
		skipWizard?.classList.toggle("is-hidden", atEnd);

		if ("finished" === step) {
			exploreSettings?.classList.remove("is-hidden");
		}

		const dots = box.querySelectorAll(".udb-dots .tns-nav > button");

		dots.forEach(function (dot, index) {
			dot.classList.toggle("is-hidden", stepsWithoutDot.includes(steps[index]) || !isActive(steps[index]));
			dot.classList.toggle("completed", index < current);
		});
	}

	/**
	 * The values of the checked toggles of a group.
	 *
	 * @param {string} group modules, widgets or settings.
	 * @returns {string[]}
	 */
	function checkedValues(group) {
		return Array.from(page.querySelectorAll('input[data-udb-group="' + group + '"]'))
			.filter((input) => input.checked)
			.map((input) => input.value);
	}

	/**
	 * Save the current step, then go on.
	 */
	function onSave() {
		const step = steps[current];
		const data = { step: step };

		if ("modules" === step) {
			data.modules = checkedValues("modules");
		} else if ("widgets" === step) {
			data.widgets = checkedValues("widgets");
		} else if ("settings" === step) {
			data.settings = checkedValues("settings");
			data.roles = roleSelect ? $(roleSelect).val() || [] : [];
		} else if ("login_url" === step) {
			data.login_url = page.querySelector("#udb_login_redirect")?.value || "";
		} else {
			goNext();
			return;
		}

		send(data, saveButton, goNext);
	}

	/**
	 * Subscribe to the newsletter, then finish.
	 */
	function onSubscribe() {
		send(
			{
				step: "subscribe",
				name: page.querySelector("#udb-subscription-name")?.value || "",
				email: page.querySelector("#udb-subscription-email")?.value || "",
				referrer: config.referrer,
			},
			subscribeButton,
			function () {
				finish("subscribe");
			}
		);
	}

	/**
	 * Skip the discount, then finish.
	 *
	 * @param {MouseEvent} e The click event.
	 */
	function onSkipDiscount(e) {
		e.preventDefault();

		send({ step: "skip_discount", referrer: config.referrer }, skipDiscountLink?.parentElement, function () {
			finish("skip-discount");
		});
	}

	/**
	 * Show the finished step.
	 *
	 * @param {string} how "subscribe" or "skip-discount".
	 */
	function finish(how) {
		page.querySelectorAll("[data-udb-show-on]").forEach(function (el) {
			el.classList.toggle("is-hidden", el.dataset.udbShowOn !== how);
		});

		// The page is done: remove its menu item.
		document.querySelectorAll("#menu-posts-udb_widgets .wp-submenu a").forEach(function (link) {
			if (link.href.includes("page=" + config.pageSlug)) {
				link.closest("li")?.remove();
			}
		});

		slider.goTo(steps.indexOf("finished"));
	}

	/**
	 * Send a step to the server.
	 *
	 * @param {Object} data The data.
	 * @param {Element|null|undefined} button The button that shows the loading state.
	 * @param {Function} onSuccess Called when it worked.
	 */
	function send(data, button, onSuccess) {
		if (busy) return;

		busy = true;
		button?.classList.add("is-loading");

		data.action = "udb_onboarding";
		data.nonce = config.nonce;

		$.post(config.ajaxUrl, data)
			.done(function (response) {
				if (response && response.success) {
					onSuccess();
				} else {
					alert((response && response.data) || config.strings.error);
				}
			})
			.fail(function (xhr) {
				alert((xhr.responseJSON && xhr.responseJSON.data) || config.strings.error);
			})
			.always(function () {
				busy = false;
				button?.classList.remove("is-loading");
			});
	}

	/**
	 * The "Hide Admin Bar for" role picker: "All" or individual roles, not both.
	 */
	function setupRoleSelect() {
		if (!roleSelect || !$.fn.select2) return;

		const $select = $(roleSelect);

		$select.select2({ placeholder: config.strings.selectRole, allowClear: true });

		$select.on("select2:select", function (e) {
			const values = $select.val() || [];
			const next = "all" === e.params.data.id ? ["all"] : values.filter((value) => "all" !== value);

			$select.val(next).trigger("change");
		});
	}
})(jQuery);
