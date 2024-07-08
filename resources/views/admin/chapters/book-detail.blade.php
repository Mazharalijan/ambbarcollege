@extends('layout.master')
@section('content')
    <style>
        video::-internal-media-controls-download-button {
            display:none;
        }

        video::-webkit-media-controls-enclosure {
            overflow:hidden;
        }

        video::-webkit-media-controls-panel {
            width: calc(100% + 30px); /* Adjust as needed */
        }
    </style>
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Book Detail</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Book Detail</li>
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
                                <a href="{{route('admin.books.index')}}" class="add_advertiser">
                                    <i class="fas fa-list"></i> Books List
                                </a>
                                {{--{{asset('uploads/books/'.$book->file)}}--}}
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3> {{$book->title}}</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    {{$book->description}}
                                </div>
                            </div>
                            <div class="row pt-4">
                                <div class="col-md-12">
                                    @if(!empty($book->book_video))
                                    <video width="320" height="240" controls controlsList="nodownload" >
                                        <source src="{{asset('uploads/book-videos/'.$book->book_video)}}" >
                                        Your browser does not support the video tag.
                                    </video>
                                    @endif
                                </div>
                            </div>
                            <div class="row pt-4">
                                <div class="col-md-12">
                                    <h4> Book Languages</h4>
                                </div>
                            </div>

                            <div class="row">
                                {{--                                    <div id="adobe-dc-view" style="height: 800px; "></div>--}}
                                @foreach($bookFiles as $file)
                                    <div class="col-md-3 pt-3">
                                        <div class="card p-3">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <a href="{{route('admin.books.view-book',[$book->id,$file->pivot->language_id])}}" style="text-decoration: none;color: black">
                                                        <h3> <i class="fa fa-language"></i> {{$file->language}}</h3>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-10">
                                                   {{-- ${(row.description != '' && row.description != null) ?
                                                    row.description.substring(0,25) :''}--}}
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="dropdown">
                                                        <a class="dropdown-toggle" data-toggle="dropdown"
                                                           href="javascript:void(0)"
                                                           aria-expanded="true">
                                                            <i class="fa fa-sliders" aria-hidden="true"></i></a>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item "
                                                               href="{{route('admin.books.edit-upload-book-file',[$book->id,$file->pivot->language_id])}}"><i
                                                                    class="fa fa-pen" aria-hidden="true"></i> Edit</a>
                                                            <form action="{{route('admin.books.delete-book-file')}}"
                                                                  class="ajax-form" method="POST">
                                                                <input type="hidden" value="{{$book->id}}" name="book_id">
                                                                <input type="hidden" value="{{$file->id}}" name="language_id">
                                                                <input type="hidden" value="{{$file->book_file}}" name="old_file">
                                                                <button type="submit" class="dropdown-item"><i
                                                                        class="fa fa-trash" aria-hidden="true"></i>
                                                                    Delete
                                                                </button>
                                                            </form>
                                                            <a href="{{route('admin.books.view-book',[$book->id,$file->pivot->language_id])}}"
                                                               alt="book detail" class="dropdown-item"><i
                                                                    class="fa fa-eye"></i> View Book </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    {{--<script src="https://documentcloud.adobe.com/view-sdk/main.js"></script>
    <script type="text/javascript">
        document.addEventListener("adobe_dc_view_sdk.ready", function () {
            var adobeDCView = new AdobeDC.View({
                clientId: "{{env('ADOBE_CLIENT_ID')}}",
                divId: "adobe-dc-view"
            });
            adobeDCView.previewFile({
                content: {location: {url: "{{asset('uploads/books/'.$book->file)}}"}},
                metaData: {fileName: "{{$book->file}}"}
            }, {
                showDownloadPDF: false,
                showPageControls: true,
                showPrintPDF: false,
                showAnnotationTools: false,
                dockPageControls: true,
                defaultViewMode: "FIT_WIDTH",
            });
        });
    </script>--}}
@endpush
