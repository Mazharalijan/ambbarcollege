@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>View Chapter</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">View Chapter</li>
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
                                {{--{{asset('uploads/books/'.$book->file)}}--}}
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>{{$book->chapter}}</h3>
                                    {{--<h4>{{$bookFile->language}}</h4>--}}
                                </div>
                            </div>
                            <div class="row pt-4">
                                <div class="col-md-12">
                                    @if(!empty($book->book_video))
                                        <video width="320" height="240" controls controlsList="nodownload">
                                            <source src="{{asset('uploads/book-videos/'.$book->book_video)}}" >
                                            Your browser does not support the video tag.
                                        </video>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="adobe-dc-view" style="height: 800px; "></div>
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
    <script src="https://documentcloud.adobe.com/view-sdk/main.js"></script>
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
    </script>
@endpush
