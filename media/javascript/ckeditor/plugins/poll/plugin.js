CKEDITOR.plugins.add( 'poll', {
	
	onLoad: function() {
		CKEDITOR.addCss("img.cke_poll {background-image: url(" + CKEDITOR.getUrl(this.path + "images/poll.png") + ");background-position: center center;background-repeat: no-repeat;border: 1px solid #a9a9a9;width: 80px;height: 30px;}");
	},
	
	init: function( editor ) {

		editor.addCommand( 'poll', new CKEDITOR.dialogCommand('pollDialog', {
			allowedContent: 'widget[!widget-type, title]',
			requiredContent: "widget[title]"
		}));
		
		editor.ui.addButton( 'Poll', {
			label: 'Опрос',
			command: 'poll',
			icon: this.path + 'images/poll-icon.png',
			toolbar: 'insert'
		});

		if (editor.contextMenu) {

			editor.addMenuGroup('pollGroup' );

			editor.addMenuItem('pollItem', {
				label: 'Свойства опроса',
				icon: this.path + 'images/poll-icon.png',
				command: 'poll',
				group: 'pollGroup'
			});
			
			editor.contextMenu.addListener(function(element) {
				if (element.hasClass('cke_poll')) {
					return { pollItem: CKEDITOR.TRISTATE_OFF };
				}
			});
		}
		
		editor.on('doubleclick', function(evt){
			var element = evt.data.element;
			if (element.is('img') && elementl.hasClass('cke_poll'))
				evt.data.dialog = 'pollDialog';
		});		
		
		CKEDITOR.dialog.add('pollDialog', this.path + 'dialogs/poll.js');		
	},
	
	afterInit: function (editor) {
		
		var dataProcessor = editor.dataProcessor;
		
		(dataProcessor = dataProcessor && dataProcessor.dataFilter)
		&& dataProcessor.addRules({
			elements: {
				widget: function (dataProcessor){
					if (dataProcessor.attributes['widget-type'] == 'poll') {
						fakeElem = editor.createFakeParserElement(dataProcessor, "cke_poll", "widget", !0);
						fakeElem.attributes.title = dataProcessor.attributes.title;
						return fakeElem;
					}
				}
			}
		});
	}
});