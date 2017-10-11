$(document).ready(function () {
    jQuery.validator.addMethod("noSpace", function (value, element) {
        return value.indexOf(" ") < 0 && value != "";
    }, "No space please and don't leave it empty");

    $("#agent-login-form").validate({
        rules: {
            _password: {required: true
            },
            _username: {
                required: true
            }
        },
        messages: {
            _password: {required: "Please enter password"},
            _username: {
                required: "Please enter username"
            }
        }
    });

    $("#form-add-agents").validate({
        rules: {
            aname: {required: true},
            email: {required: true, email: true
            },
            password: {
                required: true,
                minlength: 3,
                maxlength: 20
            },
            extension: {required: true, minlength: 3, maxlength: 5},
            pin: {required: true},
            gender: {required: true},
            status: {required: true},
        },
        messages: {
            aname: {required: "Please enter name"},
            email: {required: "Please enter email"},
            password: {required: "Please enter password", minlength: 'minmum length is 3', maxlength: 'maximum length is 5'},
            extension: {required: "Please enter extension "},
            pin: {required: "Please enter pincode"},
            gender: {required: "Please select gender"},
            status: {required: "Please select status"}

        }
    });
    $("#form-edit-agents").validate({
        rules: {
            aname: {required: true},
            email: {required: true, email: true
            },
            extension: {required: true},
            pin: {required: true},
            gender: {required: true},
            status: {required: true},
        },
        messages: {
            aname: {required: "Please enter name"},
            email: {required: "Please enter email"},
            extension: {required: "Please enter extension"},
            pin: {required: "Please enter pincode"},
            gender: {required: "Please select gender"},
            status: {required: "Please select status"}

        }
    });

    $('body').on('click', '.agent-delete', function () {

        var userId = $(this).parent('td').parent('tr').attr("data");
        var ext = $(this).parent('td').parent('tr').find("td:eq(2)").text();
        bootbox.confirm("Are you sure , you want to delete this agent ?", function (result) {
            if (result == true)
            {
                $.ajax({
                    url: path_delete_agent,
                    type: "POST",
                    data: "userId=" + userId + "&ext=" + ext,
                    success: function (response)
                    {
                        if (response.status == "success")
                        {
                            bootbox.alert("<h4 class='text-success' > " + response.message + "</h4>", function () {
                            });
                            location.reload(path_agent_manage);
                        }

                    }
                });
            }
        });
    });
    $('body').on('click', '.agent-delete-all', function () {

        var uid = "";
        var ext = "";
        $('input[type=checkbox]:checked').each(function () {
            if (this.value != 'yes') {
                uid += this.value + ',';
                ext += $(this).parent('td').parent('tr').find("td:eq(2)").text() + ',';
            }
        });
        if (uid != '') {
            bootbox.confirm("<h4 class='text-info'>Are you sure, you want to delete selected agent ?</h4>", function (result) {
                if (result == true) {
                    var tr_uid = uid.split(",");
                    var tr_ext = ext.split(",");
                    $.ajax({
                        url: path_delete_agent,
                        type: "POST",
                        data: "userId=" + uid + "&ext=" + ext + '&delete=multiple',
                        success: function (response) {
                            if (response.status == "success") {
                                bootbox.alert("<h4 class='text-success' > " + response.message + "</h4>", function () {
                                });
                                location.reload(path_agent_manage);
                            }
                            else {
                                bootbox.alert("<h4 class='text-danger' > " + response.message + "</h4>", function () {
                                });
                            }
                        }
                    });
                }
            });
        }
        else {
            bootbox.alert("<h4 class='text-success' > Please select atleast one agent to delete </h4>", function () {
            });
        }


    });


    $('body').on('change', '#checkAll', function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });


});



