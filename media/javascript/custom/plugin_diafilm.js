(function($){$.fn.diafilm = function(options){
	
	var defaults = {
		autoSlide : false,
		intervalDuration : 6000
	}
			
	if (this.length == 0) {
		return this;
	} else if (this.length > 1) {
		this.each(function() {
			$(this).diafilm(options);
		});
		return this;
	}
	
	var diafilmObj = this;
	diafilmObj.plugin = this;
	diafilmObj.slideCount = this.find('li').length;
	diafilmObj.slideActive = 0;
	diafilmObj.intervalName = null;
	diafilmObj.isAnimated = false;
	diafilmObj.config = {};
	
	diafilmObj.config = $.extend({}, defaults, options);
	
	// Получаем атрибуты элемента
	var getElement = function(object) {
		
			var element = {};
			
			var elementLink = object.find('a').attr('href');
			// Если картинки, является ссылкой
			if ((typeof elementLink != 'undefined') && (elementLink != '')) {
					element.link = elementLink;
					var elementLinkTarget = object.find('a').attr('target');
					if ((typeof elementLinkTarget != 'undefined') && (elementLinkTarget != '')) {
							element.linkTarget = elementLinkTarget;
					}
			}

			// Ссылка на уменьшенную копию картинки
			var elementThumbnail = object.find('img').attr('src');
			if ((typeof elementThumbnail != 'undefined') && (elementThumbnail != '')) {
					element.thumbnail = elementThumbnail;
			}
			
			// Ссылка на большую картинку
			var elementImage = object.find('img').attr('data-large-src');
			if ((typeof elementImage != 'undefined') && (elementImage != '')) {
					element.image = elementImage;
			}

			// Заголовок картинки
			var elementAlt = object.find('img').attr('alt');
			if ((typeof elementTitle != 'undefined') && (elementTitle != '')) {
					element.alt = elementTitle;
			}
			
			// Описание картинки
			var elementDescription = object.find('img').attr('data-description');
			if ((typeof elementDescription != 'undefined') && (elementDescription != '')) {
					element.description = elementDescription;
			}

			return element;
	};				
	
	// Генерируем необходимые элементы
	var renderDiafilm = function(){			
		
		if (diafilmObj.slideCount < 2)
			return diafilmObj; 
		
		
		diafilmObj.plugin.wrap('<div class="diafilm"></div>').removeClass('diafilm');		
		diafilmObj.plugin = diafilmObj.plugin.parent();
		
		diafilmObj.plugin.wrapInner('<div class="diafilm__lenta-wrapper"></div>');
		diafilmObj.plugin.wrapInner('<div class="diafilm__lenta"></div>');
		diafilmObj.plugin.prepend('<div class="diafilm__screen"><ul></ul></div>');
		
		var screen = diafilmObj.plugin.find('.diafilm__screen');		
		var lenta = diafilmObj.plugin.find('.diafilm__lenta');	
		
		lenta.find('li').each(function(key, value){
			var item = getElement($(this));
			$(this).attr("data-slide", key).addClass('diafilm__mini-item');
			
			var currentItem = $('<li class="diafilm__item"></li>');
			
			if (item.image)
				currentItem.html('<img src="' + item.image + '" alt="' + (item.alt ? item.alt : '') +'">');
			else if(item.thumbnail)
				currentItem.html('<img src="' + item.thumbnail + '" alt="' + (item.alt ? item.alt : '') +'">');
			
			if (item.link){
				 currentItem = currentItem.wrap('<a href="' + item.link + '"'	+ 
				 (item.linkTarget ? ' target="' + item.linkTarget + '"' : '') + '>' + '</a>');    
			}
			
			if (!item.description){
				item.description = ''
			}
			currentItem.find('img').data('description', item.description);

			screen.find('ul').append(currentItem);
			
		});
		// подпись к картинки
		screen.append('<span class="diafilm__slide-desc"></span>');
		// Элементы управления 
		screen.append('<span class="prev"><span class="prevIcon"></span></span>');
		screen.append('<span class="next"><span class="nextIcon"></span></span>');		
		lenta.append('<span class="prev"><span class="prevIcon"></span></span>');
		lenta.append('<span class="next"><span class="nextIcon"></span></span>');
	}
	
	// Создаем элементы
	renderDiafilm();
				
	var screen = diafilmObj.plugin.find('.diafilm__screen'); 
	var slides = screen.find('.diafilm__item'); 	
	var lenta = diafilmObj.plugin.find('.diafilm__lenta'); 
	var lentaList = lenta.find("ul"); 
	var lentaSlides = lenta.find('.diafilm__mini-item'); 
	var scrollSize;
	
	
	
	// Устанавливаем высоту для соблюдения пропорций
	var setImgMaxHeight = function(){
		var height = diafilmObj.plugin.width() * 10/16;	
		screen.height(height);
		//console.log(height);
		slides.find('img').css('max-height', height);
				
		var posControl = (height - screen.find('.next').outerHeight())/2;
		screen.find('.next, .prev').css("top", posControl);	
	}	
	
	// Устанавливаем длину ленты изображений
	var setListWidth = function() {		
		var listWidth = 0;	
		var i = 0;			
		lentaSlides.show().each(
			function(){
				listWidth += $(this).outerWidth();
			});			
		var minWidth = lentaList.width();
		
		if ((listWidth * 2 /lentaSlides.length) > minWidth)
			lenta.hide();
		else 
			lenta.show();
		
		if (listWidth < minWidth){
			listWidth = minWidth;
			lenta.find('.prev, .next').hide();
		} else {
			lenta.find('.prev, .next').show();
		}
		
		scrollSize	= Math.ceil(listWidth/lentaSlides.length); 
		lentaList.width(listWidth);		
		return true;
	}
	
	// размещаем элемент 
	var prepareItem = function(active) {
		
		// позиционируем слайд
		var bottom = (screen.height() - active.height()) / 2;
		var left = (screen.width() - active.width()) / 2;	
		active.css({'left': left , 'bottom': bottom });
		
		// устанавливаем подпись для слайда
		screen.find('.diafilm__slide-desc').text(active.find('img').data('description')); 
	}
	
	
	// позиционируем ленту изображений
	var setMargin = function (diff){
		var marginLeft = parseInt(lentaList.css('margin-left'));
		var newMarginLeft = marginLeft + diff;
		var delta = lenta.width() - lentaList.width();
		
		if (newMarginLeft < delta)
			newMarginLeft = delta;
		
		if (newMarginLeft > 0 || delta >= 0)
			newMarginLeft = 0;
		
		lentaList.css({'margin-left' : newMarginLeft});
	}
	
	// Устанавливаем активные элемент
	var setActiveItem = function (indexItem) {
		
		// Запущена ли анимация?
		if (diafilmObj.isAnimated || diafilmObj.slideActive == indexItem)
			return;
		
		// Устанавливаем флаг анимации
		diafilmObj.isAnimated = true;
		
		var marginBottomVal = screen.height();
		
		// Устанавливаем направление движения слайдера
		if (indexItem < diafilmObj.slideActive)
			marginBottomVal = -marginBottomVal;
		
		// скрываем текущий слайд
		slides.eq(diafilmObj.slideActive)
				.animate({'margin-bottom': marginBottomVal},diafilmObj.config.intervalDuration / 2, 
									function(){
										$(this).removeClass('active')
									});
									
		
		lentaSlides.filter('[data-slide="' + diafilmObj.slideActive +'"]')
				.removeClass('active');
		
		// делаем активным слайд с индексом indexItem
		diafilmObj.slideActive = indexItem;
		var active = slides.eq(diafilmObj.slideActive);
		
		lentaSlides.filter('[data-slide="' + diafilmObj.slideActive +'"]')
				.addClass('active');
		
		// показываем новый слайд 
		active.addClass('active').css({'margin-bottom': -marginBottomVal})
				.animate({'margin-bottom': 0},diafilmObj.config.intervalDuration/2,
									function(){
										diafilmObj.isAnimated = false
										});

		prepareItem(active);		
	}
	
	// Обеспечивает прокрутку ленты при смене слайдера
	var checkScroll = function (indexItem) {
		var elem = lentaSlides.filter('[data-slide="' + indexItem +'"]');			
		var posElem = elem.position().left;			
		var marginLeft = parseInt(lentaList.css('margin-left'));
		var visibleArea = lenta.children('.diafilm__lenta-wrapper').width() - elem.outerWidth();			
		
		if (posElem < 0)
			lentaList.css({'margin-left' : marginLeft - posElem});
		else if (posElem > visibleArea) 
			lentaList.css({'margin-left' : marginLeft - (posElem - visibleArea)});
	}
	
	// Установка таймера
	var activateInterval = function() {
		
			clearInterval(diafilmObj.intervalName);				
			
			if (diafilmObj.slideCount > 1 && diafilmObj.config.autoSlide) {
				
					diafilmObj.intervalName = setInterval (    function() {
						
							var nextSlide = diafilmObj.slideActive + 1;
							
							if (nextSlide < diafilmObj.slideCount) 
								var nextItem = nextSlide;
							else 
								var nextItem = 0;
							
							setActiveItem(nextItem);
							checkScroll(nextItem);
							
					}, diafilmObj.config.intervalDuration);
			}

			return true;
	};
	
	// Инсталяция
	var current = slides.eq(diafilmObj.slideActive).addClass('active');
	//console.log(current);
	lentaSlides.filter('[data-slide="' + diafilmObj.slideActive +'"]').addClass('active');
	setListWidth();
	setImgMaxHeight();
	current.find('img').load(function() {
														prepareItem(current);
													});
	

	
	
	// Запускаем таймер, если включена автопрокрутка
	if (diafilmObj.config.autoSlide) {
			activateInterval();
	}
	
	// прокрутка ленты вперед
	lenta.find('.next').on('click',function() {
		setMargin(-scrollSize);			
	});		
	
	// прокрутка ленты назад
	lenta.find('.prev').on('click',function() {
		setMargin(scrollSize);		
	});
	
	// клик по миниатуре изображения
	lentaSlides.on('click', function(){
		setActiveItem($(this).data('slide'));
		checkScroll(diafilmObj.slideActive);
	});		
	
	// предыдущеее изображение
	screen.find('.prev').on('click', function(){
		
		var prevSlide = diafilmObj.slideActive - 1;
		
		if (prevSlide >= 0)
			var indexItem = prevSlide;
		else
			var indexItem = slides.length - 1;
		
		setActiveItem(indexItem);			
		checkScroll(indexItem);
	});
	
	// следующие изображение изображение
	screen.find('.next').on('click', function (){
		
		var nextSlide = diafilmObj.slideActive + 1;
		
		
		if (nextSlide < slides.length)
			var indexItem = nextSlide;
		else
			var indexItem = 0;
		
		setActiveItem(indexItem);
		checkScroll(indexItem);			
	});
	
	// изменение размеров окна, пересчет размеров
	$(window).on('resize', function(){
		setListWidth();
		setImgMaxHeight();
		checkScroll(diafilmObj.slideActive);
	
	});	
		
	 // Отключать автопрокрутку при навередение
	if (diafilmObj.config.autoSlide) {
			diafilmObj.plugin.on('mouseenter', function() {
					clearInterval(diafilmObj.intervalName);
					diafilmObj.intervalName = null;
			}).on('mouseleave', function() {
					activateInterval();
			});
	}	
	
	return this;
}})(jQuery);

$(function (){
	$('.diafilm').diafilm({autoSlide: true});
});