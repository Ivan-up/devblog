CKEDITOR.plugins.add('documents', {

	onLoad: function () {
		CKEDITOR.addCss("img.cke_doc {background-image: url(" + CKEDITOR.getUrl(this.path + "images/icon-doc.png") + ");background-position: center center;background-repeat: no-repeat;border: 1px solid #a9a9a9;width: 80px;height: 80px;}");
		CKEDITOR.addCss("img.cke_xls{background-image: url(" + CKEDITOR.getUrl(this.path + "images/icon-xls.png") + ");background-position: center center;background-repeat: no-repeat;border: 1px solid #a9a9a9;width: 80px;height: 80px;}");
		CKEDITOR.addCss("img.cke_pdf{background-image: url(" + CKEDITOR.getUrl(this.path + "images/icon-pdf.png") + ");background-position: center center;background-repeat: no-repeat;border: 1px solid #a9a9a9;width: 80px;height: 80px;}");
	},

	
	init: function (editor) {
		var command = editor.addCommand('documents', new CKEDITOR.dialogCommand('documents', {
		allowedContent: 'widget[!widget-type, title]',
		requiredContent: "widget[title]"
	}));
	
	editor.ui.addButton('Documents', {
	  label : 'Документы',
	  command : 'documents',
	  icon: this.path + 'images/docs.png',
	  toolbar: "insert,80"
	});
	
	if (editor.contextMenu) {
    editor.addMenuGroup('documentsGroup' );
    editor.addMenuItem('documentsItem', {
        label: 'Свойства документа',
		icon: this.path + 'images/docs.png',
        command: 'documents',
        group: 'documentsGroup'
    });
	
	editor.contextMenu.addListener(function(element) {
        if (element.hasClass('cke_doc') || element.hasClass('cke_xls') || element.hasClass('cke_pdf')) {
            return { documentsItem: CKEDITOR.TRISTATE_OFF };
        }
    });
	}
	
	editor.on( 'doubleclick', function( evt ) {
		var element = evt.data.element;
		if ( element.is('img') && (element.hasClass('cke_doc') || element.hasClass('cke_xls') || element.hasClass('cke_pdf')))
			evt.data.dialog = 'documents';
	});
	
	CKEDITOR.dialog.add('documents', this.path + 'dialogs/documents.js');
	},
	
	afterInit: function (editor) {
		var dataProcessor = editor.dataProcessor;
		(dataProcessor = dataProcessor && dataProcessor.dataFilter) && dataProcessor.addRules({
			elements: {
				widget: function (dataProcessor) {
					if (dataProcessor.attributes['widget-type'] == 'document-doc') {
						fakeElem = editor.createFakeParserElement(dataProcessor, "cke_doc", "widget", !0);
						fakeElem.attributes.title = dataProcessor.attributes.title;
					return fakeElem;
					}
					if (dataProcessor.attributes['widget-type'] == 'document-xls') {
						fakeElem = editor.createFakeParserElement(dataProcessor, "cke_xls", "widget", !0);
						fakeElem.attributes.title = dataProcessor.attributes.title;
					return fakeElem;
					}
					if (dataProcessor.attributes['widget-type'] == 'document-pdf') {
						fakeElem = editor.createFakeParserElement(dataProcessor, "cke_pdf", "widget", !0);
						fakeElem.attributes.title = dataProcessor.attributes.title;
					return fakeElem;
					}				
					
				}
			}
		})
	}
});