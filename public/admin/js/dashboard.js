var apiBaseUrl = 'http://127.0.0.1:8000/api';
$( document ).ready(function() { 
	$("#add-user-form").validate({
		rules: {
			firstName: {
				required: true
			},
			lastName: {
				required: true
			},
			email: {
				required: true,
				email: true,
					remote: {
	                    type: 'POST',
	                    url: apiBaseUrl+'/validateUserEmailAdd',
	                    data: {
	                        'id': function() {
	                           return $('#add-user-form input.email').val();
	                        }
	                    },
	                    dataType: 'json'
	                }
			},
			phone_no: {
				required: true,
				number: true,
				minlength:10,
				maxlength:12
			},
			
			address_line:{
				required: true
			},
			zip_code:{
				required: true,
				number: true
			}

		},
		  messages: {
		   
			firstName: {
			  required: "This field is required."
			},
			lastName: {
			  required: "This field is required."
			},
			email: {
			  required: "This field is required.",
			  remote: "Email already registered."
			},
			phone_no: {
			  required: "This field is required.",
			  number: "Number must be in the integer."
			},
			address_line: {
			  required: "This field is required."
			},
			zip_code: {
			  required: "This field is required."
			}
		  },
		
		submitHandler: function() {            
			form.submit();
		}
	});

	$("#edit-user-form").validate({
		rules: {
			firstName: {
				required: true
			},
			lastName: {
				required: true
			},
			phone_no: {
				required: true,
				number: true,
				minlength:10,
				maxlength:12
			},
			
			address_line:{
				required: true
			},
			zip_code:{
				required: true,
				number: true
			}

		},
		  messages: {
		   
			firstName: {
			  required: "This field is required."
			},
			lastName: {
			  required: "This field is required."
			},
			phone_no: {
			  required: "This field is required.",
			  number: "Number must be in the integer."
			},
			address_line: {
			  required: "This field is required."
			},
			zip_code: {
			  required: "This field is required."
			}
		  },
		
		submitHandler: function() {            
			form.submit();
		}
	});

});	