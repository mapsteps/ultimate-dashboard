(function () {
	function init() {
		moveLanguageSwitcher();
	}

	function moveLanguageSwitcher() {
		var loginDiv = document.querySelector("#login");
		if (!loginDiv) return;

		var langSwitcher = document.querySelector(".language-switcher");
		if (!langSwitcher) return;

		loginDiv.appendChild(langSwitcher);
	}

	init();
})();
