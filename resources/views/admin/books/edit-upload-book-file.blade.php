@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit / Upload Book File</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Edit / Upload Book File</li>
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
                                <a href="{{route('admin.books.index')}}" class="add_advertiser">
                                    <i class="fas fa-list"></i> Books List
                                </a>
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form role="form" method="post" action="{{route('admin.books.edit-upload-book-file-submit',[$bookId,$languageId])}}"
                                  class="upload-book-file-form" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="book_id"> Book </label>
                                        <select name="book_id" id="book_id" class="form-control" disabled>
                                            <option value=""> Select Book</option>
                                            @foreach($books as $book)
                                                <option value="{{$book->id}}" {{($book->id == $bookId)?"selected":""}}>{{$book->title}} {{(!empty($book->chapter))?"( ".$book->chapter." )":""}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="language_id">Language </label>
                                        <select name="language_id" id="language_id" class="form-control">
                                            <option value=""> Select Language</option>
                                            @foreach($languages as $language)
                                                <option value="{{$language->id}}" {{($language->id == $languageId)?"selected":""}}>{{$language->language}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="file">File </label>
                                        <input type="file" class="" name="file" id="file"/>
                                        {{--<div class="progress">
                                            <div class="bar"></div >
                                            <div class="percent">0%</div >
                                        </div>--}}
                                        {{--<div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"> <div class="percent">0%</div ></div>
                                        </div>--}}
                                    </div>
                                    <div class="form-group">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                <div class="percent">0%</div ></div>
                                        </div>
                                        <div style="height: 10px;"></div>
                                        <div id='outputImage'></div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <!----> <span>Update </span>
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
