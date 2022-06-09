import writeStyle from "./write-style";

declare var wp: any;

export interface WriteBgPositionStyleParam {
	styleEl: string | HTMLStyleElement;
	cssSelector: string;
	bgPosition: string;
	keyPrefix?: string;
	bgCustomPosition?: string;
}

const writeBgPositionStyle = (opts: WriteBgPositionStyleParam) => {
	let el = opts.styleEl;
	const selector = opts.cssSelector;
	const keyPrefix = opts.keyPrefix ? opts.keyPrefix : "";
	const bgPosition = opts.bgPosition;
	let bgCustomPosition = opts.bgCustomPosition ? opts.bgCustomPosition : "";

	if (typeof el === "string") {
		el = document.querySelector(el) as HTMLStyleElement;
	}

	if (!el) return;

	bgCustomPosition = bgCustomPosition
		? bgCustomPosition
		: wp.customize("udb_login[" + keyPrefix + "bg_custom_position]").get();

	writeStyle({
		styleEl: el,
		cssSelector: selector,
		cssRules:
			"background-position: " +
			(bgPosition === "custom" ? bgCustomPosition : bgPosition) +
			";",
	});
};

export default writeBgPositionStyle;
