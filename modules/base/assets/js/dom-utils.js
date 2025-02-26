/**
 * Find an HTML element by selector.
 *
 * @param {Element|string|null} parentEl The parent element or selector.
 * @param {string|null} [selector] The selector.
 *
 * @returns {HTMLElement | null} The element or null if not found.
 */
export function findHtmlEl(parentEl, selector) {
	/** @type {HTMLElement | null} */
	let el = null;

	if (parentEl instanceof Element) {
		if (!selector) {
			return null;
		}

		el = parentEl.querySelector(selector);

		if (!(el instanceof HTMLElement)) {
			return null;
		}

		return el;
	}

	if (typeof parentEl !== "string") {
		return null;
	}

	el = document.querySelector(parentEl);

	if (!(el instanceof HTMLElement)) {
		return null;
	}

	return el;
}

/**
 * Find HTML elements by selector.
 *
 * @param {Element|string|null} parentEl The parent element or selector.
 * @param {string|null} [selector] The selector.
 *
 * @returns {HTMLElement[]} The HTML elements.
 */
export function findHtmlEls(parentEl, selector) {
	/** @type {NodeListOf<Element>} */
	let els;

	/** @type {HTMLElement[]} */
	let htmlEls = [];

	if (parentEl instanceof Element) {
		if (!selector) {
			return [];
		}

		els = parentEl.querySelectorAll(selector);

		for (let i = 0; i < els.length; i++) {
			const el = els[i];

			if (el instanceof HTMLElement) {
				htmlEls.push(el);
			}
		}

		return htmlEls;
	}

	if (typeof parentEl !== "string") {
		return [];
	}

	els = document.querySelectorAll(parentEl);

	for (let i = 0; i < els.length; i++) {
		const el = els[i];

		if (el instanceof HTMLElement) {
			htmlEls.push(el);
		}
	}

	return htmlEls;
}

/**
 * Find an HTML form element by selector.
 *
 * @param {string} selector The selector.
 * @returns {HTMLFormElement | null} The element or null if not found.
 */
export function findFormEl(selector) {
	const el = document.querySelector(selector);

	if (!(el instanceof HTMLFormElement)) {
		return null;
	}

	return el;
}

/**
 * Find an HTML input element by selector.
 *
 * @param {Element|string|null} parentEl The parent element or selector.
 * @param {string|null} [selector] The selector.
 *
 * @returns {HTMLInputElement | null} The element or null if not found.
 */
export function findInputEl(parentEl, selector) {
	/** @type {HTMLInputElement | null} */
	let el = null;

	if (parentEl instanceof Element) {
		if (!selector) {
			return null;
		}

		el = parentEl.querySelector(selector);

		if (!(el instanceof HTMLInputElement)) {
			return null;
		}

		return el;
	}

	if (typeof parentEl !== "string") {
		return null;
	}

	el = document.querySelector(parentEl);

	if (!(el instanceof HTMLInputElement)) {
		return null;
	}

	return el;
}

/**
 * Find HTML input elements by selector.
 *
 * @param {Element|string|null} parentEl The parent element or selector.
 * @param {string|null} [selector] The selector.
 *
 * @returns {HTMLInputElement[]} The HTML input elements.
 */
export function findInputEls(parentEl, selector) {
	/** @type {NodeListOf<Element>} */
	let els;

	/** @type {HTMLInputElement[]} */
	let inputEls = [];

	if (parentEl instanceof Element) {
		if (!selector) {
			return [];
		}

		els = parentEl.querySelectorAll(selector);

		for (let i = 0; i < els.length; i++) {
			const el = els[i];

			if (el instanceof HTMLInputElement) {
				inputEls.push(el);
			}
		}

		return inputEls;
	}

	if (typeof parentEl !== "string") {
		return [];
	}

	els = document.querySelectorAll(parentEl);

	for (let i = 0; i < els.length; i++) {
		const el = els[i];

		if (el instanceof HTMLInputElement) {
			inputEls.push(el);
		}
	}

	return inputEls;
}
