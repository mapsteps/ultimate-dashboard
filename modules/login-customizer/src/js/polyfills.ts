const setupPolyfills = () => {
	/**
	 * String.prototype.includes polyfill.
	 *
	 * @see https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/includes
	 */
	if (!String.prototype.includes) {
		String.prototype.includes = function (search: any, start: number) {
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
};

export default setupPolyfills;
