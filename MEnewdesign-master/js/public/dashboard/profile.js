$(document).ready(function () {
//Change password related js   
    $('#changePasswordForm').validate({
        rules: {
            password: {
                required: true,
                minlength: 6
            },
            confirmPassword: {
                required: true,
                minlength: 6
            }
        },
        messages: {
            password: {
                required: "Password is required",
                minlength: "Your password must be at least 6 characters long"
            },
            confirmPassword: {
                required: "Confirm Password is required",
                minlength: "Your Confirm Password must be at least 6 characters long"
            }
        },
        submitHandler: function (form) {

            if ($(form).valid())
            {
                if ($("#password").val() != $("#confirmPassword").val()) {
                    $("#passwordsError").text("Confirm password must be equal to the password");
                  
                } else {
                    $("#passwordsError").text(" ");
                    form.submit();
                }
            }
            return false;


        }
    });

    //User Bank Details  related js    
    $('#bankDetailsForm').validate({
        rules: {
            accountNumber: {
                required: true,
                number: true
            },
            ifsccode: {
                required: true,
                alphanumeric: true
            },
            accountName: {
                required: true,
                accountName: true
            },
            bankName: {
                required: true,
                bankName: true
            },
            branch: {
                required: true,
                maxlength: 100
            }
        },
        messages: {
            accountNumber: {
                required: "Please enter account number"
            },
            ifsccode: {
                required: "Please enter IFSC Code"
            },
            accountName: {
                required: "Please enter account name"
            },
            bankname: {
                required: "Please enter bank name"
            },
            branch: {
                required: "Please enter branch"
            }
        },
        submitHandler: function (form) {
            if($(form).valid()){
               form.submit();
           }
        }
    });
    
    $("#frmAppSettings").validate({
        rules: {
            appName: {required: true},
            callbackUrl: {required: true, url: true}

        },
        messages: {
            appName: {required: "Please enter the Application Name"},
            callbackUrl: {required: "Please enter valid url"}

        },
        errorPlacement: function (error, element) {
            error.insertAfter(element);

        },
        errorElement: "span",
    });
    
    
    jQuery.validator.addMethod("accountName", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9\s ]+$/.test(value);
    }, 'Please enter valid name with letters,numbers,space(s) only');

    jQuery.validator.addMethod("alphanumeric", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
    }, 'Letters and numbers are allowed');

    jQuery.validator.addMethod("bankName", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9&\s ]+$/.test(value);
    }, 'Please enter valid name with letters,numbers,&,space(s) only');

    jQuery.validator.addMethod("mobile", function (value, element) {
        if (value == '')
            return true;
        var regPhonecode = /^(\d+)[ \-]?(\d+)$/; // /^(\+)?\s{0,1}[0-9]\d*$/;
        return regPhonecode.test(value);
    }, "Please enter valid Phone Number");
    jQuery.validator.addMethod("weburl", function (value, element) {
        // allow http://,https://,www
        return this.optional(element) || /(https?:\/\/(?:www\.|(?!www))[^\s\.]+\.[^\s]{2,}|www\.[^\s]+\.[^\s]{2,})/.test(value);
    }, 'Please enter a valid url.');
    //Personal  Details  related js   
    $('#personalDetails').validate({
        rules: {
            username: {
                required: true,
                minlength: 6,
                maxlength: 50
            },
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            mobile: {
                required: true,
                number: true,
                minlength: 10,
                maxLen: 10
            },
            pincode: {
                number: true,
                minlength: 6,
                maxlength: 8
            },
            facebooklink: {
                weburl: true 
            },
            twitterlink: {
                weburl: true 
            },
            googlepluslink: {
                weburl: true 
            },
            linkedinlink: {
                weburl: true 
            }
        },
        messages: {
            username: {
                required: "Please enter User Name"
            },
            name: {
                required: "Please enter Name"
            },
            email: {
                required: "Please enter valid Email."
            }, 
            mobile: {
                required: "Please enter Mobile Number."
            }, 
            pincode: {
//                required: "Please enter Pincode."
            },
            facebooklink: {
                 weburl: "Enter valid url"
            },
            twitterlink: {
                 weburl: "Enter valid url"
            },
            googlepluslink: {
                 weburl: "Enter valid url"
            },linkedinlink: {
                 weburl: "Enter valid url"
            } 
            
            
        },
        submitHandler: function (form) {
           if($(form).valid()){
                  form.submit();
               }
           }
    });
    jQuery.validator.addMethod("maxLen", function (value, element, param) {
        if ($(element).val().length > param) {
            return false;
        } else {
         return true;
        }
    }, "Mobile number cannot be more than 10 characters");

    //company Details  related js    
    $('#companyDetails').validate({
        rules: {
            companyname: {
                required: true
            },
            email: {
                email: true
            },
            locality: {
               required: true, 
            },
            phone: {
                number: true,
                maxlength: 10
            },
            url: {
                weburl: true
            }
        },
        messages: {
            companyname: {
                required: "Please enter Company name"
            },
            email: {
                required: "Please enter valid Email."
            }, 
            locality: {
               required: "Please enter Locality." 
            },
            phone: {
                required: "Please enter Phone."
            },
            url: {
                 weburl: "Enter valid url"
            } 
        },
       submitHandler: function (form) {
           if($(form).valid()){
                  form.submit();
               }
           }    
    });


     $('.localityAutoComplete').change(function (e) {
       if($('#locality').val() == ''){
            $('#countryId').val('0');
            $('#stateId').val('0');
            $('#cityId').val('0');
       }
         initialize();
});  

    $('.localityAutoComplete').each(function () {
        initialize();
    });
        
});  

   $('#alertMessage').delay(5000).fadeOut(400); // <-- time in milliseconds
   $('#bankDetailsMessages').delay(5000).fadeOut(400); 
   $('#companyDetailsMessage').delay(5000).fadeOut(400);
   $('#personalDetailsMessage').delay(5000).fadeOut(400); 
   $('#passwordSuccess').delay(5000).fadeOut(400); 

