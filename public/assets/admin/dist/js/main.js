
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function (response, textStatus, jqXHR) {
        hideLoader();
        if (response.hasOwnProperty('message') && response.message.length > 0) {
            if (response.hasOwnProperty('status')) {
                if (response.status) {
                    toastr.success(response.message);
                    if (response.hasOwnProperty('closeModel') && response.closeModel.length > 0) {
                        $('#'+ response.closeModel).modal('hide');
                    }
                } else {
                    notifyError(response.message);

                }
            } else {
                notifyError(response.message);


            }
        }
        if (response.hasOwnProperty('redirect')) {
            window.location.href = response.redirect;
        }
    },
    error: function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.status == 422) {
            notifyError(Object.values(jqXHR.responseJSON.errors)[0]);
            $.each(jqXHR.responseJSON.errors, function (key, item) {

                // notifyError(item[0]);

            });
        } else {

            notifyError(errorThrown);

        }
    },
});

$(document).on('submit', '.ajax-form', function (e) {
    e.preventDefault();

    $(this).find('button[type="submit"]').append(' <i class="fa fa-spinner fa-spin"></i>');
    $(this).find('button[type="submit"]').prop('disabled', true);

    $(this).ajaxSubmit({
        error: function (jqXHR, textStatus, errorThrown, form) {
            $(form).find(':input.is-invalid').removeClass('is-invalid');
            $(form).find('.error').remove();

            if (jqXHR.status == 422) {
                //notifyError(Object.values(jqXHR.responseJSON.errors)[0]);
                $.each(jqXHR.responseJSON.errors, function (key, item) {

                    $(form).find(':input[name="' + key + '"]').addClass('is-invalid');

                    var field = $(form).find(':input[name="' + key + '"]');
                    $("<span style='color:red;padding-top:2px' class='error'>" + item + "</span>").insertAfter(field);

                });

                if ($(form).find(':input.is-invalid:first').length > 0) {
                    $(form).find(':input.is-invalid:first').focus().scroll();

                    var new_position = $(form).find(':input.is-invalid:first').offset().top - 200;

                    $('html, body').animate({scrollTop: new_position}, 500);
                }
            } else {
                notifyError(errorThrown);

            }
        },
        complete: function (jqXHR, textStatus, form) {
            form.find('button[type="submit"]').prop('disabled', false);
            form.find('button[type="submit"] i.fa-spin').remove();

            if (textStatus == 'success') {
                form[0].reset();
                form.find(':input.is-invalid').removeClass('is-invalid');
                form.find('.error').remove();
            }
        }
    });
});



//Mask input phone number
$(document).ready(function (e) {
    $('.select2').select2();
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })
    $('[data-mask]').inputmask();
    // $(".mask-phone").mask("999-999-9999");
    $("[data-bootstrap-switch]").each(function () {
        $(this).bootstrapSwitch('state', $(this).prop('checked'));

    });
});

function notifyError($msg) {
    /*notif({
        msg: $msg,
        type: "error",
        position: "center",
        color: "white"
    });*/
    toastr.error($msg);
}

function notifySuccess($msg) {
    toastr.success($msg);
}


function isNumberKey(evt) {
    let charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return !1;
    return !0
}


function baseUrl(route) {
    let url = $('meta[name="base-url"]').attr('content');
    return url + '/' + route;
}

function showLoader() {
    $('.loading').show();
}
function hideLoader() {
    $('.loading').hide();
}
function baseUrl(route) {
    let url = $('meta[name="base-url"]').attr('content');
    return url + '/' + route;
}

