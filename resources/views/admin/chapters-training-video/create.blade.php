@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Chapter Training Video</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Add Chapter Training Video</li>
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
                            <form role="form" method="post" action="{{route('admin.training-video.store')}}"
                                  class="book-form" enctype="multipart/form-data">
                                <div class="card-body">
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
                                        <label for="chapter_id"> Select Chapter Name </label>
                                        <select name="chapter_id" id="chapter_id" class="form-control select2" >
                                            <option value=""> Select Chapter</option>
                                            {{--@foreach($books as $book)
                                                <option value="{{$book->id}}" >{{$book->title}} </option>
                                            @endforeach--}}
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <span class="text-info">Maximum video file size to upload is 400MB</span><br/>
                                        <label for="video">Training Video </label>
                                        <input type="file" class="" name="training_video" id="training_video" accept="video/*" />
                                    </div>
                                    <div class="form-group">
                                        <label for="link">Training Video Link</label>
                                        <input name="link" id="link" class="form-control">
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
        $(document).on('change','#parent_id',function () {
           let bookId = $(this).val();
           $.ajax({
               url:"{{route('admin.get-chapter-by-book')}}",
               type:"POST",
               data:{
                   bookId:bookId
               },
               dateType:"json",
               success:function (result) {
                   let options = '';
                   if(result.status){
                       $.each(result.data,function (key,row) {
                           options += `<option value="${row.id}">${row.chapter}</option>`
                       });
                   } else {
                       options = `<option value="">No Chapters</option>`;
                   }
                   $('#chapter_id').html(options);
               }
           });
        });
    </script>
@endpush
