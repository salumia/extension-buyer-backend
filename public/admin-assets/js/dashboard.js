var apiBaseUrl = 'http://127.0.0.1:8000/api';
//var apiBaseUrl = 'http://trigvent.com/extbuyer/api';
$( document ).ready(function() { 

	$('#back-user-list').on('click',function(){
        window.history.back();
    });
    
    $(document).on('click','.model_close',function(){ 
    	$("#rejectStatus-form")[0].reset();
        $('#basicModal').hide();
        /*$('.getLoder').html('');
       	$('#extensionId').show();*/
    });	

    /*$('.model_close').click(function(){
        $("#rejectStatus-form")[0].reset();
        $('#basicModal').hide();
        $('.getLoder').html('');
       	$('#extensionId').show();
    })*/

    setTimeout(function(){
		$(".alert.alert-success").hide();
	}, 2000);

	setTimeout(function(){
		$(".alert.alert-danger").hide();
	}, 2000);

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
	                    url: apiBaseUrl+'/validateUserEmailCheck',
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
			  required: "FirstName is required."
			},
			lastName: {
			  required: "LastName is required."
			},
			email: {
			  required: "Email is required.",
			  remote: "Email already registered."
			},
			phone_no: {
			  required: "Phone No is required.",
			  number: "Number must be in the integer."
			},
			address_line: {
			  required: "Address is required."
			},
			zip_code: {
			  required: "Zipcode is required."
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
			  required: "FirstName is required."
			},
			lastName: {
			  required: "LastName is required."
			},
			phone_no: {
			  required: "Phone is required.",
			  number: "Number must be in the integer."
			},
			address_line: {
			  required: "Address is required."
			},
			zip_code: {
			  required: "Zipcode is required."
			}
		  },
		
		submitHandler: function() {            
			form.submit();
		}
	});
    
    $("#admin-change-password").validate({
		rules: {
			password: {
				required: true,
				minlength:6,
				maxlength:15
			},
			confirmpassword:{
				required: true,
				minlength:6,
				maxlength:15,
				equalTo: "#admin-password"
			}
		},
		  messages: {
		   
			confirmpassword: {
			  required: "Confirm Password is required.",
			  equalTo: "Password is not matched."
			}
		  },
		
		submitHandler: function() {            
			form.submit();
		}
	});

    $("#profile-admin-edit").validate({
        rules: {
            
            email:{
                required: true,
                email:true,
                    remote: {
	                    type: 'POST',
	                    url: apiBaseUrl+'/validateAdminEmailCheck',
	                    data: {
	                        'id': function() {
	                           return $('#profile-admin-edit.email').val();
	                        }
	                    },
	                    dataType: 'json'
	                }
            }
        },
          messages: {
          
            email: {
              required: "Email is required.",
              remote: "Email already registered.",
            }
          },
        
        submitHandler: function() {            
            form.submit();
        }
    });
    
    $("#add-category-form").validate({
		rules: {
			categoryName: {
				required: true
			},
			status: {
				required: true
			}
		},
		  messages: {
			categoryName: {
			  required: "Category Name is required."
			},
			status: {
			  required: "Status is required."
			}
		  },
		
		submitHandler: function() {            
			form.submit();
		}
	});

	$("#edit-category-form").validate({
		rules: {
			categoryName: {
				required: true
			},
			status: {
				required: true
			}
		},
		  messages: {
			categoryName: {
			  required: "Category Name is required."
			},
			status: {
			  required: "Status is required."
			}
		  },
		
		submitHandler: function() {            
			form.submit();
		}
	});
	
	
    
    
    $("#rejectStatus-form").validate({
		rules: {
			reject_reason: {
				required: true
			}
		},
		  messages: {
			reject_reason: {
			  required: "Reason is required.",
			}
		  },
		
		submitHandler: function() {  
		    //alert();
			reasonStatus();
			return false;
		}
	});

	
	$(document).on('change','#extensionId',function(){ 
		var $this = $(this);
		 //var status =$(this).("option:selected" )[0].getAttribute("value")
		var status =$( "option:selected",this ).val()
		var extensionId =$(this).attr('extension_id');
		if(status==5){
		    $('#basicModal').show();
		    $('#extId').val(extensionId);
		    return false;
		}else{
			$this.closest('#extensionId').hide();
			$this.closest('td').append('<div class="loader"></div>');
    		$.ajax({
                type: 'POST',
                url: apiBaseUrl+'/extensionStatus/'+extensionId,
                data: {status:status},
                dataType: 'json',
                success: function(response){
                    console.log(response);
                    if(response.status==200){
    	                  $('.alert-success').find('p').text(response.message);
        	               setTimeout(function(){
        	                    $('.getLoder').html('');
                                 	setTimeout(function(){
			                    	$('#extensionId').show();
			                    	location.reload();
		                    	}, 3000);
                            }, 1000);
                    	
                    }else{
                    	$('.alert-danger').find('p').text(response.message);
                    }
                },error: function(jqXHR, textStatus) {
                   console.warn(textStatus);
                },
            });
		}
		
		
	});
    
    
    
    

	var getSearchParameter =   getUrlParameter('search');

	if(getSearchParameter != ''){
		$('#search-tab').val(getSearchParameter);
	}
	
	var getSearchCategoryParameter =   getUrlParameter('category');

	if(getSearchCategoryParameter != ''){
		$('#search-category').val(getSearchCategoryParameter);
	}

	var getSearchEmailParameter =   getUrlParameter('email');

	if(getSearchEmailParameter != ''){
		$('#search-email').val(getSearchEmailParameter);
	}
	
	var getSearchExtensionParameter =   getUrlParameter('extension');

	if(getSearchExtensionParameter != ''){
		$('#search-extension').val(getSearchExtensionParameter);
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


 function reasonStatus(){
    var reason=$('#reject_reason').val();
       var extId=$('#extId').val();
       var status=5;
        $.ajax({
            type: 'POST',
            url: apiBaseUrl+'/extensionStatusReject/'+extId,
            data: {status:status,reason:reason},
            dataType: 'json',
            success: function(response){
                //console.log(response);
                if(response.status==200){
                	$('.getLoder').html('');
        	      	$('#extensionId').show();
	                $('.msg').html('<div class="alert alert-success"><p>'+response.message+'</p></div>');
	                setTimeout(function(){
	                    $("#rejectStatus-form")[0].reset();
	                    $('#basicModal').hide();
	                    	//location.reload();
	                    	setTimeout(function(){
			                    
			                    	location.reload();
		                    }, 3000);
                    }, 1000);
                	
                }else{
                    
                  $('.alert alert-info').text('sorry not updated');
                  $("#rejectStatus-form")[0].reset();
                }
            },error: function(jqXHR, textStatus) {
               console.warn(textStatus);
            },
        });
            
        
        
}