window.addEventListener('load', function()
{
	(function(){

			var form = document.querySelector('.mail');                  // Форма
			var preview_mail = document.querySelector('.preview_mail');  // Зона предпросмотра
			
			// Создаем кнопку предпросмотра
			var btn = document.createElement('input');			
			btn.type = 'button';
			btn.value = 'Предпросмотр HTML-версии';
			btn.className = 'btn btn-primary mail_prev';
			preview_mail.appendChild(btn);
			
			btn.addEventListener('click', function(){
				
				var htmlInput = form.querySelector('#mailhtml'); // поле загрузки html-версии
				var file_html;                                   // файла html-версии
				
				if (htmlInput.files.length != 1){					
					alert('Вы не выбрали файл html-версии');				
					return false;
				}					
				
				file_html = htmlInput.files[0];
				
				if (!file_html || !file_html.type.match(/text\/html/)) {
					alert('Неверный формат файла, должен быть html');
					return false;
				}			
					
				var reader = new FileReader();
				
				btn.style.visibility = 'hidden';
				
				reader.addEventListener("load", function(event) {
					
					var html = event.target.result;                    // текст файла html-версии					
					var images = form.querySelector('#images').files;  // выбранные картинки
					var imgs = [];					                           // очищенный массив картинок
					
					// замена url картинок на DataUrl
					// @imgArr - массив картинок 
					// @index - индекс начальной картинки (0)
					function replaceImg(imgArr, index){	
					
						var img = imgArr[index];	
						var reader = new FileReader();
	
						// при чтении в reader
						reader.addEventListener("load", function(event){
							
							var imgDataUrl = event.target.result;
							
							// Экранируем спец символы в имени файла
							var regFilter = /([\[\]\\\/\^\$\.\|\?\*\+\(\)\{\}])/g;							
							var imgName = img.name.replace(regFilter, '\\$1');
							
							// заменяем картинки
							var regEx = new RegExp('(src|url) {0,2}([=\(]) {0,2}([\'\"]) {0,2}(' + imgName + ')', 'g');
							html = html.replace(regEx, '$1$2$3' + imgDataUrl);
							
							++index;
							// проверяем последний ли элемент 
							if (imgArr.length > index){
								replaceImg(imgArr, index);								
							}
							else 
								showPreview(html);
							
						});
						
						// читаем файл
						reader.readAsDataURL(img);						
					}
					
					// выводим предпросмотр
					function showPreview(innerHtml){
						
						var doc; 
						var	frameDoc = document.createElement('iframe');
						var oldFrame = preview_mail.querySelector('.mail-iframe');
						if (oldFrame)
							preview_mail.removeChild(oldFrame);
						frameDoc.className = 'mail-iframe';
						frameDoc.src = 'about:blank';
						frameDoc.width = '100%';
						preview_mail.appendChild(frameDoc);
						
						doc = frameDoc.contentWindow.document;
						doc.open();
						doc.write(innerHtml);
						doc.close();
						
						frameDoc.style.border = "0";
						frameDoc.style.outline = "1px solid #666";
						frameDoc.height = doc.body.scrollHeight;
						
						btn.style.visibility = 'visible';
						btn.value = 'Обновить предпросмотр';
					}
					
					// оставляем только картинки
					for (var i = 0; i < images.length; i++){
						if (images[i].type.match(/image.*/))
							imgs.push(images[i])
					}
					
					// если есть картинки заменяем их, иначе выводим документ
					if (imgs.length)
						replaceImg(imgs, 0);
					else 
						showPreview(html);					
				});
				
				reader.readAsText(file_html);				
				
			}, false);		
	})();
	
	
	document.getElementById('images').addEventListener('change', function(){
		if (this.files.length > 18){
			alert('Нельзя прикрепить более 18 картинок');
			this.value = '';
		}
	});
	document.querySelector('.mail').addEventListener('submit', function(e){
		if (!this.subject.value) {
			alert('Вы не заполнили тему');
			e.preventDefault();
		}					
	});
	
});