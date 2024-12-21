//declare url variables here 

if (typeof base_url == 'undefined') {
    base_url = undefined;
}
if (typeof in_contact_id == 'undefined') {
    in_contact_id = 0;
}


$(document).ready(function (ev) {

    $("#in_contact_number").inputmask({ "mask": "999-999-9999" });

    initlisting();

    $.validator.addMethod("valueNotEquals", function (value, element, params) {
        var temp = [];
        if ($.isArray(params) == true) {
            $.each(params, function (index, inner_value) {
                if (value == inner_value) {
                    temp.push('1');
                }
            });
        }

        if (temp.length > 0) {
            return false;
        }

        return params !== value;
    }, "Value must not equal arg.");

    $.validator.addMethod("general_phone", function (phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length == 10 || $.isNumeric(phone_number) || phone_number.match(/\d{3}-\d{3}-\d{4}/);
    }, "Please specify a valid phone number");

    $.validator.addMethod("letterswithbasicpunc", function (value, element) {
        return this.optional(element) || /^[a-z0-9\-.,()'"\s]+$/i.test(value);
    }, "Letters or punctuation only please");

    $.validator.addMethod("letterswithbasicpuncnowhitespace", function (value, element) {
        return this.optional(element) || (/^[a-z0-9\-.,()'"\s]+$/i.test(value) && value && value.trim());
    }, "Letters or punctuation only please");

    $.validator.addMethod("integer", function (value, element) {
        // return this.optional( element ) || /^-?\d+$/.test( value );
        return this.optional(element) || /^\d+$/.test(value);
    }, "A positive or negative non-decimal number please");

    $.validator.addMethod("email_id", function (emailaddress, element) {
        emailaddress = emailaddress.replace(/\s+/g, "");
        return this.optional(element) || emailaddress.match(/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i);
    }, "Please specify a valid phone number");

});

var contact_form_alert = "";
$("#gevme_form").validate({
    ignore: [],
    errorClass: "invalid-feedback",
    // validClass: "is-valid",
    errorElement: 'span',
    highlight: function (element, errorClass, validClass) {
        $(element).parents("div.form-control").removeClass(errorClass).removeClass(validClass);
    },
    errorPlacement: function (error, element) {
        var placement = $(element).data('error');
        if (placement) {
            $(placement).after(error);
        } else {
            error.insertAfter(element);
        }
    },
    // unhighlight: function(element, errorClass, validClass) {
    //     $(element).parents(".error").removeClass(errorClass).addClass(validClass);
    // }
    rules: {
        st_full_name: {
            required: true,
        },
        st_email: {
            required: true,
            email: true,
            email_id: true,
        },
        in_contact_number: {
            required: true,
            general_phone: true
        },
    },
    messages: {
        st_full_name: {
            required: "Please enter first name.",
        },
        st_email: {
            required: "Please enter email address.",
            email: "Invalid email address.",
            email_id: "Invalid email address.",
        },
        in_contact_number: {
            required: "Please enter contact number.",
            general_phone: "Invalid contact number.",
        },
    },
    submitHandler: function (form, event) {
        event.preventDefault();
        $("#in_contact_number").inputmask({ "mask": "9999999999" });
        var submittype = $("#gevme_form :submit").data('submittype');
        var form = $("#gevme_form");
        var url = base_url + 'controller.php';
        form.find(".invalid-feedback").detach();
        window.clearTimeout(contact_form_alert);
        $("#response-alert").addClass("d-none");
        $("#gevme_form :submit").attr("disabled", "disabled");
        var dataform = $('#gevme_form')[0]; // You need to use standard javascript object here
        var formData = new FormData(dataform);
        formData.append('action', submittype);
        if (submittype == "edit") {
            formData.append('in_contact_id', in_contact_id);
        }
        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {

            }
        }).done(function (data) {
            $("html, body").animate({ scrollTop: ($('#response-alert').offset().top - 120) }, 'easeInOutExpo');

            if (data.result = "success") {
                $('#submit_button').attr('data-submittype', "add");
                $('#submit_button').data('submittype', "add");
                appendTableData(data);
            } else {
                showErrors(data.errors);
            }
            $("#gevme_form :submit").removeAttr("disabled");

            $("#gevme_form")[0].reset();
            $('#submit_button').text('Submit');
            submittype = "add";
            $("#in_contact_number").inputmask({ "mask": "999-999-9999" });
        });
    }
});

function showErrors(params) {
    $.each(params, function (index, value) {
        var error = '<span id="' + index + '-error" class="invalid-feedback text-left" style="display: inline;">' + value + '</span>';
        var placement = $("#" + index).data('error');
        if (placement) {
            $(placement).after(error);
        } else {
            $("#" + index).after(error);
        }
    });
}


function appendTableData(params) {
    $("#response-message").text(params.message);
    $("#response-alert").removeClass("d-none").addClass("d-flex");
    contact_form_alert = setTimeout(function () { $("#response-alert").removeClass("d-flex").addClass("d-none"); }, 5000);
    $('#update_listing_rows').html(params.rows_data);
    $(".contact_number").inputmask({ "mask": "999-999-9999" });
}


//to hide ajax alert messages from below input fields 
$('body').on('click', '#gevme_form input, #gevme_form file, #gevme_form select, #gevme_form textarea', function (event) {
    $(this).closest(".form-group").find(".invalid-feedback").detach();
});

$('body').on('change', '#gevme_form input, #gevme_form file, #gevme_form select, #gevme_form textarea', function (event) {
    $(this).closest(".form-group").find(".invalid-feedback").detach();
});


$('body').on('click', '.delete_user', function (e) {
    $('#delete_user').attr('data-delete_id', $(this).data("delete_id"));
    $('#delete_user').data('delete_id', $(this).data("delete_id"));
    $("#DeleteModal").modal('show');
    $(this).closest("tr").addClass('selected');
});

var delete_notification = "";
$('body').on('click', '#delete_user', function (e) {
    var deleteid = $(this).data("delete_id");
    var fd = new FormData();
    window.clearTimeout(delete_notification);
    $("#delete_user").attr("disabled", "disabled");

    // Append data 
    fd.append('in_contact_id', deleteid);
    fd.append('action', 'delete');

    $.ajax({
        url: base_url + 'controller.php',
        method: 'post',
        data: fd,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (data) {

        },
    }).done(function (data) {
        $("html, body").animate({ scrollTop: ($('#response-alert').offset().top - 120) }, 'easeInOutExpo');

        if (data.result = "success") {
            $('.selected').remove();
            appendTableData(data);
        } else {
            showErrors(data.errors);
        }
        $("#delete_user").removeAttr("disabled");
        $("#DeleteModal").modal('hide');
    });


});


$('body').on('click', '.edit_user', function (e) {
    var fd = new FormData();
    fd.append('action', 'get_particular');
    fd.append('in_contact_id', $(this).data("in_contact_id"));

    // AJAX request 
    $.ajax({
        url: base_url + 'controller.php',
        method: 'post',
        data: fd,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (data) {

        },
    }).done(function (data) {
        $.each(data.rows_data[0], function (index, value) {
            if ($('#' + index).length) {
                $('#' + index).val(value);
            }
        });
        in_contact_id = data.rows_data[0].in_contact_id;

        $("html, body").animate({ scrollTop: ($('#response-alert').offset().top - 120) }, 'easeInOutExpo');
    });

    $('#submit_button').attr('data-submittype', "edit");
    $('#submit_button').data('submittype', "edit");
    $('#submit_button').text('Update');
    $("#st_full_name").focus();
});

function initlisting() {
    var fd = new FormData();
    fd.append('action', 'list');

    // AJAX request 
    $.ajax({
        url: base_url + 'controller.php',
        method: 'post',
        data: fd,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function (data) {

        },
    }).done(function (data) {
        $("html, body").animate({ scrollTop: ($('#response-alert').offset().top - 120) }, 'easeInOutExpo');

        if (data.result = "success") {
            $('.selected').remove();
            appendTableData(data);
        } else {
            showErrors(data.errors);
        }
        $("#delete_user").removeAttr("disabled");

    });

}

