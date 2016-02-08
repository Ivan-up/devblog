CKEDITOR.dialog.add('gallery', function(editor) {
		var xhr = new XMLHttpRequest();
		var arr2 = [];
		
		xhr.addEventListener("readystatechange", function(){
			
			if (xhr.readyState == 4) {
				var arr = [];
				
				try {
				 arr = JSON.parse(xhr.responseText);
				}catch(e){}
				
				for (var i in arr)
					arr2.push([arr[i]['title'],arr[i]['id_gallery']]);
			}
		});

		xhr.open("post", location.BASE_URL + "index.php?c=ajax&action=getgallery#" + (new Date()).getTime(), false);
		xhr.send('');

	return {
		title : 'Вставить галерею',
		minWidth : 400,
		minHeight : 200,
		contents :
			[
				{
					id : 'tab1',
					label : 'Galleries',
					elements :
					[       
						{
						type: 'hbox',
					    widths: [ '50%', '50%' ],
					    children: [{       
								type : 'select',
								id : 'gallery',
								default: '1',
								label : 'Выберите галерею',
								width: '123px',
								items : arr2,
							},
							{       
								type : 'button',
								id : 'new_gal',
								label : 'Создать галерею',
								onClick: function() {
								       CKEDITOR.dialog.getCurrent().hide();
								       window.open(location.BASE_URL + 'admin/gallery/add', 'new', 'width=600,height=480,resizable=yes,scrollbars=no,status=yes');
								    }						
							}]
						},
						{       
							type : 'select',
							id : 'player',
							label : 'Как отображать',
							items: [ ['Галерея', 'lightbox'], ['Слайдер', 'slider'], ['Диафильм', 'diafilm'] ],
    						default: 'lightbox',
						}
					]
				}
			],
		

		onOk: function () {
			var gallery_id = this.getContentElement('tab1', 'gallery').getValue();
			var player_type = this.getContentElement('tab1', 'player').getValue();
			var element = this.getContentElement('tab1', 'gallery').getInputElement().$;
			var gallery_title = element.options[element.selectedIndex].text;
			var widget = editor.document.createElement('widget');
			widget.setAttribute('title', gallery_title);
			widget.setAttribute('widget-type', player_type);
			
			if (player_type=='lightbox'){
				widget.setText('[[--widget/gallery/' + gallery_id + '--]]');
				widget = editor.createFakeElement(widget, "cke_gallery", "widget", !0);
			}
			if (player_type=='slider'){
				widget.setText('[[--widget/slider/' + gallery_id + '--]]');
				widget = editor.createFakeElement(widget, "cke_slider", "widget", !0);
			}
			if (player_type=='diafilm'){
				widget.setText('[[--widget/diafilm/' + gallery_id + '--]]');
				widget = editor.createFakeElement(widget, "cke_diafilm", "widget", !0);
			}
			widget.$.title = gallery_title;
			editor.insertElement(widget);
    }
  };
});