CKEDITOR.dialog.add('pollDialog', function(editor) {
	
	var xhr = new XMLHttpRequest();
	var arr2 = [];
	
	xhr.addEventListener("readystatechange", function(){
		
		if (xhr.readyState == 4) {
			var arr = [];
			
			try {
				arr = JSON.parse(xhr.responseText);
			} catch(e){}
			
			for (var i in arr)
			{
				arr2.push([arr[i]['question'],arr[i]['pid']]);
			}
			
		}
	});
	
	xhr.open("post", location.BASE_URL + "index.php?c=ajax&action=getpolls#" + (new Date()).getTime(), false);
	xhr.send('');
	
	return {
		title: 'Вставить опрос',
		minWidht: 400,
		minHeight: 200,
		
		contents : [
			{
				id: 'tab-basic',
				label: 'Опрос',
				elements: [
					{
						type: 'select',
						id: 'poll',
						default: 1,
						label: 'Выбрать опрос из списка',
						items: arr2
					},
					{
						type: 'button',
						id: 'new_poll',
						label: 'Создать новый опрос',
						onClick: function() {
								CKEDITOR.dialog.getCurrent().hide();
								window.open(location.BASE_URL + 'admin/poll/add/', 'new', 
								'width=600,height=480,resizable=yes,scrollbars=no');
							}
					}
				]				
			}
		],
		
		onOk: function() {
			var poll_id = this.getContentElement('tab-basic', 'poll').getValue();
			var element = this.getContentElement('tab-basic', 'poll').getInputElement().$;
			var poll_title = element.options[element.selectedIndex].text;
			var widget = editor.document.createElement('widget');
			
			widget.setAttribute('title', poll_title);
			widget.setAttribute('widget-type', 'poll');
			widget.setText('[[--widget/poll/' + poll_id + '--]]');
			widget = editor.createFakeElement(widget, "cke_poll", "widget", !0);
			
			widget.$.title = poll_title;
			editor.insertElement(widget);
		}
	};
});