(function($){
	if( $('#udb-custom-dashboard-css').length ) {
		var editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
		editorSettings.codemirror = _.extend(
			{},
			editorSettings.codemirror,
			{
				indentUnit: 4,
				tabSize: 4,
				mode: 'css',
			}
		);
		var editor = wp.codeEditor.initialize( $('#udb-custom-dashboard-css'), editorSettings );
	}
 })(jQuery);