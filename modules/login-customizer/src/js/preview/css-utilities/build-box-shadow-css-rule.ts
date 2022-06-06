const buildBoxShadowCssRule = (blur: string, color: string) => {
	let rule = "box-shadow: none;";
	if (!blur || !color) return rule;

	rule = "box-shadow: 0 0 " + blur + " 0 " + color + ";";

	return rule;
};

export default buildBoxShadowCssRule;