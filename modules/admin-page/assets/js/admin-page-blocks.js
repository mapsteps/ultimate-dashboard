(function () {
	var iframe = document.getElementById('udb-admin-page-blocks-iframe');
	if (!iframe) return;

	// Listen postMessage event from the page inside iframe.
	window.addEventListener('message', function (event) {
		iframe.style.height = (event.data.height) + 'px';
	}, false);
}());