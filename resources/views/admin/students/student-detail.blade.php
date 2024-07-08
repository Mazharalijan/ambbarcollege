@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Student Detail</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Student Detail</li>
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
                                <a href="{{ route('admin.students.index') }}" class="">
                                    <i class="fas fa-list"></i> Students
                                </a>
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Username</th>
                                    <td>{{$student->username}}</td>
                                </tr>
                                <tr>
                                    <th>First Name</th>
                                    <td>{{$student->first_name}}</td>
                                </tr>
                                <tr>
                                    <th>Last Name</th>
                                    <td>{{$student->last_name}}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{$student->email}}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{$student->phone_number}}</td>
                                </tr>
                                <tr>
                                    <th>Level</th>
                                    <td>Level {{$student->level}}</td>
                                </tr>
                            </table>
                            <h4>Assign Books</h4>
                            <div class="row" id="result"></div>
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
                url: "{{route('admin.student-books-data',[$student->id])}}",
                data: {
                    page: page,
                    search: search,
                },
                type: "GET",
                dataType: "json",
                success: function (result) {
                    hideLoader();
                    let studentBooks = result.data;
                    let html = '';
                    if (studentBooks.length > 0) {
                        $('#btn-load-more').data('page', page);
                        $.each(studentBooks, function (ket, row) {
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
                                    <a href="${baseUrl('admin/book-chapters/' + row.id)}" alt="book detail">
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
                                               <a href="${baseUrl('admin/book-chapters/' + row.id)}" alt="book detail" class="dropdown-item"><i class="fa fa-eye"></i> View Detail </a>
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
                        notifyError('No Books Found !');
                    }
                }
            });
        }
    </script>
    </script>
@endpush