// show image js
 $("#picture").change(function (e) {
        readURL(this, 'picShow');
    });
    function readURL(input, showWhere) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#' + showWhere).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#username").blur(function(){
    var userName = $('#username').val();
    $.ajax({
        url   : api_checkUserNameExist,
        type  : 'POST',
        data: {
            username:userName          
        },
        headers: {
            'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'
        }, 
        success : function (result) {
            var status=result.response.userNameStatus;
            var total=result.response.total;
            var msg=result.response.messages;
            if(total == 1){ 
            $("#userSuccessMessage").text(" ");
            $("#userErrorMessage").text(msg);
            $("#userErrorMessage").css('color','red');
            }
            if(total == 0){ 
            $("#userErrorMessage").text(" ");
            $("#userSuccessMessage").text(msg);
            $("#userSuccessMessage").css('color','green');
            $("#userSuccessMessage").css('display','block').delay(5000).fadeOut('slow');
            }
        },
        error: function (result) {
          //   alert('something Went Wrong');
        }
    }); 
});

/* Functions for Filling google address */
var placeSearch, autocomplete;
var componentForm = {
    neighborhood: 'long_name',
    premise: 'long_name',
    route: 'long_name',
    sublocality_level_1: 'long_name',
    sublocality_level_2: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'long_name',
    country: 'long_name'
};

function initialize() { 
    // Create the autocomplete object, restricting the search
    // to geographical location types.

    var map = new google.maps.Map(document.getElementById('locality'), {
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var input = (document.getElementById('locality'));

    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var autocomplete = new google.maps.places.Autocomplete((input));
    
    // When the user selects an address from the dropdown,
    // populate the address fields in the form.
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
        var place = autocomplete.getPlace();
        fillInAddress(place);
    });

}
   
function fillInAddress(place) { 
    for (var component in componentForm) {
        if ($.inArray(component, ignoreArray) > -1) {
            document.getElementById(component).value = '';
            document.getElementById(component).disabled = false;
        }
    }
    if (place.length == 0) {
        return;
    }
    var ignoreArray = ['route', 'sublocality_level_2', 'premise'];
    var adddressObj = {};
    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            adddressObj[addressType] = val;
        }
    }
    var state = '';
    var city = '';
    var country = adddressObj.country;
    
    if (adddressObj.administrative_area_level_1) {
        state = adddressObj.administrative_area_level_1;
    }

    if (adddressObj.locality) {
        city = adddressObj.locality;
    }else if (state != '') {
        city = state;
    }
    
    var data = {};
    data.keyWord = country;
    url = api_countrySearch;
    $.ajax({
        method: 'GET',
        url: url,
        data: data,
        headers: {'Content-Type': 'application/x-www-form-urlencoded',
            'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}
    }).success(function (data) {
        if (data.response.total > 0) {
            var countryId = data.response.countryList[0].id;
            $('#countryId').val(countryId);
            var data = {};
            data.countryId = countryId;
            if (state != '') {
                data.keyWord = state;
                url = api_stateSearch;
                $.ajax({
                    method: 'GET',
                    url: url,
                    data: data,
                    headers: {'Content-Type': 'application/x-www-form-urlencoded',
                        'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}
                }).success(function (data) {
                    if (data.response.total > 0) {
                        var stateId = data.response.stateList[0].id;
                        $('#stateId').val(stateId);
                        var data = {};
                        data.countryId = countryId;
                        data.stateId = stateId;
                        data.major = 0;
                        if (city != '') {
                            data.keyWord = city;
                            url = api_citySearch;
                            $.ajax({
                                method: 'GET',
                                url: url,
                                data: data,
                                headers: {'Content-Type': 'application/x-www-form-urlencoded',
                                    'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'}
                            }).success(function (data) {
                                if (data.response.total > 0) {
                                    var cityId = data.response.cityList[0].id;
                                    $('#cityId').val(cityId);
                                } else if(data.response.total == 0 && city != state){
                                    cityId = city;
                                    $('#cityId').val(cityId);
                                }
                                else{
                                    $('#cityId').val('0');
                                }
                                
                            }).error(function (data) {
                                alert('Something went wrong please try again');
                            });
                        } else {
                            $('#cityId').val('0');
                        }
                        
                    } else if(data.response.total == 0){
                        stateId = state;
                        $('#stateId').val(stateId);
                        if(city != ""){
                            $('#cityId').val(city);
                        }else{
                            $('#cityId').val('0');
                        }
                    } else {
                        //alert('No state found');
                        alert(data.response.messages);
                    }
                }).error(function (data) {
                    alert('Something went wrong please try again');
                });
            } else {
                $('#stateId').val('0');
            }
            
        }else {
            alert('No Country');
        }
    }).error(function (data) { 
        alert('Something went wrong please try again');
    });
    
}// [END region_fillform]
$(document).on('keypress','#personalDetails',function(evt){
    var keywCode = window.event ? event.keyCode : evt.which;
   if(keywCode == 13){
      evt.preventDefault();
      if(evt.target.name == 'locality'){
        initialize();
      }
   }
 });
 

