(function () {
	window.addEventListener('load', handleLoadAndResize, false);
	window.addEventListener('resize', handleLoadAndResize, false);

	function handleLoadAndResize(e) {
		checkPageHeight(e);
		document.documentElement.style.overflow = 'hidden';

		setTimeout(checkPageHeight, 500);
		setTimeout(checkPageHeight, 1000);
		setTimeout(checkPageHeight, 2000);
		setTimeout(checkPageHeight, 3000);
	}

	function checkPageHeight() {
		var pageOuterHeight = document.documentElement.scrollHeight;

		var data = {
			height: pageOuterHeight
		};

		/**
		 * This file is running in a page that is inside iframe.
		 * Let's notify the parent's window via postMessage and pass the data.
		 */
		window.parent.postMessage(data, '*');
	}
}());