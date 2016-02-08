CKEDITOR.dialog.add('stealcontentDialog', function(editor) {
	return {
		title: 'Загрузить описание функции с php.net',
		minWidht: 400,
		minHeight: 100,
		
		contents : [
			{
				id: 'tab-basic',
				label: 'Load content',
				elements: [
					{
						type: 'text',
						id: 'url',
						width: '400px',
						label: 'URL ( Например, http://php.net/manual/ru/function.mb-strlen.php )',
						validate: CKEDITOR.dialog.validate.notEmpty( "Поле не может быть пустым" )
					}
				]
			}
		],
		
		onOk: function() {
			
			var url = this.getContentElement('tab-basic', 'url').getValue();			
			var data = 'url=' + url;
			
			var xhr = new XMLHttpRequest();
			
			xhr.addEventListener("readystatechange", function(){
				
				if (xhr.readyState == 4) {
					//console.log(xhr.responseText);
					editor.insertHtml(xhr.responseText);
				}
			});
			
			xhr.open("post", location.BASE_URL + "index.php?c=ajax&action=get_cont_php_net#" + (new Date()).getTime(), false);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send(data);			
			
		}
		
	};
});