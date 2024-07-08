@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Student List</h1>
                    </div>
                    <div class="col-sm-6">
                        {{session('message')}}
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Student List</li>
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
                                <a href="{{ route('admin.students.create') }}" class="add_advertiser">
                                    <i class="fas fa-plus-circle"></i> Add Student
                                </a>
                            </h3>
                            <div class="card-title text-right float-right">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                                    <i class="fas fa-plus-circle mr-1"></i> Import Students
                                  </button>
                            </div>

                            <!--Import Students Modal -->
                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Import Students</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="/admin/import-students" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <p class="text-orange">Please select  a CSV file (.csv) to import</p>
                                            <input type="file" class="my-3" id="fileInput" name="fileInput" accept=".xlsx, .xls, .csv">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Import</button>
                                        </div>
                                    </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            @if (session('message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{session('message')}}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @endif

                            <table id="studentTable" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    {{--<th>Level</th>--}}
                                    {{--<th>Language</th>--}}
                                    <th>Assign Chapter</th>
                                    <th>Attendance</th>
                                    <th>Action(s)</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


@endsection
@push('scripts')
    <script>
        let columns = [
            {
                data: 'username',
            },
            {
                data: 'first_name',
            },
            {
                data: 'last_name',
            },
            {
                data: 'phone_number',
            },
            {
                data: 'email',
            },
            /*{
                data: 'student_level',
            },*/
            /*{
                data: 'language',
            },*/
            {
                data: 'assign-chapter',
            },
            {
                data: 'student-attendance',
            },
            {
                data: 'actions'
            },

        ];

        ajaxDataTable('#studentTable',
            "{{ route('admin.students-data') }}", columns, [6]);

    </script>
@endpush
