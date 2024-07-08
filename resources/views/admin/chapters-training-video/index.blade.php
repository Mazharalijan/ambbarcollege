@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Chapter Training Video List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Chapter Training Video List</li>
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
                                                <label for="title">Chapter</label>
                                                <input type="text" name="title" id="title"
                                                       placeholder="Chapter" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="button" onclick="loadMore(1,true)" class="btn btn-primary search-btn">
                                    <span>Search </span>
                                </button>
                                <a href="{{route('admin.chapters.index')}}" class="btn btn-default">Reset</a>
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
                                <a href="{{ route('admin.training-video.create') }}" class="add_advertiser">
                                    <i class="fas fa-plus-circle"></i> Add Chapter Training Video
                                </a>
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
                url: "{{route('admin.chapters-data')}}",
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
                                    <a href="${baseUrl('admin/training-video/' + row.id)}" alt="chapter detail">
                                     <img src="${bookImageUrl}"
                                                     style="width: 100%;height: 250px" class="img img-responsive"
                                                     alt="">
                                    </a>
                                   </div>
                                </div>
                                <div class="row">

                                     <div class="col-md-12">
                                        <h5>${(row.chapter != '' && row.chapter != null) ? row.chapter :''}</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                       ${(row.description != '' && row.description != null) ? row.description.substring(0,25) :''}
                                    </div>
                                    <div class="col-md-2">
                                        <div class="dropdown">
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)"
                                               aria-expanded="true">
                                                <i class="fa fa-sliders" aria-hidden="true"></i></a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item " href="${baseUrl('admin/training-video/' + row.id + '/edit')}" ><i class="fa fa-pen" aria-hidden="true"></i> Edit</a>

                                               <a href="${baseUrl('admin/training-video/' + row.id)}" alt="chapter detail" class="dropdown-item"><i class="fa fa-eye"></i> View Detail </a>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                          </div>
                        `;
                            /*<form action="${baseUrl('admin/training-video/' + row.id)}" class="ajax-form" method="POST">
                                                     <input type="hidden" name="_method" value="DELETE">
                                                     <button type="submit" class="dropdown-item"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
                                                </form>*/
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
