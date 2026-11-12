/**
 * Onboarding (Setup Wizard & the short "Setup" for users coming from other plugins).
 *
 * Each step is a <section data-udb-step> (modules, widgets, settings, login_url, subscription, finished);
 * one is shown at a time. Every step is saved through the one "udb_onboarding" AJAX action.
 */
(function ($) {
	"use strict";

	const config = window.udbOnboarding;
	const page = document.querySelector(".udb-onboarding");

	if (!config || !page) return;

	const sections = Array.from(page.querySelectorAll(".udb-onboarding-step"));
	const steps = sections.map((section) => section.dataset.udbStep);
	const footer = page.querySelector(".udb-onboarding-footer");
	const progress = page.querySelector(".udb-onboarding-progress");
	const nextButton = page.querySelector(".udb-onboarding-next");
	const subscribeButton = page.querySelector(".udb-onboarding-subscribe");
	const skipAll = page.querySelector(".udb-onboarding-skip-all");
	const loginRedirectToggle = page.querySelector("#udb_modules__login_redirect");
	const roleBoxes = page.querySelectorAll('input[data-udb-group="roles"]');

	// The steps with settings; they get the footer and count as "Step x of y".
	const settingSteps = ["modules", "widgets", "settings", "login_url"];

	let current = 0;
	let busy = false;

	updateUi();

	nextButton.addEventListener("click", onNext);
	subscribeButton.addEventListener("click", onSubscribe);
	page.querySelector(".udb-onboarding-skip").addEventListener("click", goNext);
	page.querySelector(".udb-onboarding-skip-discount").addEventListener("click", onSkipDiscount);
	loginRedirectToggle?.addEventListener("change", updateUi);

	roleBoxes.forEach(function (box) {
		box.addEventListener("change", onRoleChange);
	});

	skipAll?.querySelector("a").addEventListener("click", function (e) {
		e.preventDefault();
		goTo(steps.indexOf("subscription"));
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
	 * Show a step and move the focus to its title.
	 *
	 * @param {number} index The step's index.
	 */
	function goTo(index) {
		current = index;
		updateUi();
		sections[index].querySelector("h2").focus();
	}

	/**
	 * Go to the next active step.
	 */
	function goNext() {
		let next = current + 1;

		while (next < steps.length && !isActive(steps[next])) {
			next++;
		}

		goTo(next);
	}

	/**
	 * Show the current step and the parts that depend on it.
	 */
	function updateUi() {
		const isSettingStep = settingSteps.includes(steps[current]);
		const counted = steps.filter((step) => settingSteps.includes(step) && isActive(step));

		sections.forEach(function (section, index) {
			section.hidden = index !== current;
		});

		footer.hidden = !isSettingStep;

		if (skipAll) skipAll.hidden = !isSettingStep;

		progress.hidden = counted.length < 2;
		progress.textContent = config.strings.progress
			.replace("%1$s", counted.indexOf(steps[current]) + 1)
			.replace("%2$s", counted.length);
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
	function onNext() {
		const step = steps[current];
		const data = { step: step };

		if ("modules" === step) {
			data.modules = checkedValues("modules");
		} else if ("widgets" === step) {
			data.widgets = checkedValues("widgets");
		} else if ("settings" === step) {
			data.settings = checkedValues("settings");
			data.roles = checkedValues("roles");
		} else if ("login_url" === step) {
			data.login_url = page.querySelector("#udb_login_redirect").value;
		}

		send(data, nextButton, goNext);
	}

	/**
	 * Subscribe to the newsletter, then finish.
	 */
	function onSubscribe() {
		send(
			{
				step: "subscribe",
				name: page.querySelector("#udb-subscription-name").value,
				email: page.querySelector("#udb-subscription-email").value,
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

		send({ step: "skip_discount", referrer: config.referrer }, null, function () {
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
			el.hidden = el.dataset.udbShowOn !== how;
		});

		// The page is done: remove its menu item.
		document.querySelectorAll("#menu-posts-udb_widgets .wp-submenu a").forEach(function (link) {
			if (link.href.includes("page=" + config.pageSlug)) {
				link.closest("li")?.remove();
			}
		});

		goTo(steps.indexOf("finished"));
	}

	/**
	 * Send a step to the server.
	 *
	 * @param {Object} data The data.
	 * @param {Element|null} button The button that shows the loading state.
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
	 * The "Hide Admin Bar for" roles: "All" or individual roles, not both.
	 *
	 * @param {Event} e The change event.
	 */
	function onRoleChange(e) {
		if (!e.target.checked) return;

		roleBoxes.forEach(function (box) {
			if (box !== e.target && ("all" === e.target.value || "all" === box.value)) {
				box.checked = false;
			}
		});
	}
})(jQuery);
