jQuery(document).ready(function($){

	function evaluate(){
		var item = $(this);
		var relatedItem = $('.checktoshow');
	   
		if(item.is(":checked")){
			relatedItem.fadeIn();
		}else{
			relatedItem.fadeOut();   
		}
	}

	$('#wp_ulike_style').click(evaluate).each(evaluate);
	
    $('.my-color-field').wpColorPicker();
});