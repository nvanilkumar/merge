var timeout = null;
$("#myModal").on("show.bs.modal", function (e) {
    var link = $(e.relatedTarget);
    $(this).find(".modal-content").load(link.attr("href"));
});
$(document).ready(function () {
    jQuery.validator.addMethod("noSpace", function (value, element) {
        return value.indexOf(" ") < 0 && value != "";
    }, "No space please and don't leave it empty");
    $('body').on('click', '.form-submiters', function () {
        $('#message').html('');
    });
    $("#add-contact-form").validate({
        rules: {
            fullname: {required: true
            },
            email: {
                required: true,
                email: true
            },
            message: {required: true}
        },
        messages: {
            fullname: {required: var_fieldnotblank},
            email: {
                required: var_fieldnotblank,
                email: var_validemail
            },
            message: {required: var_fieldnotblank}
        },
        submitHandler: function (form) {
            $.ajax({
                type: $(form).attr('method'),
                url: $(form).attr('action'),
                data: $(form).serialize(),
                dataType: 'json'
            })
                    .done(function (response) {

                        if (response.status == "success") {
                            $('#add-contact-form')[0].reset();
                            $('#contact-messages').html('<div class="alert alert-success" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + response.message + '</div>');
                            timeout = setTimeout(function () {
                                $('#newsletter-messages').html('');
                            }, 5000);
                            //flashmessage(response.message, response.status);
                        }
                        else {
                            $('#add-contact-form')[0].reset();
                            $('#contact-messages').html('<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + response.message + '</div>');
                            timeout = setTimeout(function () {
                                $('#newsletter-messages').html('');
                            }, 5000);
                            // flashmessage(response.message,  response.status);
                        }

                    });
            return false;
        },
        errorPlacement: function (error, element) {

            error.appendTo(element.parent());
            //error.prependTo(element.parent());
        },
        invalidHandler: function (form, validation) {
            $('div.form-error').remove();
        }
    });
    $('body').on('click','#close-enroll-model',function(){
        $('#add-contact-form')[0].reset();
    });
    $("#form-newsletter").validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required: var_fieldnotblank,
                email: var_validemail
            }
        },
        submitHandler: function (form) {
            $.ajax({
                type: $(form).attr('method'),
                url: $(form).attr('action'),
                data: $(form).serialize(),
                dataType: 'json'
            })
                    .done(function (response) {

                        if (response.status == "success") {
                            $('#form-newsletter')[0].reset();
                            $('#newsletter-messages').html('<div class="alert alert-success" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + response.message + '</div>');
                            timeout = setTimeout(function () {
                                $('#newsletter-messages').html('');
                            }, 5000);
                            //flashmessage(response.message, response.status);
                        }
                        else {
                            $('#form-newsletter')[0].reset();
                            $('#newsletter-messages').html('<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + response.message + '</div>');
                            timeout = setTimeout(function () {
                                $('#newsletter-messages').html('');
                            }, 5000);
                            // flashmessage(response.message,  response.status);
                        }

                    });
            return false;
        },
        errorPlacement: function (error, element) {

            error.appendTo(element.parent().parent());
            //error.prependTo(element.parent());
        },
        invalidHandler: function (form, validation) {
            $('div.form-error').remove();
        }
    });
    $("#member-logiform").validate({
        rules: {
            _password: {required: true
            },
            _email: {
                required: true,
                email: true
            }
        },
        messages: {
            _password: {required: var_fieldnotblank},
            _email: {
                required: var_fieldnotblank,
                email: var_validemail
            }
        },
        errorPlacement: function (error, element) {

            error.appendTo(element.parent());
            //error.prependTo(element.parent());
        },
        invalidHandler: function (form, validation) {
            $('div.form-error').remove();
        }
    });
    $("#student-reg-form").validate({
        rules: {
            fname: {required: true
            },
            lname: {required: true
            },
            password: {
                required: true,
                minlength: 3,
                maxlength: 25
            },
            cpassword: {
                required: true,
                equalTo: "#password-id"
            },
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            fname: {required: var_fieldnotblank},
            lname: {required: var_fieldnotblank
            },
            password: {
                required: var_fieldnotblank,
                minlength: passwordlimitmin3,
                maxlength: passwordlimit4
            },
            cpassword: {
                required: var_fieldnotblank,
                equalTo: passwordmismatch
            },
            email: {
                required: var_fieldnotblank,
                email: var_validemail
            }
        },
        errorPlacement: function (error, element) {

            error.appendTo(element.parent());
            //error.prependTo(element.parent());
        },
        invalidHandler: function (form, validation) {
            $('div.form-error').remove();
        }
    });
    $("#forgotpassword-form").validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required: var_fieldnotblank,
                email: var_validemail
            }
        },
        errorPlacement: function (error, element) {

            error.appendTo(element.parent());
            //error.prependTo(element.parent());
        },
        invalidHandler: function (form, validation) {
            $('div.form-error').remove();
        }
    });
    $("#resetpassword-form").validate({
        rules: {
            newpassword: {
                required: true,
                minlength: 3,
                maxlength: 25
            },
            confirmpassword: {
                required: true,
                equalTo: "#newpassword"
            }
        },
        messages: {
            newpassword: {
                required: var_fieldnotblank,
                minlength: passwordlimitmin3,
                maxlength: passwordlimit4
            },
            confirmpassword: {
                required: var_fieldnotblank,
                equalTo: passwordmismatch
            }
        }
    });

    $('body').on('click', '#enrollNowTraining', function () {
        clearTimeout(timeout);
        $('#enrollNowMessagesTraining').html('By enrolling to this Training you are agreeing to receive emails and remainders about this Training.');
    });
    $('body').on('click', '#enroll-to-workshop', function () {

//$('#enrollNow').modal('hide');
        var wid = $(this).attr('data-wid');
        //alert(enroll_to_workshop_url);

        $.ajax({
            type: 'POST',
            url: enroll_to_workshop_url,
            data: {'wid': wid},
            dataType: 'json'
        })
                .done(function (response) {

                    if (response.status == "success") {
                        $('#enrollNowMessages').html(response.message);
                        timeout = setTimeout(function () {
                            $('#enrollNow').modal('hide');
                        }, 5000);
                        //flashmessage(response.message, response.status);
                    }
                    else {
                        $('#enrollNowMessages').html(response.message);
                        // setTimeout(function () {
                        //     $('#newsletter-messages').html('');
                        // }, 5000);

                        // flashmessage(response.message,  response.status);
                    }

                });
    });
    $("#userinfo-form").validate({
        rules: {
            fname: {required: true,
            },
            lname: {required: true,
            },
            password: {
                minlength: 3,
                maxlength: 25
            },
            cpassword: {
                equalTo: "#password"
            },
            email: {required: true,
                email: true
            }
        },
        messages: {
            fname: {required: "Please enter first name",
            },
            lname: {required: "Please enter last name",
            },
            password: {
                minlength: "Password must contain atleast 3 characters",
                maxlength: "Password must not exceed more than 25 characters"
            },
            cpassword: {
                equalTo: "Password mismatch"
            },
            email: {required: "Please enter email address"
            }

        },
        submitHandler: function (form) {
            form.submit();
        },
        errorPlacement: function (error, element) {
            if (element.attr("name") == "pageLink") {
                error.appendTo(element.parent().parent());
            } else {
                error.appendTo(element.parent());
            }

            //error.prependTo(element.parent());
        },
        invalidHandler: function (form, validation) {
            $('div.form-error').remove();
        }
    });

    var videodiv = $('#video-div').wrap('<div/>').parent().html();
    //    jwplayer('video-example1').setup({
