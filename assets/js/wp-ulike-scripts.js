function likeThis(postId, n1, n2) {
    if (postId != '') {
        jQuery('#wp-ulike-' + postId + ' .counter').html('<a class="loading"></a><span class="count-box">...</span>');
		jQuery.ajax({
		  type:'POST',
		  url: ulike_obj.ajaxurl,
          data:{
            action:'ulikeprocess',
            id: postId
          },
		  success: function(value) {
			if(n1+n2 == 1){
				jQuery('#wp-ulike-' + postId + ' .counter').html("<a onclick='likeThis("+postId+",1,1)' class='text'>" + ulike_obj.text_after_like + "</a><span class='count-box'>"+value+"</span>");
			}
			else if(n1+n2 == 2){
				if(ulike_obj.return_initial_after_unlike == 1){
					if(ulike_obj.button_type == 'image'){
						jQuery('#wp-ulike-' + postId + ' .counter').html("<a onclick='likeThis("+postId+",1,0)' class='image'></a><span class='count-box'>"+value+"</span>");	
					}
					else if(ulike_obj.button_type == 'text'){
						jQuery('#wp-ulike-' + postId + ' .counter').html("<a onclick='likeThis("+postId+",1,0)' class='text'>" + ulike_obj.button_text + "</a><span class='count-box'>"+value+"</span>");	
					}
				}
				else{
					jQuery('#wp-ulike-' + postId + ' .counter').html("<a onclick='likeThis("+postId+",1,0)' class='text'>" + ulike_obj.text_after_unlike + "</a><span class='count-box'>"+value+"</span>");	
				}
			}
			else if(n1+n2 == 3){
				jQuery('#wp-ulike-' + postId + ' .counter').html("<a class='text user-tooltip' title='Already Voted'>" + ulike_obj.text_after_like + "</a><span class='count-box'>"+value+"</span>");			
			}
			else if(n1+n2 == 4){
				if(ulike_obj.button_type == 'image'){
					jQuery('#wp-ulike-' + postId + ' .counter').html("<a class='image' title='You Liked This'></a><span class='count-box'>"+value+"</span>");	
				}
				else if(ulike_obj.button_type == 'text'){
					jQuery('#wp-ulike-' + postId + ' .counter').html("<a class='text' title='You Liked This'>" + ulike_obj.button_text + "</a><span class='count-box'>"+value+"</span>");	
				}
			}
		  }
		});			
    }
}
function likeThisComment(commentId, n1, n2) {
    if (commentId != '') {
        jQuery('#wp-ulike-comment-' + commentId + ' .counter').html('<a class="loading"></a><span class="count-box">...</span>');
		jQuery.ajax({
		  type:'POST',
		  url: ulike_obj.ajaxurl,
          data:{
            action:'ulikecommentprocess',
            id: commentId
          },
		  success: function(value) {
			if(n1+n2 == 1){
				jQuery('#wp-ulike-comment-' + commentId + ' .counter').html("<a onclick='likeThisComment("+commentId+",1,1)' class='text'>" + ulike_obj.text_after_like + "</a><span class='count-box'>"+value+"</span>");
			}
			else if(n1+n2 == 2){
				if(ulike_obj.return_initial_after_unlike == 1){
					if(ulike_obj.button_type == 'image'){
						jQuery('#wp-ulike-comment-' + commentId + ' .counter').html("<a onclick='likeThisComment("+commentId+",1,0)' class='image'></a><span class='count-box'>"+value+"</span>");
					}
					else if(ulike_obj.button_type == 'text'){
						jQuery('#wp-ulike-comment-' + commentId + ' .counter').html("<a onclick='likeThisComment("+commentId+",1,0)' class='text'>" + ulike_obj.button_text + "</a><span class='count-box'>"+value+"</span>");
					}
				}
				else{
					jQuery('#wp-ulike-comment-' + commentId + ' .counter').html("<a onclick='likeThisComment("+commentId+",1,0)' class='text'>" + ulike_obj.text_after_unlike + "</a><span class='count-box'>"+value+"</span>");
				}
			}
			else if(n1+n2 == 3){
				jQuery('#wp-ulike-comment-' + commentId + ' .counter').html("<a class='text user-tooltip' title='Already Voted'>" + ulike_obj.text_after_like + "</a><span class='count-box'>"+value+"</span>");			
			}
			else if(n1+n2 == 4){
				if(ulike_obj.button_type == 'image'){
					jQuery('#wp-ulike-comment-' + commentId + ' .counter').html("<a class='image'></a><span class='count-box'>"+value+"</span>");
				}
				else if(ulike_obj.button_type == 'text'){
					jQuery('#wp-ulike-comment-' + commentId + ' .counter').html("<a class='text'>" + ulike_obj.button_text + "</a><span class='count-box'>"+value+"</span>");
				}
			}
		  }
		});			
    }
}
function likeThisActivity(activityID, n1, n2) {
    if (activityID != '') {
        jQuery('#wp-ulike-activity-' + activityID + ' .counter').html('<a class="loading"></a><span class="count-box">...</span>');
		jQuery.ajax({
		  type:'POST',
		  url: ulike_obj.ajaxurl,
          data:{
            action:'ulikebuddypressprocess',
            id: activityID
          },
		  success: function(value) {
			if(n1+n2 == 1){
				jQuery('#wp-ulike-activity-' + activityID + ' .counter').html("<a onclick='likeThisActivity("+activityID+",1,1)' class='text'>" + ulike_obj.text_after_like + "</a><span class='count-box'>"+value+"</span>");
			}
			else if(n1+n2 == 2){
				if(ulike_obj.return_initial_after_unlike == 1){
					if(ulike_obj.button_type == 'image'){
						jQuery('#wp-ulike-activity-' + activityID + ' .counter').html("<a onclick='likeThisActivity("+activityID+",1,0)' class='image'></a><span class='count-box'>"+value+"</span>");	
					}
					else if(ulike_obj.button_type == 'text'){
						jQuery('#wp-ulike-activity-' + activityID + ' .counter').html("<a onclick='likeThisActivity("+activityID+",1,0)' class='text'>" + ulike_obj.button_text + "</a><span class='count-box'>"+value+"</span>");	
					}
				}
				else{
					jQuery('#wp-ulike-activity-' + activityID + ' .counter').html("<a onclick='likeThisActivity("+activityID+",1,0)' class='text'>" + ulike_obj.text_after_unlike + "</a><span class='count-box'>"+value+"</span>");	
				}
			}			
			else if(n1+n2 == 3){
				jQuery('#wp-ulike-activity-' + activityID + ' .counter').html("<a class='text user-tooltip' title='Already Voted'>" + ulike_obj.text_after_like + "</a><span class='count-box'>"+value+"</span>");			
			}
			else if(n1+n2 == 4){
				if(ulike_obj.button_type == 'image'){
					jQuery('#wp-ulike-activity-' + activityID + ' .counter').html("<a class='image'></a><span class='count-box'>"+value+"</span>");	
				}
				else if(ulike_obj.button_type == 'text'){
					jQuery('#wp-ulike-activity-' + activityID + ' .counter').html("<a class='text'>" + ulike_obj.button_text + "</a><span class='count-box'>"+value+"</span>");	
				}			
			}
		  }
		});			
    }
}