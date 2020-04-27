var apiBaseUrl = 'http://127.0.0.1:8000/api';
$( document ).ready(function() { 
	
	$('#back-user-list').on('click',function(){
        window.history.back();
    }) 

    $('#country_id').on('change',function(){
    	$id=$(this).val();
    	$('#state_id').find('option').remove();
    	$('#city_id').find('option').remove();
    	$.ajax({
            type: "GET",
            url: apiBaseUrl+"/getstate/"+$id,
            dataType: 'json',
        }).done(function(response) {
        	html ='<option value="">Please Select State</option>';
            if (response.status == 200) {
              	response.state.forEach(function(item, index){
           		 	html +=`<option value="`+item.id+`">`+item.name+`</option>`;
       		 	});
       		 	$('#state_id').append(html);
            } 
        });
    	
    });

    $('#state_id').on('change',function(){
    	$id=$(this).val();
    	$('#city_id').find('option').remove();
    	$.ajax({
            type: "GET",
            url: apiBaseUrl+"/getcity/"+$id,
            dataType: 'json',
        }).done(function(response) {
        	html ='<option value="">Please Select city</option>';
            if (response.status == 200) {
              	response.city.forEach(function(item, index){
           		 	html +=`<option value="`+item.id+`">`+item.name+`</option>`;
       		 	});
       		 	$('#city_id').append(html);
            } 
        });
    	
    })

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


	var getSearchParameter =   getUrlParameter('search');

	if(getSearchParameter != ''){
		$('#search-tab').val(getSearchParameter);
	}

	var getSearchEmailParameter =   getUrlParameter('email');

	if(getSearchEmailParameter != ''){
		$('#search-email').val(getSearchEmailParameter);
	}

});	

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};