$(function() {
	var _tokenUploadFiles ="35aaa43d0afade47df85d2739bee8fe1";
	var _timestampUploadFiles ="1451685456"; 
	var ul = document.getElementById('uploaded-files');
	ul.parentNode.style.display = "none";
	$('#file_upload').uploadify({
		'formData'     : {
			'timestamp' : _timestampUploadFiles,
			'token'     : _tokenUploadFiles
		},
		'fileSizeLimit': '50MB',
		'multi'     : true,
		'swf'      : location.BASE_URL + 'media/javascript/uploadify.swf',
		'uploader' : location.BASE_URL + 'index.php?c=audio&action=add',
		'buttonText': 'Обзор...',
		'onUploadSuccess' : function(file, data, response) {				
				
				var li = document.createElement('li');
				li.innerHTML = 'Файл <strong>' + file.name + '</strong> ' + data;
				ul.appendChild(li);
				ul.parentNode.style.display = "";
				
		}
	});
});
