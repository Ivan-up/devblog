CKEDITOR.plugins.add( 'video', {
	
	onLoad: function() {
		CKEDITOR.addCss("img.cke_video {background-image: url(" + CKEDITOR.getUrl(this.path + "images/video.png") + ");background-position: center center;background-repeat: no-repeat;border: 1px solid #a9a9a9;width: 80px;height: 80px;}");
	},
	
	init: function( editor ) {

		editor.addCommand( 'video', new CKEDITOR.dialogCommand('videoDialog', {
			allowedContent: 'widget[!widget-type, title]',
			requiredContent: "widget[title]"
		}));
		
		editor.ui.addButton( 'Video', {
			label: 'Видео',
			command: 'video',
			icon: this.path + 'images/video.png',
			toolbar: 'insert'
		});

		if (editor.contextMenu) {

			editor.addMenuGroup('videoGroup' );

			editor.addMenuItem('videoItem', {
				label: 'Свойства видео',
				icon: this.path + 'images/video.png',
				command: 'video',
				group: 'videoGroup'
			});
			
			editor.contextMenu.addListener(function(element) {
				if (element.hasClass('cke_video')) {
					return { videoItem: CKEDITOR.TRISTATE_OFF };
				}
			});
		}
		
		editor.on('doubleclick', function(evt){
			var element = evt.data.element;
			if (element.is('img') && elementl.hasClass('cke_video'))
				evt.data.dialog = 'videoDialog';
		});		
		
		CKEDITOR.dialog.add('videoDialog', this.path + 'dialogs/video.js');		
	},
	
	afterInit: function (editor) {
		
		var dataProcessor = editor.dataProcessor;
		
		(dataProcessor = dataProcessor && dataProcessor.dataFilter)
		&& dataProcessor.addRules({
			elements: {
				widget: function (dataProcessor){
					if (dataProcessor.attributes['widget-type'] == 'video') {
						fakeElem = editor.createFakeParserElement(dataProcessor, "cke_video", "widget", !0);
						fakeElem.attributes.title = dataProcessor.attributes.title;
						return fakeElem;
					}
				}
			}
		});
	}
});