CKEDITOR.plugins.add( 'audio', {
	
	onLoad: function() {
		CKEDITOR.addCss("img.cke_audio {background-image: url(" + CKEDITOR.getUrl(this.path + "images/audio.png") + ");background-position: center center;background-repeat: no-repeat;border: 1px solid #a9a9a9;width: 80px;height: 80px;}");
	},
	
	init: function( editor ) {

		editor.addCommand( 'audio', new CKEDITOR.dialogCommand('audioDialog', {
			allowedContent: 'widget[!widget-type, title]',
			requiredContent: "widget[title]"
		}));
		
		editor.ui.addButton( 'Audio', {
			label: 'Аудио',
			command: 'audio',
			icon: this.path + 'images/audio.png',
			toolbar: 'insert'
		});

		if (editor.contextMenu) {

			editor.addMenuGroup('audioGroup' );

			editor.addMenuItem('audioItem', {
				label: 'Свойства аудио',
				icon: this.path + 'images/audio.png',
				command: 'audio',
				group: 'audioGroup'
			});
			
			editor.contextMenu.addListener(function(element) {
				if (element.hasClass('cke_audio')) {
					return { audioItem: CKEDITOR.TRISTATE_OFF };
				}
			});
		}
		
		editor.on('doubleclick', function(evt){
			var element = evt.data.element;
			if (element.is('img') && elementl.hasClass('cke_audio'))
				evt.data.dialog = 'audioDialog';
		});		
		
		CKEDITOR.dialog.add('audioDialog', this.path + 'dialogs/audio.js');		
	},
	
	afterInit: function (editor) {
		
		var dataProcessor = editor.dataProcessor;
		
		(dataProcessor = dataProcessor && dataProcessor.dataFilter)
		&& dataProcessor.addRules({
			elements: {
				widget: function (dataProcessor){
					if (dataProcessor.attributes['widget-type'] == 'audio') {
						fakeElem = editor.createFakeParserElement(dataProcessor, "cke_audio", "widget", !0);
						fakeElem.attributes.title = dataProcessor.attributes.title;
						return fakeElem;
					}
				}
			}
		});
	}
});