//flashplayer: '{{ asset('assets / js / jwplayer / player.swf') }}',
    //       file: 'https://www.youtube.com/watch?v=-5wpm-gesOY',
    //      height: '100',
    //     width: '200',
    //'playlist.position': 'right',
    //'playlist.size': 80
//});
    //      jwplayer('video-example2').setup({
//flashplayer: '{{ asset('assets / js / jwplayer / player.swf') }}',
//        file: '{{ asset('assets / videos / magento.mp4') }}',
//        height: '100',
    //       width: '200',
    //'playlist.position': 'right',
    //'playlist.size': 80
//});
    $('body').on('click', '#add-video-button', function () {
        $('#video-container-div').append(videodiv);
    });

    $("#user-create-training-form").validate({
        rules: {
            trainingTitle: {
                required: true
            },
            department: {
                required: true
            },
            public: {
                required: true
            },
            assesment: {
                required: true
            }

        },
        messages: {
            trainingTitle: {
                required: var_fieldnotblank
            },
            department: {
                required: var_fieldnotblank
            },
            public: {
                required: var_fieldnotblank
            },
            assesment: {
                required: var_fieldnotblank
            }
        },
        errorPlacement: function (error, element) {
            if (element.attr("class") == "newplace") {
                error.appendTo(element.parent().parent());
            } else {
                error.appendTo(element.parent());
            }
            //error.prependTo(element.parent());
        },
        invalidHandler: function (form, validation) {
            $('div.form-error').remove();
        }
    });


   var  enrollwrkform = $("#enroll-workshop-form").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            fname: {
                required: true
            },
            lname: {
                required: true
            },
            phone: {
                required: true
            }
        },
        messages: {
            email: {
                required: var_fieldnotblank,
                email: var_validemail
            },
            fname: {
                required: var_fieldnotblank,
            },
            lname: {
                required: var_fieldnotblank,
            },
            phone: {
                required: var_fieldnotblank,
            }
        },
        submitHandler: function (form) {
            $.ajax({
                type: $(form).attr('method'),
                url: enroll_to_workshop_url,
                data: $(form).serialize(),
                dataType: 'json'
            })
                    .done(function (response) {

                        if (response.status == "success") {
                            $('#enroll-workshop-form')[0].reset();
                            $('#enrollNowMessages').html('<div class="alert alert-success" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + response.message + '</div>');
                            timeout = setTimeout(function () {
                                $('#enrollNowMessages').html('');
                            }, 5000);
                            //flashmessage(response.message, response.status);
                        }
                        else {
                            $('#enroll-workshop-form')[0].reset();
                            $('#enrollNowMessages').html('<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + response.message + '</div>');
                            timeout = setTimeout(function () {
                                $('#enrollNowMessages').html('');
                            }, 5000);
                            // flashmessage(response.message,  response.status);
                        }

                    });
            return false;
        },
        errorPlacement: function (error, element) {

            error.appendTo(element.parent().parent());
            //error.prependTo(element.parent());
        },
        invalidHandler: function (form, validation) {
            $('div.form-error').remove();
        }
    });

    $('body').on('click','#close-enroll-model',function(){
        $('#enroll-workshop-form')[0].reset();
        enrollwrkform.resetForm();
    });




});

