export interface WriteStyleParam {
	styleEl: string | HTMLStyleElement;
	cssSelector: string;
	cssRules: string;
};

const writeStyle = (opts: WriteStyleParam) => {
	let el = opts.styleEl;
	const selector = opts.cssSelector;
	const rules = opts.cssRules;

	if (typeof el === 'string') {
		el = document.querySelector(el) as HTMLStyleElement;
	}

	if (!el) return;

	el.innerHTML = selector + " {" + rules + "}";
};

export default writeStyle;