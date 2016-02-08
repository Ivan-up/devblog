/**
 * Класс FileUploader
 * @param ioptions Ассоциативный массив опций загрузки
 */
function FileUploader(ioptions) {	
	
	// Позиция, с которой будем загружать файл
	this.position = 0;
	
	// Размер загружаемого файла
	this.filesize = 0;
	
	// Объект Blob или File (FileList[i])
	this.file = null;
	
	// Ассоциативный массив опций
	this.options = ioptions;
	
	// Если не определена опция uploadscript, то возвращаем null. 
	// Нельзя продолжать, если эта опция не определена.
	if (this.options['uploadscript'] == undefined) return null;
	
	/**
	 * Проверка, поддерживает ли браузер необходимые объекты
	 * @return true, если браузер поддерживает все необходимые объекты 
	 */
	this.CheckBrowser = function() {
		if (window.File && window.FileReader && window.FileList && window.Blob)
			return true;
		else 
			return false;
	}
	
	/**
	 * Загружка части файла на сервер
	 * @param from Позиция, с которой будем загружать файл
	 */
	this.UploadPortion = function(from) {
		
		// Объект FileReader, в него будем считываеть часть загружаемого файла
		var reader = new FileReader();
		
		//console.log(reader);
		// Текущий объект 
		var that = this;
		
		// Позиция с которой будем загружать файл 
		var loadfrom = from;
		
		// Объект Blob, для частичного считывания файла
		var blob = null;
		
		// Таймаут для функции setTimeout. С помощью функции реализована 
		// повторная попытка загрузки  по таймеру 
		var xhrHttpTimeout = null;
		
		/**
		 * Событие срабатывающее после чтения части  файла в FileReader
		 * @param evn Событие
		 */
		reader.onloadend = function(evt) {
			
			if (evt.target.readyState == FileReader.DONE) {
				
				// Создадим объект XMLHttpRequest, установим адрес скрипта 
				// для POST и необходимые заголовки HTTP запроса
				var xhr = new XMLHttpRequest();
				
				xhr.open('POST', that.options['uploadscript'], true);
				
				xhr.setRequestHeader('Content-Type', "application/x-binary; charset=x-user-defined");
				
				// Идентификатор загрузки(чтобы знать на строне сервера
				// что с чем склеивать)
				xhr.setRequestHeader("Upload-Id", that.options['uploadid']);
				
				// Позиция начала в файле
				xhr.setRequestHeader("Portion-From", from);
				
				// Размер порции
				xhr.setRequestHeader("Portion-Size", that.options['portion']);
				
				// Установим таймаут
				that.xhrHttpTimeout = setTimeout(function() {
					xhr.abort();
				}, that.options['timeout']);				
		
		
				/**
				 * Событие XMLHttpRequest.onProcess. Отрисовка ProgressBar.
				 * @param evt Событие 
				 */
				xhr.upload.addEventListener("progress", function (evt) {
				 
				 if (evt.lengthComputable) {
					 
					 // Посчитаем кол-во закаченного в процентах
					 var percentComplete = Math.round((loadfrom + evt.loaded) * 100 / that.filesize);
					 
					 //console.log("Загружено: " + percentComplete + " / 100");
					 
					 var progress = document.querySelector('.progress-bar');
					 
					 progress.firstChild.nodeValue = percentComplete + '%';
					 progress.style.width = percentComplete + '%';
					 
				 }
				}, false);
		 
				/**
				 * Событие XMLHttpRequest.onLoad. Окончание загрузки порции.
				 * @param evt Событие
				 */
				xhr.addEventListener("load", function (evt) {
					// Очистим таймаут
					clearTimeout(that.xhrHttpTimeout);
					
					// Если сервер не вернул статус 200, то выведем окнос с сообщением
					// сервера 
					if (evt.target.status != 200) {
						alert(evt.target.responseText);
						return;
					}
					
					// Добавим к текущей позиции размер порции.
					that.position += that.options['portion'];
					
					// Закачиваем следующую порцию, если файл еще не кончился
					if (that.filesize > that.position) {
						that.UploadPortion(that.position);
					} else {
						// Если все порции загружены, сообщим об этом серверу. 
						// XMLHttpRequest, метод GET, PHP скрипт тот-же
						var gxhr = new XMLHttpRequest();
						gxhr.open('GET', that.options['uploadscript'] + '&st=done', true);
						
						// Установим идентификатор загрузки 
						gxhr.setRequestHeader("Upload-Id", that.options['uploadid']);
						
						/**
						 * Событие XMLHttpRequest.onLoad. Окончание загрузки сообщения
						 * об окончании загрузки файла.
						 * @param evt Событие 
						 */
						 gxhr.addEventListener("load", function(evt) {
							 if (evt.target.status != 200) {
								 alert(evt.target.responseText.toString());
								 return;
							 } else {
								 // Если все нормально, то отправим пользователя дальше
								 // там может быть сообщение об успешной загрузке или следующий
								 // шаг формы с дополнительными полями
								 that.onSuccess();
							 }
						 }, false);
						 
						 // Отправим HTTP GET запрос 
						 gxhr.send('');
					}
				}, false);					 
					 
					 
				/**
				 * Событие XMLHttpRequest.onError. Ошибка при загрузке
				 * @param evt Событие 
				 */
				xhr.addEventListener("error", function(evt){ 
					// Очистим таймут 
					clearTimeout(that.xhrHttpTimeout);
					
					// Сообщим серверу об ошибке во время загрузке,
					// сервер сможет удалить уже загруженные части.
					// XMLHttpRequest, метод GET, PHP скрипт тот-же.
					var gxhr = new XMLHttpRequest();
					
					gxhr.open('GET', that.options['uploadscript'] + '&st=abort', true);
					
					// Установим идентификатор загрузки 
					gxhr.setRequestHeader("Upload-Id", that.option['upload']);

					/**
					 * Событие XMLHttpRequest.onLoad. Окончание загрузки сообщения
					 * об ошибке загрузки 
					 * @param evt Событие
					 */
					gxhr.addEventListener("load", function (evt){
					 // Если сервер не вернул HTTP статус 200, то выведем
					 // окно с сообщением сервера 
					 if (evt.target.status != 200) {
						 alert(evt.target.responseText);
						 return;
					 }
					}, false);
					 
					// Отправим HTTP GET запрос 
					gxhr.send('');
					
					// Отобразим сообщение об ошибке 
					if (that.options['message_error'] == undefined)
						alert("There was an error attempting to upload the file.");
					else 
						alert(that.options['message_error']);
				}, false);						
						
						
				/**
				 * Событие XMLHttpRequest.onAbort. Если по какой-то причине
				 * передачи прервана, повторим попытку.
				 * @param evt Событие 
				 */					 
				xhr.addEventListener("abort", function(evt) {
					clearTimeout(that.xhrHttpTimeout);
					that.UploadPortion(that.portion);
				}, false);
				
				console.dir(xhr);
				// Отправим порцию медом POST
				xhr.send(evt.target.result);					
			}
			
		};
			
		that.blob = null;

		// Считаем порцию в объект Blob. Три условия для трех возможный определений Blob.[.*]slice().
		if (this.file.slice)
			that.blob = this.file.slice(from, from + that.options['portion']);
		else {
			if (this.file.webkitSlice) 
				that.blob = this.file.webkitSlice(from, from + that.options['portion']);
			else {
				if (this.file.mozSlice) 
					that.blob = this.file.mozSlice(from, from + that.options['portion']);
			}				
		}

		// Считываем Blob (часть файла) в FileReader 
		reader.readAsArrayBuffer(that.blob);		
	
	}	
	
	
	/**
	 * Загрузка файла на сервер 
	 * return Число. Если не 0, то произошла ошибка 
	 */
	this.Upload = function() {
		
		// Скроем форму, чтобы пользователь не отправил файл дважды
		// var e = document.getElementById(this.option['form']);
		// if (e) e.style.display = 'none';
				
		if (!this.file) 
			return -1;
		else {
			// Если размер файла меньше размера порции, значит порция равна размеру файла
			if (this.filesize < this.options['portion']){
				this.options['portion'] = this.filesize;
			}			
			this.UploadPortion(0);
		}		
	}
	
	if (this.CheckBrowser()) {
		
		// Установим значение по умолчанию
		if (this.options['portion'] == undefined) 
			this.options['portion'] = 1048576;
		
		if (this.options['timeout'] == undefined)
			this.options['timeout'] = 15000;
		
		var that = this;
		
		// Добавим обработку события выбора файла 
		document.getElementById(this.options['formfiles']).addEventListener('change', function (evt) {
			var files = evt.target.files;
			
			// Выберем только первый файл
			that.filesize = files[0].size;
			that.file = files[0];
			
		}, false);
		
		// Добавим обработку события onSubmit формы 
		document.getElementById(this.options['form']).addEventListener('submit', function (evt) {
			if (that.checkUpload()) {
				that.Upload();
				evt.target.preventDefault();
				//(arguments[0].preventDefault) ? arguments[0].preventDefault() : arguments[0].returnValue = false;
			}
		}, false);
		
	}
	
	this.onSuccess = function(){
	}
	
	this.checkUpload = function(){
		return true;
	}
	
}