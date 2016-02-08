CKEDITOR.plugins.add('gallery', {

	onLoad: function () {
		CKEDITOR.addCss("img.cke_gallery{background-image: url(" + CKEDITOR.getUrl(this.path + "images/placegall.png") + ");background-position: center center;background-repeat: no-repeat;border: 1px solid #a9a9a9;width: 80px;height: 80px;}");
		CKEDITOR.addCss("img.cke_slider{background-image: url(" + CKEDITOR.getUrl(this.path + "images/placeslid.png") + ");background-position: center center;background-repeat: no-repeat;border: 1px solid #a9a9a9;width: 80px;height: 80px;}");
		CKEDITOR.addCss("img.cke_diafilm{background-image: url(" + CKEDITOR.getUrl(this.path + "images/placediaf.png") + ");background-position: center center;background-repeat: no-repeat;border: 1px solid #a9a9a9;width: 80px;height: 80px;}");
	},
	
	init: function (editor) {
		var command = editor.addCommand('gallery', new CKEDITOR.dialogCommand('gallery', {
			allowedContent: 'widget[!widget-type, title]',
			requiredContent: "widget[title]"
		}));
	
		editor.ui.addButton('Gallery', {
			label : 'Галерея',
			command : 'gallery',
			icon: this.path + 'images/gallery.png',
			toolbar: "insert,80"
		});
	
		if (editor.contextMenu) {
			editor.addMenuGroup('galleryGroup' );
			editor.addMenuItem('galleryItem', {
					label: 'Свойства галереи',
					icon: this.path + 'images/gallery.png',
					command: 'gallery',
					group: 'galleryGroup'
    });
	
			editor.contextMenu.addListener(function(element) {
						if (element.hasClass('cke_gallery') || element.hasClass('cke_slider') || element.hasClass('cke_diafilm')) {
								return { galleryItem: CKEDITOR.TRISTATE_OFF };
						}
			});
		}
	
		editor.on( 'doubleclick', function( evt ) {
			var element = evt.data.element;
			if ( element.is('img') && (element.hasClass('cke_gallery') || element.hasClass('cke_slider') || element.hasClass('cke_diafilm')))
				evt.data.dialog = 'gallery';
		});
		
		CKEDITOR.dialog.add('gallery', this.path + 'dialogs/gallery.js');
	},
	
	afterInit: function (editor) {
		var dataProcessor = editor.dataProcessor;
		(dataProcessor = dataProcessor && dataProcessor.dataFilter) && dataProcessor.addRules({
			elements: {
				widget: function (dataProcessor) {
					if (dataProcessor.attributes['widget-type'] == 'lightbox') {
						fakeElem = editor.createFakeParserElement(dataProcessor, "cke_gallery", "widget", !0);
						fakeElem.attributes.title = dataProcessor.attributes.title;
					return fakeElem;
					}
					if (dataProcessor.attributes['widget-type'] == 'slider') {
						fakeElem = editor.createFakeParserElement(dataProcessor, "cke_slider", "widget", !0);
						fakeElem.attributes.title = dataProcessor.attributes.title;
					return fakeElem;
					}
					if (dataProcessor.attributes['widget-type'] == 'diafilm') {
						fakeElem = editor.createFakeParserElement(dataProcessor, "cke_diafilm", "widget", !0);
						fakeElem.attributes.title = dataProcessor.attributes.title;
					return fakeElem;
					}
					
				}
			}
		})
	}
});