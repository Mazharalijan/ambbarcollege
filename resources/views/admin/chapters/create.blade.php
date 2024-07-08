@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Chapter</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Add Chapter</li>
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
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{route('admin.chapters.index')}}" class="add_advertiser">
                                    <i class="fas fa-list"></i> Chapter List
                                </a>
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form role="form" method="post" action="{{route('admin.chapters.store')}}"
                                  class="book-form" enctype="multipart/form-data">
                                <div class="card-body">
                                    {{--<div class="form-group">
                                        <label for="title">Title </label>
                                        <input type="text" name="title" id="title"
                                               placeholder="Title" class="form-control">
                                    </div>--}}
                                    <div class="form-group">
                                        <label for="parent_id"> Select Book Name </label>
                                        <select name="parent_id" id="parent_id" class="form-control select2" >
                                            <option value=""> Select Book</option>
                                            {{--{{($book->id == $bookId)?"selected":""}}--}}
                                            @foreach($books as $book)
                                                <option value="{{$book->id}}" >{{$book->title}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Chapter Name</label>
                                        <input type="text" name="chapter" id="chapter"
                                               placeholder="Chapter Name" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="chapter_number">Chapter Number</label>
                                        <input type="number" name="chapter_no" id="chapter_number"
                                               placeholder="Chapter Number" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="video_link">Chapter Video Link</label>
                                        <input type="text" name="video_link" id="video_link"
                                               placeholder="Chapter video link" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description (optional)</label>
                                        <textarea class="form-control" name="description" id="description"></textarea>
                                    </div>
                                   {{-- <div class="form-group">
                                        <label for="file">File </label>
                                        <input type="file" class="" name="file" id="file"/>
                                        --}}{{--<div class="progress">
                                            <div class="bar"></div >
                                            <div class="percent">0%</div >
                                        </div>--}}{{--
                                        --}}{{--<div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> <div class="percent">0%</div ></div>
                                        </div>--}}{{--
                                    </div>--}}
                                    <div class="form-group">
                                        <label for="file">Book Upload </label>
                                        <input type="file" class="" name="file" id="file"/>
                                    </div>

                                    <div class="form-group">
                                        <label for="file">Chapter Image </label>
                                        <input type="file" class="" name="book_image" id="book_image" accept="image/*" />
                                    </div>
                                    <div class="form-group">
                                        <span class="text-info">Maximum video file size to upload is 400MB</span><br/>
                                        <label for="video">Chapter Video </label>
                                        <input type="file" class="" name="book_video" id="book_video" accept="video/*" />
                                    </div>
                                    <div class="form-group">
                                        {{--<div class="progress">
                                            <div class="bar"></div >
                                            <div class="percent">0%</div >
                                        </div>--}}
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                <div class="percent">0%</div ></div>
                                        </div>
                                        {{--<div class='progress' id="progressDivId">
                                            <div class='progress-bar' id='progressBar'></div>
                                            <div class='percent' id='percent'>0%</div>
                                        </div>--}}
                                        <div style="height: 10px;"></div>
                                        <div id='outputImage'></div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit"  class=" btn-book-form btn btn-primary">
                                        <!----> <span>Add </span>
                                    </button>
                                </div>
                            </form>
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
