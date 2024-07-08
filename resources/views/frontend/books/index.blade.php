@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Books List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Books List</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- Default box -->
                        {{--collapsed-card => by default open apply in card-header--}}
                        <div class="card " >
                            <div class="card-header">
                                <h3 class="card-title">Search Filter</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool " data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body collapse" id="">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="title">Book Title</label>
                                            <input type="text" name="title" id="title"
                                                   placeholder="Book Title" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" onclick="loadMore(1,true)" class="btn btn-primary search-btn">
                                    <span>Search </span>
                                </button>
                                <a href="{{route('admin.books.index')}}" class="btn btn-default">Reset</a>
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">

                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <div class="row" id="result">
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-center pt-3">
                                    <button class="btn btn-primary" id="btn-load-more" data-page="1">Load More</button>
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
        let page = 1;
        $(document).ready(function () {
            // get data on page load
            loadMore();
            //get more records on button click
            $(document).on('click', '#btn-load-more', function () {
                page = $("#btn-load-more").data('page');
                page++;
                loadMore(page);
            });
        });

        //load more data by page number
        function loadMore(page = 1, reset = false) {
            showLoader();
            let search = $('#title').val();
            $.ajax({
                url: "{{route('books-data')}}",
                data: {
                    page: page,
                    search: search,
                },
                type: "GET",
                dataType: "json",
                success: function (result) {
                    hideLoader();
                    let contacts = result.data;
                    let html = '';
                    if (contacts.length > 0) {
                        $('#btn-load-more').data('page', page);
                        $.each(contacts, function (ket, row) {
                            let bookImageUrl = "";
                            if(row.book_image != '' && row.book_image != null){
                                bookImageUrl = '{{asset('uploads/book-images')}}/'+row.book_image;
                            } else {
                                bookImageUrl = '{{asset('uploads/no-photo-available.png')}}';
                            }
                            html += `
                          <div class="col-md-3 pt-3">
                            <div class="card p-3">
                                <div class="row">
                                  <div class="col-md-12">
                                    <a href="${baseUrl('book-chapters/' + row.id)}" alt="book detail">
                                     <img src="${bookImageUrl}"
                                                     style="width: 100%;height: 250px" class="img img-responsive"
                                                     alt="">
                                    </a>
                                   </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3>${(row.title != '' && row.title != null) ? row.title.substring(0,15) :''}</h3>
                                    </div>
                                    <div class="col-md-12">
                                        <h5></h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">

                                    </div>
                                    <div class="col-md-2">
                                        <div class="dropdown">
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)"
                                               aria-expanded="true">
                                                <i class="fa fa-sliders" aria-hidden="true"></i></a>
                                            <div class="dropdown-menu">
                                               <a href="${baseUrl('book-chapters/' + row.id)}" alt="book detail" class="dropdown-item"><i class="fa fa-eye"></i> View Detail </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                          </div>
                        `;
                        });
                        if (reset === true) {
                            $('#result').empty().append(html)
                        } else {
                            $('#result').append(html);
                        }
                    } else {
                        notifyError('No Records Found !');
                    }
                }
            });
        }
    </script>
@endpush
