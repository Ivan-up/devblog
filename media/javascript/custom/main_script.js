location.BASE_URL = '/';

 $("document").ready( function(){
	
	(function(){
		
		function responseVideo(){
			$('.video-js').each( function() {
					var that = this;
					var id;				
					
					videojs(this).ready(function(){					
						id = this.id();
						
						if (resizeVideo(id))
							return true;
						
						var aspectRatio = 264/640;
						
						if (that.videoHeight && that.videoWidth){
							aspectRatio = that.videoHeight/that.videoWidth;
						}
										
						var width = document.getElementById(id).parentElement.offsetWidth;
						this.width(width).height( width * aspectRatio ); 
						
					});
					
					function resizeVideo(id) {						
							var wrapper = document.getElementById(id);
							var video = wrapper.querySelector('video');
							if (video.videoWidth) {
								aspectRatio = video.videoHeight/video.videoWidth;
								var width = wrapper.parentElement.offsetWidth;
								videojs(id).width(width).height( width * aspectRatio );
								return true;
							}
							return false;
					}
					
					this.addEventListener('loadedmetadata', function (){
							resizeVideo(id);
					});
					
				}
			);
		} 
		
		responseVideo();
		
		$(window).on('resize', responseVideo);
	
	})();
	
	function PullDownMenu(menuSelector, ioptions) {
		
		var defaults =  {
			iconTagName : 'i',
			iconEmptyClass : 'glyphicon glyphicon-globe',
			iconOpenClass : 'glyphicon glyphicon-minus',
			iconCloseClass : 'glyphicon glyphicon-plus'
		};
		
		this.options = ioptions;
		
		for (var p in defaults) {
			if (this.options[p] == undefined)
				this.options[p] = defaults[p];
		}
		
		if (!('querySelector' in document) || !('addEventListener' in window))
			return;
		
		
		this.init = function (menu) {
			
			var allLis = menu.querySelectorAll('li');

			for (var i = 0; i < allLis.length; i++) {		
				var li = allLis[i];		
				var elem = document.createElement(this.options['iconTagName']);
			
				li.insertBefore(elem, li.children[0]);
				
				var ul = li.querySelectorAll('ul');	
				
				if (ul.length > 0) {			
					elem.setAttribute('class', this.options['iconCloseClass']);
					elem.style.cursor = 'pointer';
					li.querySelector('ul').style.display = "none";
					var active = li.querySelectorAll('.active');
					
					if (active.length > 0) {				
						li.querySelector('ul').style.display = "block";
						elem.setAttribute('class', this.options['iconOpenClass']);
					}
				} else {
					elem.setAttribute('class', this.options['iconEmptyClass']);			
				}
			}
			
			var alli = menu.querySelectorAll(this.options['iconTagName']);
			that = this;
			for (var i = 0; i < alli.length; i++) {
				alli[i].addEventListener('click', function(event){
					
					li = this.parentNode;
					var uls = li.querySelectorAll('ul');
					
					if (uls.length == 0) return true;
					
					if (uls[0].style.display == 'block') {				
						uls[0].style.display = "none";
						this.setAttribute('class', that.options['iconCloseClass']);				
					} else {
						uls[0].style.display = "block";
						this.setAttribute('class', that.options['iconOpenClass']);
					}
					
				}, false);
			}
		}
		
		var menus = document.querySelectorAll(menuSelector);
		
		for (var i = 0; i < menus.length; i++)
			this.init(menus[i]);
		
	}
	
	var menuObj = new PullDownMenu('.pull-down-menu > ul', {});
	
	var descSiteObj = new PullDownMenu('.desc-site', 
		{ 
			iconEmptyClass : 'glyphicon glyphicon-file',
			iconOpenClass : 'glyphicon glyphicon-folder-open',
			iconCloseClass : 'glyphicon glyphicon-folder-close'
		});
	
	
});
 
 