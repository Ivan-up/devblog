window.onload = function() {
	var _fileUploadUrl ="/index.php?c=ajax&action=ckupload&pid=post_0";
		CKEDITOR.replace('post_content', {
			filebrowserUploadUrl : _fileUploadUrl
		});
		CKEDITOR.config.extraPlugins = 'video,gallery,audio,anonsbreak,stealcontent,poll';
		CKEDITOR.config.baseHref = location.BASE_URL;
		CKEDITOR.config.allowedContent = true;
				
	
		var docType = document.getElementById('selectPostType');
		var commentSt = document.getElementById('commentSt');
		
		function checkStatus() {
			if (docType.value != 'post') 
				commentSt.style.display = 'none'
			else 
				commentSt.style.display = "";
		}
		
		checkStatus();
		
		docType.addEventListener('click', checkStatus);
	
		setTimeout(function(){
			var divAlert = document.querySelector('.post-saved');
			if (divAlert)
				divAlert.parentNode.removeChild(divAlert);
		}, 4000);

	
}