(function ($) {

	var customCSSFields = document.querySelectorAll('.udb-custom-css');
	var colorFields = document.querySelectorAll('.udb-color-field');

	if (customCSSFields.length) {
		var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};

		editorSettings.codemirror = _.extend(
			{},
			editorSettings.codemirror,
			{
				indentUnit: 4,
				tabSize: 4,
				mode: 'css',
			}
		);

		[].slice.call(customCSSFields).forEach(function (el) {
			wp.codeEditor.initialize(el, editorSettings);
		});
	}

	if (colorFields.length) {
		[].slice.call(colorFields).forEach(function (el) {
			var opts = {
				defaultColor: el.dataset.default,
				change: function (event, ui) {},
				clear: function () { },
				hide: true,
				palettes: true
			};

			$(el).wpColorPicker(opts);
		});
	}

	var refererField = document.querySelector('[name="_wp_http_referer"]');

	function setRefererValue(hash) {
		if (!refererField) return;
		var url;

		if (refererField.value.includes('#')) {
			url = refererField.value.split('#');
			url = url[0];

			refererField.value = url + '#' + hash;
		} else {
			refererField.value = refererField.value + '#' + hash;
		}
	}

	$('.heatbox-tab-nav-item').on('click', function () {
		$('.heatbox-tab-nav-item').removeClass('active');
		$(this).addClass('active');

		var link = this.querySelector('a');
		var hashValue = link.href.substring(link.href.indexOf('#') + 1);

		setRefererValue(hashValue);

		$('.udb-settings-form .heatbox-admin-panel').css('display', 'none');
		$('.udb-settings-form .udb-' + hashValue + '-panel').css('display', 'block');
	});

	window.addEventListener('load', function () {

		var hashValue = window.location.hash.substr(1);

		if (!hashValue) {
			hashValue = 'widgets';
		}

		setRefererValue(hashValue);

		$('.heatbox-tab-nav-item').removeClass('active');
		$('.heatbox-tab-nav-item.' + hashValue + '-panel').addClass('active');

		$('.udb-settings-form .heatbox-admin-panel').css('display', 'none');
		$('.udb-settings-form .udb-' + hashValue + '-panel').css('display', 'block');

	});

})(jQuery);
