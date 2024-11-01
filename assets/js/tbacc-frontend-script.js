/********************
Tab Accordion Plugin Frontend javascript
*********************/
jQuery(function($){

	var tbaccIntWidth = parseFloat(tbaccWidth);
	var windowWidth	  = $(window).width();
	tbaccTabAccordionToggle(windowWidth);

	$(window).resize(function(){
		var windowWidth	  = $(this).width();
		tbaccTabAccordionToggle(windowWidth);
	});
	
	$(document).on('click','.tbacc-tab-titles li.tbacc-title a',function(el){
		el.preventDefault();
		if(!$(this).parent().hasClass('tbacc-active')){
			$('.tbacc-tab-titles li.tbacc-title').removeClass('tbacc-active');
			$(this).parent().addClass('tbacc-active');
			var target = $(this).attr('href');
			$('.tbacc-tab-contents .tbacc-tab-content').removeClass('tbacc-active');
			$(target).addClass('tbacc-active');
		}
	});

	$(document).on('click','.tbacc-accordion-title a',function(el){
		el.preventDefault();
		var itemId;
		var currentEleId = $(this).parents('.tbacc-tab-content').attr('id');
		$('.tbacc-tab-contents .tbacc-tab-content').removeClass('tbacc-active');
		$(this).parents('.tbacc-tab-content').addClass('tbacc-active');
		$(this).parents('.tbacc-tab-content').find('.tbacc-tab-accordion-content').slideToggle();
		$('.tbacc-tab-contents .tbacc-tab-content').each(function(){
			itemId  =  $(this).attr('id');
			if(itemId!=currentEleId){
				$(this).find('.tbacc-tab-accordion-content').slideUp();
			}
		});
	});

	function tbaccTabAccordionToggle(windowWidth){
		if(windowWidth>tbaccIntWidth){
			if($('.tbacc-container-wrap .tbacc-content').hasClass('tbacc-accordions')){
				$('.tbacc-container-wrap .tbacc-content').removeClass('tbacc-accordions');
			}
			if(!$('.tbacc-container-wrap .tbacc-content').hasClass('tbacc-tabs')){
				$('.tbacc-container-wrap .tbacc-content').addClass('tbacc-tabs');
			}

			$('.tbacc-tab-contents .tbacc-tab-content').removeClass('tbacc-active');
			$('.tbacc-tab-contents .tbacc-tab-content:first-child').addClass('tbacc-active');
			
		}
		else{
			if($('.tbacc-container-wrap .tbacc-content').hasClass('tbacc-tabs')){
				$('.tbacc-container-wrap .tbacc-content').removeClass('tbacc-tabs');
			}
			if(!$('.tbacc-container-wrap .tbacc-content').hasClass('tbacc-accordions')){
				$('.tbacc-container-wrap .tbacc-content').addClass('tbacc-accordions');
			}

			$('.tbacc-tab-contents .tbacc-tab-content').removeClass('tbacc-active');
			$('.tbacc-tab-contents .tbacc-tab-content .tbacc-tab-accordion-content').hide();
			$('.tbacc-tab-contents .tbacc-tab-content:first-child').addClass('tbacc-active');
			$('.tbacc-tab-contents .tbacc-tab-content:first-child').find('.tbacc-tab-accordion-content').show();
		}
		$('.tbacc-container-wrap').show();
	}
});