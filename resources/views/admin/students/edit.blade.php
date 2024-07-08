@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Student</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Edit Student</li>
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
                                <a href="{{route('admin.students.index')}}" class="add_advertiser">
                                    <i class="fas fa-list"></i> Student List
                                </a>
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form role="form" method="post" action="{{route('admin.students.update', [$user->id])}}"
                                  class="ajax-form" enctype="multipart/form-data">
                                @method('PUT')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="username">Username </label>
                                        <input type="text" name="username" id="username"
                                               placeholder="Username" class="form-control" value="{{$user->username}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="first_name">First Name </label>
                                        <input type="text" name="first_name" id="first_name"
                                               placeholder="First Name" class="form-control" value="{{$user->first_name}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="last_name">Last Name </label>
                                        <input type="text" name="last_name" id="last_name"
                                               placeholder="Last Name" class="form-control" value="{{$user->last_name}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone_number">Email </label>
                                        <input type="text" name="email" id="email"
                                               placeholder="Email" class="form-control" value="{{$user->email}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone_number">Phone Number </label>
                                        <input type="text" name="phone_number" id="phone_number"
                                               placeholder="Phone Number" class="form-control" value="{{$user->phone_number}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone_number">Update Password</label>
                                        <input type="checkbox" value="1" id="update_password" name="update_password"/>
                                        <input type="text" name="password" id="password" style="display: none"
                                               placeholder="Password" class="form-control" value="">
                                    </div>
                                    {{--<div class="form-group">
                                        <label for="level">Student Level</label>
                                        <select name="level" id="level" class="form-control">
                                            <option value="1" {{$user->level == '1'?"selected":""}}>Level 1</option>
                                            <option value="2" {{$user->level == '2'?"selected":""}}>Level 2</option>
                                            <option value="3" {{$user->level == '3'?"selected":""}}>Level 3</option>
                                        </select>
                                    </div>--}}
                                    {{--<div class="form-group">
                                        <label for="language_id"> Assign Book Language </label>
                                        <select name="language_id" id="language_id" class="form-control">
                                            <option value=""> Select Language</option>
                                            @foreach($languages as $language)
                                                <option value="{{$language->id}}" {{($language->id == $user->language_id)?"selected":""}} >{{$language->language}}</option>
                                            @endforeach
                                        </select>
                                    </div>--}}
                                    <div class="form-group">
                                        <label for="level">Assign Books</label>
                                        <select name="book_ids[]" id="book_ids" class="select2bs4 " multiple data-placeholder="Select a Book" style="width: 100%;">
                                            @if($books->isNotEmpty())
                                            @foreach($books as $book)
                                                <option value="{{$book->id}}" {{($user->books->isNotEmpty() && in_array($book->id,$user->books->pluck('id')->toArray()))?"selected":""}}>{{$book->title}} </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <!----> <span>Update  </span>
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
        $(document).ready(function(){
            $('#update_password').click(function(){
                if($(this).prop("checked") == true){
                    $('#password').show();
                }
                else if($(this).prop("checked") == false){
                    $('#password').hide();
                }
            });

            $(document).on('keyup','#email',function(){
                // let str= "someone@example.com";
                let str = $(this).val();
                let nameMatch = str.match(/^([^@]*)@/);
                let username = nameMatch ? nameMatch[1] : null;
                $('#username').val(username);
            });

            //get books by language
            $(document).on('change','#language_id',function () {
                showLoader();
                let languageId = $(this).val();
                $.ajax({
                    url:"{{route('admin.get-books-by-language')}}",
                    type:"POST",
                    data:{
                        languageId:languageId
                    },
                    dataType:"json",
                    success:function (result) {
                        hideLoader();
                        let options = '';
                        if(result.result.length > 0){
                            $.each(result.result,function (key,row) {
                                options += `<option value="${row.id}">${row.title}</option>`;
                            });
                        } else {
                            notifyError('No books available for this language.')
                        }
                        $('#book_ids').html(options);
                    }
                });
            });
        });
    </script>
@endpush
