const setupColorControl = () => {
	const controls = document.querySelectorAll(".udb-customize-control-color") as NodeListOf<HTMLElement>;
	if (!controls.length) return;

	[].slice.call(controls).forEach(function (control: HTMLElement) {
		var clearColor = control.querySelector(".wp-picker-clear");
		if (!clearColor) return;
		clearColor.classList.remove("button-small");
	});
}

export default setupColorControl;