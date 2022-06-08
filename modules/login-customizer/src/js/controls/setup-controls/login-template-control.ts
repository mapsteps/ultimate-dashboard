declare var wp: any;

const setupLoginTemplateControl = () => {
	const controls = document.querySelectorAll(
		".udb-customize-control-login-template"
	) as NodeListOf<HTMLElement>;

	if (!controls.length) return;

	[].slice.call(controls).forEach(function (control: HTMLElement) {
		const controlName = control.dataset.controlName;
		const images = control.querySelectorAll(
			".udb-customize-control-template img"
		) as NodeListOf<HTMLImageElement>;

		if (!images.length) return;

		[].slice.call(images).forEach(function (image: HTMLImageElement) {
			image.addEventListener("click", function () {
				const selected = this;

				[].slice.call(images).forEach(function (img: HTMLImageElement) {
					const parentNode = img.parentNode as HTMLElement;

					if (img == selected) {
						parentNode.classList.add("is-selected");
					} else {
						parentNode.classList.remove("is-selected");
					}
				});

				wp.customize(controlName).set(this.dataset.templateName);
			});
		});
	});
}

export default setupLoginTemplateControl;