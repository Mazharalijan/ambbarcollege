@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>View Book</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">View Book</li>
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
                                    <h3>{{$book->title}}</h3>
                                    <h4>{{$bookFile->language}}</h4>
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
                content: {location: {url: "{{asset('uploads/books/'.$bookFile->book_file)}}"}},
                metaData: {fileName: "{{$bookFile->book_file}}"}
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
