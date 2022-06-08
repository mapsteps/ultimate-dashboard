declare var wp: any;

const setupRangeControl = () => {
	const controls = document.querySelectorAll(".udb-customize-control-range") as NodeListOf<HTMLElement>;
	if (!controls.length) return;

	[].slice.call(controls).forEach(function (control: HTMLElement) {
		var controlName = control.dataset.controlName;
		var slider = control.querySelector(
			'[data-slider-for="' + controlName + '"]'
		) as HTMLInputElement;

		let rawValue = wp.customize(controlName).get() + "";

		let unitValue = rawValue.replace(/\d+/g, "");
		unitValue = unitValue ? unitValue : "%";

		wp.customize(controlName, function (setting) {
			setting.bind(function (val) {
				rawValue = val + "";

				unitValue = rawValue.replace(/\d+/g, "");
				unitValue = unitValue ? unitValue : "%";

				const numberValue = rawValue.replace(unitValue, "").trim();

				slider.value = numberValue;
			});
		});

		slider.addEventListener("input", function (e) {
			const numberValue = this.value;

			wp.customize(controlName).set(numberValue + unitValue);
		});

		control
			.querySelector(".udb-customize-control-reset")
			.addEventListener("click", function (e) {
				wp.customize(controlName).set(this.dataset.resetValue);
			});
	});
}

export default setupRangeControl;