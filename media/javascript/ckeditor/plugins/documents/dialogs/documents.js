CKEDITOR.dialog.add('documents', function(editor) {
		$.ajax({
			type: "POST",
			url: "ajax/getdocuments",
			success: function(msg){ 
				CKEDITOR.arrDocuments = msg; 
				arr = JSON.parse(CKEDITOR.arrDocuments);
				arr2 = [];
				types = [];
				for (var i in arr)
				{
					arr2.push([arr[i]['title'],arr[i]['id_document']]);
					types[arr[i]['id_document']] = arr[i]['type'];
				}
			},
			async: false     
        });
	return {
		title : 'Вставить документ',
		minWidth : 400,
		minHeight : 200,
		contents :
			[
				{
					id : 'tab1',
					label : 'Documents',
					elements :
					[       
						{       
							type : 'select',
								id : 'document',
								default: '1',
								label : 'Выберите документ',
								items : arr2,
						},
						{       
							type : 'button',
							id : 'new_doc',
							label : 'Загрузить документ',
							onClick: function() {
							       CKEDITOR.dialog.getCurrent().hide();
							       window.open('/docs/add', 'new', 'width=600,height=480,resizable=yes,scrollbars=no,status=yes');
							    }						
						}
					]
				}
			],
		

		onOk: function () {
			var document_id = this.getContentElement('tab1', 'document').getValue();
			var element = this.getContentElement('tab1', 'document').getInputElement().$;
			var document_title = element.options[element.selectedIndex].text;
			var document_type = types[document_id];
			var widget = editor.document.createElement('widget');
			
			widget.setAttribute('title', document_title);
			widget.setAttribute('widget-type', 'document-'+document_type);
			widget.setText('[[--widget/documents/' + document_id + '--]]');
			widget = editor.createFakeElement(widget, "cke_"+document_type, "widget", !0);

			widget.$.title = document_title;
			editor.insertElement(widget);
        }
  };
});