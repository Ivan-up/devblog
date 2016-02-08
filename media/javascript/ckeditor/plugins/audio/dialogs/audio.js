CKEDITOR.dialog.add('audioDialog', function(editor) {
	
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
				arr2.push([arr[i]['title'],arr[i]['fid']]);
			}
			
		}
	});
	
	xhr.open("post", location.BASE_URL + "index.php?c=ajax&action=getaudio#" + (new Date()).getTime(), false);
	xhr.send('');
	
	return {
		title: 'Вставить аудио',
		minWidht: 400,
		minHeight: 200,
		
		contents : [
			{
				id: 'tab-basic',
				label: 'Audio',
				elements: [
					{
						type: 'select',
						id: 'audio',
						default: 1,
						label: 'Выбрать аудиозапись из списка',
						items: arr2
					},
					{
						type: 'button',
						id: 'new_audio',
						label: 'Загрузить новую аудиозапись',
						onClick: function() {
								CKEDITOR.dialog.getCurrent().hide();
								window.open(location.BASE_URL + 'admin/audio/add/', 'new', 
								'width=600,height=480,resizable=yes,scrollbars=no');
							}
					}
				]				
			}
		],
		
		onOk: function() {
			var audio_id = this.getContentElement('tab-basic', 'audio').getValue();
			var element = this.getContentElement('tab-basic', 'audio').getInputElement().$;
			var audio_title = element.options[element.selectedIndex].text;
			var widget = editor.document.createElement('widget');
			
			widget.setAttribute('title', audio_title);
			widget.setAttribute('widget-type', 'audio');
			widget.setText('[[--widget/audio/' + audio_id + '--]]');
			widget = editor.createFakeElement(widget, "cke_audio", "widget", !0);
			
			widget.$.title = audio_title;
			editor.insertElement(widget);
		}
	};
});