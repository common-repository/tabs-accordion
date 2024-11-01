/********************
Tab Accordion Plugin javascript
*********************/
jQuery(function($){
	$('button.tbacc-add-more-button').click(function(){
		$('form.tbacc-form p.tbacc-error').remove();
		if($('form.tbacc-form input').hasClass('tbacc-error')){
			$('form.tbacc-form input').removeClass('tbacc-error');
		}
		if($('form.tbacc-form .tbacc-form-group').hasClass('tbacc-error')){
			$('form.tbacc-form .tbacc-form-group').removeClass('tbacc-error');
		}
		

		var tabContent = $('.tbacc-tab-accordion-content-wrap .tbacc-tab-accordion-content:first-child').clone().wrap('<div>').parent().html();

		$('.tbacc-tab-accordion-content-wrap').append(tabContent);

		$('.tbacc-tab-accordion-content-wrap .tbacc-tab-accordion-content:last-child input[type="text"]').each(function(){
			$(this).val("");
		});
		$('.tbacc-tab-accordion-content-wrap .tbacc-tab-accordion-content:last-child textarea').each(function(){
			$(this).val("");
		});
		$('.tbacc-tab-accordion-content-wrap .tbacc-tab-accordion-content:last-child  input[type="checkbox"]').each(function(){
			$(this).attr('checked',false);
		});
		

		var fieldId;
		$('.tbacc-tab-accordion-content-wrap .tbacc-tab-accordion-content').each(function(index){
			$(this).find('.tbacc-left-content .tbacc-count').html(index+1);
			updateIndex($(this),index);
		});
		$('.tbacc-tab-accordion-content-wrap .tbacc-tab-accordion-content:last-child .tbacc-dependent-field').slideUp();
		$('.tbacc-remove-button-wrap').fadeIn();
	});	

	$(document).on('click','.tbacc-remove-button-wrap .tbacc-remove-button',function(){
		var remove =  confirm("Are you sure, you want to delete this tab content?");
		if(remove){
			$(this).parents('.tbacc-tab-accordion-content').remove();
			$('.tbacc-tab-accordion-content-wrap .tbacc-tab-accordion-content').each(function(index){
				$(this).find('.tbacc-left-content .tbacc-count').html(index+1);
				updateIndex($(this),index);
			});
			
			if($('.tbacc-remove-button-wrap').length==1){
				$('.tbacc-remove-button-wrap').fadeOut();
			}
		}

	});

	$(document).on('change','input[type="checkbox"].enable-accordion-title',function(){
		$(this).parents('.tbacc-form-group').next().slideToggle();
		if($(this).is(':checked')){
			$(this).parents('.tbacc-form-group').next().find('input').attr('data-required',true);
		}
		else{
			$(this).parents('.tbacc-form-group').next().find('input').attr('data-required',false);
		}
	});

	$('form.tbacc-form').submit(function(){
		var error = false;
		var requiredText;
		var value;
		$(this).find('input[data-required="true"]').each(function(){
			if($(this).val()==''){
				requiredText = $(this).attr('data-required-text');
				if($(this).parents('.tbacc-form-group').find('p.tbacc-error').length==0){
					$(this).parents('.tbacc-form-group').append('<p class="tbacc-message tbacc-error">'+requiredText+' is required.</p>');
				}
				else{
					$(this).parents('.tbacc-form-group').find('p.tbacc-error').html(requiredText+' is required.');
				}

				if(!$(this).hasClass('tbacc-error')){
					$(this).addClass('tbacc-error');
				}
				error = true;
			}
		});

		$(this).find('textarea').each(function(){
			value = $(this).val();
			if($(this).hasClass('add-editor-area')){
				value = $('#'+$(this).attr('id')+'_ifr').contents().find('body').text();
			}

			if(value==''){
				if(!$(this).parents('.tbacc-form-group').hasClass('tbacc-error')){
					$(this).parents('.tbacc-form-group').addClass('tbacc-error');
				}

				requiredText = $(this).parents('.tbacc-form-group').attr('data-required-text');
				if($(this).parents('.tbacc-form-group').find('p.tbacc-error').length==0){
					$(this).parents('.tbacc-form-group').append('<p class="tbacc-message tbacc-error">'+requiredText+' is required.</p>');
				}
				else{
					$(this).parents('.tbacc-form-group').find('p.tbacc-error').html(requiredText+' is required.');
				}
				error = true;
			}
		});

		if(error){
			return false;
		}
	});

	$(document).on('focus','input.tbacc-form-control',function(){
		if($(this).hasClass('tbacc-error')){
			$(this).removeClass('tbacc-error');
		}
		var errorElement = $(this).parents('.tbacc-form-group').find('p.tbacc-error');
		if(errorElement.length>0){
			errorElement.fadeOut('slow',function(){
				errorElement.remove();
			});
		}
	});

	$(document).on('focus','textarea',function(){
		if($(this).parents('.tbacc-form-group').hasClass('tbacc-error')){
			$(this).parents('.tbacc-form-group').removeClass('tbacc-error');
		}
		var errorElement = $(this).parents('.tbacc-form-group').find('p.tbacc-error');
		if(errorElement.length>0){
			errorElement.fadeOut('slow',function(){
				errorElement.remove();
			});
		}
	});

	function updateIndex($this,count){
		var itemName;	
		var itemId;  	
		var posId; 		
		var updatedId;   
		var posName; 	
		var updatedName;
		$this.find('input[type="text"]').each(function(){
			itemName	= $(this).attr('name');
			itemId  	= $(this).attr('id');
			posId 		= itemId.indexOf("[");
			updatedId   = itemId.substr(0,posId)+"["+count+"]";
			posName 	= itemName.indexOf("[");
			updatedName = itemName.substr(0,posName)+"["+count+"]";

			$(this).attr({'name':updatedName,'id':updatedId});
			$(this).parents('.tbacc-form-group').find('label').attr('for',updatedId);
		});
		
		$this.find('input[type="checkbox"]').each(function(){
			itemName	= $(this).attr('name');
			itemId  	= $(this).attr('id');
			posId 		= itemId.indexOf("[");
			updatedId   = itemId.substr(0,posId)+"["+count+"]";
			posName 	= itemName.indexOf("[");
			updatedName = itemName.substr(0,posName)+"["+count+"]";

			$(this).attr({'name':updatedName,'id':updatedId});
			$(this).parents('.tbacc-form-group').find('label').attr('for',updatedId);
		});
		
		$this.find('textarea').each(function(){
			itemName	= $(this).attr('name');
			itemId  	= $(this).attr('id');
			posId 		= itemId.indexOf("[");
			updatedId   = itemId.substr(0,posId)+"["+count+"]";
			posName 	= itemName.indexOf("[");
			updatedName = itemName.substr(0,posName)+"["+count+"]";

			$(this).attr({'name':updatedName,'id':updatedId});
			$(this).parents('.tbacc-form-group').find('label').attr('for',updatedId);
		});
		
	}


	$('select[name="items_per_page"]').change(function(){
		$(this).parents('form').submit();
	});

	$('input[type="range"]').on('input', function(){
		if($(this).parent().find('.tbacc-range-value').length>0){
		    $(this).parent().find('.tbacc-range-value').html(this.value+'px');
		}
	}); 

	$('input[name="same_accordion_settings"').change(function(){
		$('#accordions-setting').slideToggle();
	});
});