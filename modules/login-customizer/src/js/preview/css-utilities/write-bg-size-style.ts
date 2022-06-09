import writeStyle from "./write-style";

declare var wp: any;

export interface WriteBgSizeStyleParam {
	styleEl: string | HTMLStyleElement;
	cssSelector: string;
	bgSize: string;
	keyPrefix?: string;
	bgCustomSize?: string;
}

const writeBgSizeStyle = (opts: WriteBgSizeStyleParam) => {
	let el = opts.styleEl;
	const selector = opts.cssSelector;
	const keyPrefix = opts.keyPrefix ? opts.keyPrefix : "";
	const bgSize = opts.bgSize;
	let bgCustomSize = opts.bgCustomSize ? opts.bgCustomSize : "";

	if (typeof el === "string") {
		el = document.querySelector(el) as HTMLStyleElement;
	}

	if (!el) return;

	bgCustomSize = bgCustomSize
		? bgCustomSize
		: wp.customize("udb_login[" + keyPrefix + "bg_custom_size]").get();

	writeStyle({
		styleEl: el,
		cssSelector: selector,
		cssRules:
			"background-size: " +
			(bgSize === "custom" ? bgCustomSize : bgSize) +
			";",
	});
};

export default writeBgSizeStyle;
