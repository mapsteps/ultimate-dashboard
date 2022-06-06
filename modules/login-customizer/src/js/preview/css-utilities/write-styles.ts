export interface WriteStyleContentParam {
	cssSelector: string;
	cssRules: string;
};

export interface WriteStylesParam {
	styleEl: string | HTMLStyleElement;
	styles: WriteStyleContentParam[];
};

const writeStyles = (opts: WriteStylesParam) => {
	let el = opts.styleEl;
	const styles = opts.styles;

	if (!Array.isArray(styles)) return;

	if (typeof el === 'string') {
		el = document.querySelector(el) as HTMLStyleElement;
	}

	if (!el) return;

	let output = "";

	styles.forEach(function (style) {
		output += style.cssSelector + " {" + style.cssRules + "}";
	});

	el.innerHTML = output;
};

export default writeStyles;