$(document).ready(function(){
	
	var myCarousel = $('#myCarousel1');
	var listGroup = myCarousel.find('.list-group');
	var clickEvent = false;
	
	function setHeightLGItem() {
		var boxHeight = myCarousel.find ('.carousel-inner').innerHeight();
		if (boxHeight) {
			var itemLength = myCarousel.find ('.item').length;
			var triggerHeight = Math.floor ( boxHeight / itemLength + 1 );
			myCarousel.find('.list-group-item').outerHeight(triggerHeight);
		}
	}
	
	setHeightLGItem();
	
	$(window).resize(setHeightLGItem);
	
		
	myCarousel.carousel({
		interval: 4000
	}).on('click', '.list-group li', function(){
		
		clickEvent = true;
		listGroup.children('li').removeClass('active');
		$(this).addClass('active');
		
	}).on('slid.bs.carousel', function(e){
		if (!clickEvent) {
			
			var count = listGroup.children().length - 1;
			var current = listGroup.children('li.active');
			
			current.removeClass('active').next().addClass('active');
			var id = parseInt(current.data('slide-to'));
			
			if (count == id) {
				listGroup.children('li').first().addClass('active');
			}
		}
		clickEvent = false;
	});	
	
	
});
