@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Chapter</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Edit Chapter</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-md-12">
{{--                            <embed src="{{asset('uploads/books/'.$book->file)}}" type="application/pdf" width="100%" height="auto">--}}
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{route('admin.chapters.index')}}" class="add_advertiser">
                                    <i class="fas fa-list"></i> Chapters List
                                </a>
                                {{--{{asset('uploads/books/'.$book->file)}}--}}
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            {{--<div id="adobe-dc-view"></div>
                            <script src="https://documentcloud.adobe.com/view-sdk/main.js"></script>
                            <script type="text/javascript">
                                document.addEventListener("adobe_dc_view_sdk.ready", function(){
                                    var adobeDCView = new AdobeDC.View({clientId: "2f938545e270416594e7c5bdb25b4d21", divId: "adobe-dc-view"});
                                    adobeDCView.previewFile({
                                        content:{location: {url: "{{asset('uploads/books/'.$book->file)}}"}},
                                        metaData:{fileName: "{{$book->file}}"}
                                    }, {showDownloadPDF: false, showPageControls: true,showPrintPDF:false,showAnnotationTools:false,dockPageControls:true,defaultViewMode:"FIT_WIDTH"});
                                });
                            </script>--}}
                            <form role="form" method="post" action="{{route('admin.chapters.update', [$book->id])}}"
                                  class="book-form" enctype="multipart/form-data">
                                @method('PUT')
                                <input type="hidden" name="old_book_image" value="{{$book->book_image}}">
                                <input type="hidden" name="old_book_video" value="{{$book->book_video}}">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="parent_id"> Select Book Name </label>
                                        <select name="parent_id" id="parent_id" class="form-control select2" >
                                            <option value=""> Select Book </option>
                                            {{--{{($book->id == $bookId)?"selected":""}}--}}
                                            @foreach($books as $row)
                                                <option value="{{$row->id}}" {{($row->id == $book->parent_id)?"selected":""}} >{{$row->title}}  </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{--<div class="form-group">
                                        <label for="title">Title </label>
                                        <input type="text" name="title" id="title"
                                               placeholder="Title" class="form-control" value="{{$book->title}}">
                                        <input type="hidden" value="{{$book->file}}" name="old_file">
                                    </div>--}}
                                    <div class="form-group">
                                        <label for="title">Chapter Name</label>
                                        <input type="text" name="chapter" id="chapter"
                                               placeholder="Chapter Name" class="form-control" value="{{$book->chapter}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="chapter_number">Chapter Number</label>
                                        <input type="number" name="chapter_no" id="chapter_number"  value="{{$book->chapter_no}}"
                                               placeholder="Chapter Number" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="video_link">Chapter Video Link</label>
                                        <input type="text" name="video_link" id="video_link" value="{{$book->video_link}}"
                                               placeholder="Chapter video link" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description (optional)</label>
                                        <textarea class="form-control" name="description" id="description">{{$book->description}}</textarea>
                                    </div>
                                    {{--<div class="form-group">
                                        <label for="file">File </label>
                                        <input type="file" class="" name="file" id="file"/>
                                    </div>--}}
                                    <div class="form-group">
                                        <label for="file">Book Upload  </label>
                                        <input type="file" class="" name="file" id="file"/>
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Chapter Image</label>
                                        <input type="file"  name="book_image" id="book_image" accept="image/*" >
                                        <img src="{{asset('uploads/book-images/'.$book->book_image)}}" class="img  img-responsive" style="width: 120px;height: 100px;"  alt="">
                                    </div>
                                    <div class="form-group">
                                        <span class="text-info">Maximum video file size to upload is 400MB</span><br/>
                                        <label for="video">Chapter Video </label>
                                        <input type="file" class="" name="book_video" id="book_video" accept="video/*" />
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
