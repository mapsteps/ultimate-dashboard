const proNotice = {
	show: (autoHide?: boolean) => {
		const notice: HTMLElement = document.querySelector(
			".udb-pro-login-customizer-notice"
		);

		if (!notice) return;

		notice.classList.add("is-shown");

		if (autoHide) setTimeout(proNotice.hide, 3000);
	},

	hide: () => {
		var notice: HTMLElement = document.querySelector(
			".udb-pro-login-customizer-notice"
		);

		if (!notice) return;

		notice.classList.remove("is-shown");
	},
};

export default proNotice;
