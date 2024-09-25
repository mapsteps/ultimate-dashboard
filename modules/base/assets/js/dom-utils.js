/**
 * Find an HTML element by selector.
 *
 * @param {string} selector The selector.
 * @returns {HTMLElement | null} The element or null if not found.
 */
export function findHtmlEl(selector) {
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
export function findHtmlEls(selector) {
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
export function findInputEl(selector) {
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
export function findInputEls(selector) {
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
