import writeStyle from "./write-style";

declare var wp: any;

export interface WriteBgPositionStyleParam {
	styleEl: string | HTMLStyleElement;
	cssSelector: string;
	bgPosition: string;
	keyPrefix?: string;
	bgHorizontalPosition?: string;
	bgVerticalPosition?: string;
};

const writeBgPositionStyle = (opts: WriteBgPositionStyleParam) => {
	let el = opts.styleEl;
	const selector = opts.cssSelector;
	const keyPrefix = opts.keyPrefix ? opts.keyPrefix : "";
	const bgPosition = opts.bgPosition;

	let bgHorizontalPosition = opts.bgHorizontalPosition
		? opts.bgHorizontalPosition
		: "";

	let bgVerticalPosition = opts.bgVerticalPosition
		? opts.bgVerticalPosition
		: "";

	if (typeof el === 'string') {
		el = document.querySelector(el) as HTMLStyleElement;
	}

	if (!el) return;

	bgHorizontalPosition = bgHorizontalPosition
		? bgHorizontalPosition
		: wp
			.customize("udb_login[" + keyPrefix + "bg_horizontal_position]")
			.get();

	bgVerticalPosition = bgVerticalPosition
		? bgVerticalPosition
		: wp.customize("udb_login[" + keyPrefix + "bg_vertical_position]").get();

	let customBgPosition = "";

	if (bgPosition === "custom") {
		customBgPosition =
			(!bgHorizontalPosition ? "0%" : bgHorizontalPosition) +
			" " +
			(!bgVerticalPosition ? "0%" : bgVerticalPosition);

		writeStyle({
			styleEl: el,
			cssSelector: selector,
			cssRules: "background-position: " + customBgPosition + ";",
		});
	} else {
		writeStyle({
			styleEl: el,
			cssSelector: selector,
			cssRules: "background-position: " + bgPosition + ";",
		});
	}
};

export default writeBgPositionStyle;