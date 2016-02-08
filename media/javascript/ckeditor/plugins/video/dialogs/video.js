CKEDITOR.dialog.add('videoDialog', function(editor) {
	
	var xhr = new XMLHttpRequest();
	var arr2 = [];
	
	xhr.addEventListener("readystatechange", function(){
		
		if (xhr.readyState == 4) {
			var arr = [];
			
			try {
				arr = JSON.parse(xhr.responseText);
			}catch(e){}
			
			for (var i in arr)
			{
				arr2.push([arr[i]['title'],arr[i]['fid']]);
			}
			
		}
	});
	
	xhr.open("post", location.BASE_URL + "index.php?c=ajax&action=getvideo#" + (new Date()).getTime(), false);
	xhr.send('');
	
	return {
		title: 'Вставить видео',
		minWidht: 400,
		minHeight: 200,
		
		contents : [
			{
				id: 'tab-basic',
				label: 'Video',
				elements: [
					{
						type: 'select',
						id: 'video',
						default: 1,
						label: 'Выбрать видео из списка',
						items: arr2
					},
					{
						type: 'button',
						id: 'new_video',
						label: 'Загрузить новое видео',
						onClick: function() {
								CKEDITOR.dialog.getCurrent().hide();
								window.open(location.BASE_URL + 'admin/video/add/', 'new', 
								'width=600,height=480,resizable=yes,scrollbars=no');
							}
					}
				]				
			}
		],
		
		onOk: function() {
			var video_id = this.getContentElement('tab-basic', 'video').getValue();
			var element = this.getContentElement('tab-basic', 'video').getInputElement().$;
			var video_title = element.options[element.selectedIndex].text;
			var widget = editor.document.createElement('widget');
			
			widget.setAttribute('title', video_title);
			widget.setAttribute('widget-type', 'video');
			widget.setText('[[--widget/video/' + video_id + '--]]');
			widget = editor.createFakeElement(widget, "cke_video", "widget", !0);
			
			widget.$.title = video_title;
			editor.insertElement(widget);
		}
	};
});