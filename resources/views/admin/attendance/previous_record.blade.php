@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Attendance</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/admin/attendances') }}">Attendance List</a></li>
                            <li class="breadcrumb-item active">Add Student Attendance</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <!-- /.card-header -->
                        <div class="card-body">
                            <form role="form" method="post" action="{{ route('admin.attendance-form') }}"
                                class="book-form" enctype="multipart/form-data">
                                {{--  <div class="card-title text-right float-right">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#importAttendanceModal">
                                        <i class="fas fa-plus-circle mr-1"></i> Import Attendance
                                    </button>
                                </div>  --}}
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="student">Student Name </label>
                                        <select class="form-control" id="student" name="student">
                                            <option>Select Student</option>
                                            @isset($users)
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">
                                                        {{ $user->first_name . ' ' . $user->last_name }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                        @error('student')
                                            <span style="color:red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="fromDate">From Date</label>

                                        <input type="datetime-local" name="from_date" id="fromDate" placeholder=""
                                            class="form-control">
                                        @error('from_date')
                                            <span style="color:red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="toDate">To Date </label>
                                        <input type="datetime-local" name="to_date" id="toDate" placeholder="Date To"
                                            class="form-control">
                                        @error('to_date')
                                            <span style="color:red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="hours">Hours</label>
                                        <input type="numbers" name="hours" id="hours" placeholder="Hours"
                                            class="form-control">
                                        @error('hours')
                                            <span style="color:red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">

                                        <label for="">Days in Week</label>
                                        <div class="form-check">
                                            <label class="form-check-label mr-4">
                                                <input type="checkbox" class="form-check-input" name="days[]"
                                                    value="Mon">Monday
                                            </label>
                                            <label class="form-check-label mr-4">
                                                <input type="checkbox" class="form-check-input" name="days[]"
                                                    value="Tue">Tuesday
                                            </label>
                                            <label class="form-check-label mr-4">
                                                <input type="checkbox" class="form-check-input" name="days[]"
                                                    value="Wed">Wednesday
                                            </label>
                                            <label class="form-check-label mr-4">
                                                <input type="checkbox" class="form-check-input" name="days[]"
                                                    value="Thu">Thursday
                                            </label>
                                            <label class="form-check-label mr-4">
                                                <input type="checkbox" class="form-check-input" name="days[]"
                                                    value="Fri">Friday
                                            </label>
                                            <label class="form-check-label mr-4">
                                                <input type="checkbox" class="form-check-input" name="days[]"
                                                    value="Sat">Saturday
                                            </label>
                                            <label class="form-check-label mr-4">
                                                <input type="checkbox" class="form-check-input" name="days[]"
                                                    value="Sun">Sunday
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 0%;"
                                                aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                <div class="percent">0%</div>
                                            </div>
                                        </div>
                                        <div style="height: 10px;"></div>
                                        <div id='outputImage'></div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class=" btn-book-form btn btn-primary">
                                        <!----> <span>Add </span>
                                    </button>
                                </div>
                            </form>


                            <!--Import Students Modal -->
                            <div class="modal fade" id="importAttendanceModal" tabindex="-1" role="dialog"
                                aria-labelledby="importAttendanceModalTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLongTitle">Import Attendance</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="{{route('admin.attendance-import')}}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <p class="text-orange">Please select an Excel file (.xlsx, .xls) or a
                                                    CSV file (.csv) to import</p>
                                                <input type="file" class="my-3" id="fileInput" name="fileInput"
                                                    accept=".xlsx, .xls, .csv">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Import</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script>
        /* $(document).on('submit', '.book-form', function (e) {
                    e.preventDefault();
                    let bookForm = $(this);
                    let file = $('input[name=file]').val();
                    let bar = $('.progress-bar');
                    let percent = $('.percent');
                    let status = $('#status');
                    let percentVal = '0%';

                    $(this).find('button[type="submit"]').append(' <i class="fa fa-spinner fa-spin"></i>');
                    $(this).find('button[type="submit"]').prop('disabled', true);
                    let book_image = $('#book_image').prop('files')[0] ?? "";
                    let book_video = $('#book_video').prop('files')[0] ?? "";
                    let form_data = new FormData(this);
                    form_data.append('file', book_image);
                    form_data.append('file', book_video);
                    $.ajax({
                        url:$(this).attr('action'),
                        type:$(this).attr('method'),
                        data: form_data,
                        cache: false,
                        contentType: false,
                        processData: false,

                        error: function (jqXHR, textStatus, errorThrown, form) {
                            bookForm.find(':input.is-invalid').removeClass('is-invalid');
                            bookForm.find('.error').remove();

                            if (jqXHR.status == 422) {
                                //notifyError(Object.values(jqXHR.responseJSON.errors)[0]);
                                $.each(jqXHR.responseJSON.errors, function (key, item) {

                                    bookForm.find(':input[name="' + key + '"]').addClass('is-invalid');

                                    var field = bookForm.find(':input[name="' + key + '"]');
                                    $("<span style='color:red;padding-top:2px' class='error'>" + item + "</span>").insertAfter(field);

                                });

                                if (bookForm.find(':input.is-invalid:first').length > 0) {
                                    bookForm.find(':input.is-invalid:first').focus().scroll();

                                    var new_position = bookForm.find(':input.is-invalid:first').offset().top - 200;

                                    $('html, body').animate({scrollTop: new_position}, 500);
                                }
                            } else {
                                notifyError(errorThrown);

                            }
                        },
                        uploadProgress: function(event, position, total, percentComplete) {
                            console.log(event);
                            //if(file != ""){
                            percentVal = percentComplete + '%';
                            bar.width(percentVal);
                            percent.html(percentVal);
                            // }
                        },
                        complete: function (jqXHR, textStatus, form) {
                            bookForm.find('button[type="submit"]').prop('disabled', false);
                            bookForm.find('button[type="submit"] i.fa-spin').remove();

                            if (textStatus == 'success') {
                                bookForm.find(':input.is-invalid').removeClass('is-invalid');
                            }
                        }
                    });
                });*/
        /*var file_data = $('#sortpicture').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        alert(form_data);
        $.ajax({
            url: 'upload.php', // point to server-side PHP script
            dataType: 'text',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(php_script_response){
                alert(php_script_response); // display response from the PHP script, if any
            }
         });*/
        /*$(document).on('submit', '.book-form', function (e) {
            e.preventDefault();
            let file = $('input[name=file]').val();
            let bar = $('.progress-bar');
            let percent = $('.percent');
            let status = $('#status');
            let percentVal = '0%';

            $(this).find('button[type="submit"]').append(' <i class="fa fa-spinner fa-spin"></i>');
            $(this).find('button[type="submit"]').prop('disabled', true);

            $(this).ajaxSubmit({
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
                    console.log(event);
                    if(file != ""){
                        percentVal = percentComplete + '%';
                        bar.width(percentVal);
                        percent.html(percentVal);
                    }
                },
                complete: function (jqXHR, textStatus, form) {
                    form.find('button[type="submit"]').prop('disabled', false);
                    form.find('button[type="submit"] i.fa-spin').remove();

                    if (textStatus == 'success') {
                        form.find(':input.is-invalid').removeClass('is-invalid');
                    }
                }
            });
        });*/
    </script>
@endpush