function ajaxDataTable(selector,url,columns,disableColumnSearch = []) {

    $(selector).DataTable({
        "processing": true,
        "scrollX": true,
        "serverSide": true,
        "ajax":url,
        "columns":columns,
        "destroy": true,
        "searching": true,
        "columnDefs": [
            { "orderable": false, "targets": disableColumnSearch }
        ],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
}


//send reset password link
$(document).on('click', '.send-reset-password-link', function () {
    let id = $(this).data('id');
    showLoader();
    $.ajax({
        url: baseUrl('admin/user/send-reset-link'),
        type: "POST",
        data: {
            id: id
        }
    });
});


//Book form Submit
$(document).on('submit', '.book-form', function (e) {
    e.preventDefault();
    let book_image = $('input[name="book_image"]').val();
    let book_video = $('input[name="book_video"]').val();
    let bar = $('.progress-bar');
    let percent = $('.percent');
    let status = $('#status');
    let percentVal = '0%';

    $(this).find('button[type="submit"]').append(' <i class="fa fa-spinner fa-spin"></i>');
    $(this).find('button[type="submit"]').prop('disabled', true);

    $(this).ajaxSubmit({
        //target:"#outputImage",
        beforeSend: function() {
            status.empty();
            percentVal = '0%';
            bar.width(percentVal);
            percent.html(percentVal);
        },

        error: function (jqXHR, textStatus, errorThrown, form) {
            $(form).find(':input.is-invalid').removeClass('is-invalid');
            $(form).find('.error').remove();

            if (jqXHR.status == 422) {
                percentVal = '0%';
                bar.width(percentVal);
                percent.html(percentVal);
                //notifyError(Object.values(jqXHR.responseJSON.errors)[0]);
                $.each(jqXHR.responseJSON.errors, function (key, item) {

                    $(form).find(':input[name="' + key + '"]').addClass('is-invalid');

                    var field = $(form).find(':input[name="' + key + '"]');
                    $("<span style='color:red;padding-top:2px' class='error'>" + item + "</span>").insertAfter(field);

                });

                if ($(form).find(':input.is-invalid:first').length > 0) {
                    $(form).find(':input.is-invalid:first').focus().scroll();

                    var new_position = $(form).find(':input.is-invalid:first').offset().top - 200;

                    $('html, body').animate({scrollTop: new_position}, 500);
                }
            } else {
                notifyError(errorThrown);

            }
        },
        uploadProgress: function(event, position, total, percentComplete) {
            //console.log(event);

            /*if(book_image != "" && book_video != ""){*/
            percentVal = percentComplete + '%';
            bar.width(percentVal);
            percent.html(percentVal);
            /*}*/
        },
        complete: function (jqXHR, textStatus, form) {
            form.find('button[type="submit"]').prop('disabled', false);
            form.find('button[type="submit"] i.fa-spin').remove();

            if (textStatus == 'success') {
                form[0].reset();
                form.find(':input.is-invalid').removeClass('is-invalid');
            }
        }
    });
});

//Upload Book form Submit
$(document).on('submit', '.upload-book-file-form', function (e) {
    e.preventDefault();
    let file = $('input[name="file"]').val();
    let bar = $('.progress-bar');
    let percent = $('.percent');
    let status = $('#status');
    let percentVal = '0%';

    $(this).find('button[type="submit"]').append(' <i class="fa fa-spinner fa-spin"></i>');
    $(this).find('button[type="submit"]').prop('disabled', true);

    $(this).ajaxSubmit({
        //target:"#outputImage",
        beforeSend: function() {
            status.empty();
            percentVal = '0%';
            bar.width(percentVal);
            percent.html(percentVal);
        },

        error: function (jqXHR, textStatus, errorThrown, form) {
            $(form).find(':input.is-invalid').removeClass('is-invalid');
            $(form).find('.error').remove();

            if (jqXHR.status == 422) {
                percentVal = '0%';
                bar.width(percentVal);
                percent.html(percentVal);
                //notifyError(Object.values(jqXHR.responseJSON.errors)[0]);
                $.each(jqXHR.responseJSON.errors, function (key, item) {

                    $(form).find(':input[name="' + key + '"]').addClass('is-invalid');

                    var field = $(form).find(':input[name="' + key + '"]');
                    $("<span style='color:red;padding-top:2px' class='error'>" + item + "</span>").insertAfter(field);

                });

                if ($(form).find(':input.is-invalid:first').length > 0) {
                    $(form).find(':input.is-invalid:first').focus().scroll();

                    var new_position = $(form).find(':input.is-invalid:first').offset().top - 200;

                    $('html, body').animate({scrollTop: new_position}, 500);
                }
            } else {
                notifyError(errorThrown);

            }
        },
        uploadProgress: function(event, position, total, percentComplete) {
            //console.log(event);

            /*if(book_image != "" && book_video != ""){*/
            percentVal = percentComplete + '%';
            bar.width(percentVal);
            percent.html(percentVal);
            /*}*/
        },
        complete: function (jqXHR, textStatus, form) {
            form.find('button[type="submit"]').prop('disabled', false);
            form.find('button[type="submit"] i.fa-spin').remove();

            if (textStatus == 'success') {
                form[0].reset();
                form.find(':input.is-invalid').removeClass('is-invalid');
            }
        }
    });
});
