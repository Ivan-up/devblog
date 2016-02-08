window.addEventListener('load', function(){	

	// Максимальное количество загружаемых изображений за одни раз
	var maxFiles = 6;
	
	// 
	var uploadImg = document.querySelector('.upload-img');
	
	// Форма загрузки изображений
	var form = uploadImg.querySelector('.upload-img__form');	
	
	// Кнопка выбора файлов
	var defaultUploadBtn = uploadImg.querySelector('.upload-img__btn-file');
	
	// Область для предпросмотра изображений
	var area = uploadImg.querySelector(".upload-img__preview");
	
	// Шаблон предпросмотра изображений картинок
	var template = uploadImg.querySelector("#image-template").innerHTML;
	
	// массив добавленых для загрузки картинок
	var queue = [];
	
	// область падения файла изображений при перетаскивании
	var box = uploadImg.querySelector('.upload-img__drop-files');
	
	// Область кнопки загрузить и удалить, а также количество файлов
	var btnUpload = uploadImg.querySelector('.upload_img__btn-upload');
	
	// Информация о состояние загрузки
	var loadingInfo = uploadImg.querySelector('.upload-img__loading');
	
	// Прогресс бар
	var progressBar = loadingInfo.querySelector('.progress-bar');
	
	var uploadedFilesName = uploadImg.querySelector('.upload-img__file-name-holder');
	
	// Область информер о загруженных изображениях - скрыта	
	area.style.display = 'none';
	btnUpload.style.display = 'none';
	loadingInfo.style.display = 'none';	
	uploadedFilesName.style.display = 'none';
	
	
	
	// Отменяем события по умолчанию и эффект всплытия
	box.addEventListener('dragenter', onDrag);
	box.addEventListener('dragover', onDrag);

	function onDrag(e) {
		e.stopPropagation();
		e.preventDefault();
	}
	
	/******************************************* 
	 * Метод при падении файла в зону загрузки 
	 *******************************************/
	box.addEventListener('drop', function(e) {
		e.stopPropagation();
		e.preventDefault();
		
		var files = e.dataTransfer.files;
		if (files.length <= maxFiles && (queue.length + files.length) <= maxFiles){	
			area.style.display = '';
			btnUpload.style.display = '';	
			for (var i = 0; i < files.length; i++) {
				preview(files[i]);
			}
			this.value = "";		
		} else {
			alert('Ты не можешь загрузить больше чем ' + maxFiles + ' картинок за один раз!');
			this.value = "";
			files.length = 0;
			return ;
		}		
	});
	
	/***************************************
	 * При нажатие на кнопку выбора файлов 
	 ***************************************/
	defaultUploadBtn.addEventListener("change", function(){
		
		var files = this.files;
		if (files.length <= maxFiles && (queue.length + files.length) <= maxFiles) {
			area.style.display = '';
			btnUpload.style.display = '';	
			
			for (var i = 0; i < files.length; i++) {
				preview(files[i]);
			}
			
			this.value = "";
		} else {
			alert('You can not upload more than' + maxFiles + ' images!');
			this.value = "";
			files.length = 0;
			return ;
		}
	});
	
	/*************************************************
	 * Добавление предпросмотра картинок на страницу 
	 *************************************************/
	function preview(file) {
		// Если файл выбран и является картинкой
		if (file && file.type.match(/image.*/)) {
			
			var reader = new FileReader();
			// при чтении в reader
			reader.addEventListener("load", function(event) {
				
				// вставляем картинку на страницу
				var html = Mustache.render(template, {
					"image": event.target.result,
					"name": file.name
				});				
				var li = document.createElement("li");
				li.innerHTML = html;				
				area.appendChild(li);
				
				// добавляем возможность удаления картинки из предпросмотра
				li.querySelector(".upload-img__del-link").addEventListener('click',
					function(event) {
						event.preventDefault;
						removePreview(li);
					});				
				
				// добавляем в файл в массив для загрузки
				queue.push({
					"filename": file.name,
					"value": event.target.result,
					"li": li,
					"filesize": file.size
				});
				
				filesTextStatus(queue);				
			});
			
			// читаем файл
			reader.readAsDataURL(file);
		}
	}
	
	/**************************************************
	 * Выводит информацию о кол-ве файлов для загрузки
	 **************************************************/
	function filesTextStatus(queue) {
		if (queue.length == 0){
			area.style.display = 'none';
			btnUpload.style.display = 'none';	
		} else if (queue.length == 1){
			uploadImg.querySelector('.upload_img__files-info span').
				innerHTML = "1";
		} else {
			uploadImg.querySelector('.upload_img__files-info span').
				innerHTML = queue.length;
		}
	}
	
	/**************************************
	 * удаление картинки из предпросмотра 
	 **************************************/
	function removePreview(li) {
		queue = queue.filter(function(element) {
			return element.li != li;
		});
		
		li.parentNode.removeChild(li);
		filesTextStatus(queue);
		if (queue.length == 0) {
			// Скрываем область для обработки загрузки файлов
			area.style.display = 'none';
			btnUpload.style.display = 'none';	
		}
	}
	
	/*******************************************
	 * Удаление всех картинок из предпросмотра 
	 *******************************************/
	function restartFiles() {
		
		// Установим бар загрузки в значение по умолчанию
		progressBar.style.width = '0%';
		progressBar.innerHTML = '';
		loadingInfo.querySelector('.upload-img__loading-file').innerHTML = "";	
		loadingInfo.style.display = "none";	
		
		// Удаляем все изображения в предпросмотре
		area.innerHTML = "";
		
		// Скрываем область для обработки загрузки файлов
		area.style.display = 'none';
		btnUpload.style.display = 'none';	
		
		// Очищаем массив
		queue.length = 0;
		
		filesTextStatus(queue);
		
		return false;
	}
	
	/*********************************************
	 * Удаляет все изображения из предпросмотра
	 *********************************************/
	btnUpload.querySelector('.upload_img__del-all').addEventListener('click',	function (){
																																			this.preventDefault;
																																			restartFiles();
																																	});
	
	/**********************************
	 * Загрузка изображений на сервер
	 **********************************/
	form.addEventListener('submit', 
		function (e){
			e.preventDefault();
			
			if (!queue)
				return;
			
			// Показываем информации о состояние загрузки
			loadingInfo.style.display = "";
			// Показываем место отображения списка загруженных файлов
			uploadedFilesName.style.display = '';
			// Скрываем область кнопок загрузить и удалить все
			btnUpload.style.display = 'none';
			
			// Переменные для работы прогресс бара			
			var totalSize = 0;
			var uploaded = 0;
			// индекс файла в массиве
			var x = 0;
			// id-галереи
			var galleryId = this['gallery_id'].value;
			// имя файла			
			var fileName = queue[x].filename;			
			// массив для отправки на сервер
			var data = 'name=' + fileName + '&value=' + queue[x].value + '&gallery_id=' + galleryId;
			
			for (var i = 0; i < queue.length; i++)
				totalSize += queue[i].filesize;
		
			// Загружаем файл
			loadingInfo.querySelector('.upload-img__loading-file').innerHTML = 'Загружается ' + fileName+ ' ( 0 из' + queue.length + ')';			
			request(data, getStatus);
			
			function getStatus(response) {
				
				// Изменения бара загрузки			
				uploaded += queue[x].filesize;
				progressBar.style.width = Math.round(100*uploaded/totalSize) + '%';
				progressBar.innerHTML = Math.round(100*uploaded/totalSize) + '%';					
							
				++x;
				
				// Формируем в виде списка все загруженные изображения
				var text = document.createTextNode(response);
				var li = document.createElement('li');
				li.appendChild(text);
				uploadedFilesName.querySelector(".upload-img__uploaded-files").appendChild(li);
				
				// Если загрузка завершена 
				if (uploaded == totalSize){
					// Загрузка завершена 
					loadingInfo.querySelector('.upload-img__loading-file').firstChild.nodeValue = 'Все файлы отправлены';
					// Вызываем функцию удаления всех узображений после задержики 1 секунда 
					setTimeout(restartFiles, 1000);
				} else {
					// Загружаем следующий файл
					fileName = queue[x].filename;
					loadingInfo.querySelector('.upload-img__loading-file').innerHTML = 'Загружается ' + fileName + ' (' + x + ' из ' + queue.length + ')';
					data = 'name=' + fileName + '&value=' + queue[x].value + '&gallery_id=' + galleryId;
					request(data, getStatus);
				}						
			}			
			
		});
		
	
	/******************************************
	 * функция отправки изображения на сервер
	 *****************************************/
	function request(data, fn) {
		var xhr = new XMLHttpRequest();		
		
		xhr.addEventListener("readystatechange", function() {
			
			if (xhr.readyState == 4){
				fn(xhr.responseText);
			}
		});
		
		xhr.open("post", location.BASE_URL + "index.php?c=ajax&action=image#" + (new Date()).getTime(), true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(data);
	}
	
});