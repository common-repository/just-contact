jQuery(document).ready( function($) {

	$("#ajax-link").click( function() {
     $('#ajax-link').prop('disabled', true);
     $('#updtMsg').show();
//alert($("#Success_message").val());


		var data = {
			action: 'test_response',
                        post_var: 'this will be echoed back',
                        emailId:$('#email_id').val(),
                        s_msg:$("#Success_message").val(),
                        f_msg:$("#fail_message").val(),
                        
                       
		};
		// the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file
	 	$.post(the_ajax_script.ajaxurl, data, function(data1) {
	 		 $('#ajax-link').prop('disabled', false);
			 $('#updtMsg').hide();
	 	});
	 	return false;
	});
});