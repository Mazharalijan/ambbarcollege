@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Attendance List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Attendance List</li>
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
                                <a href="{{route('admin.attendanceform')}}" class="add_advertiser">
                                    <i class="fas fa-list"></i>Add Student Previous Attendance
                                </a>
                            </h3>
                            {{--  @if(auth()->user()->hasRole('student'))
                            <h3 class="card-title float-right">
                                <a href="{{route('admin.attendanceform')}}" class="add_advertiser">
                                    <i class="fas fa-list"></i>Print Student Attendance
                                </a>
                            </h3>
                            @endif  --}}


                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="attendanceTable" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Hours</th>
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
    {{--  <script>
        let columns = [
            {
                data: 'student_name',
            },
            {
                data: 'formatted_sign_in_date',
                name:"sign_in",
            },
            {
                data: 'formatted_sign_out_date',
                name:"sign_out",
            },
            {
                data: 'total_hours',
            },

        ];

        ajaxDataTable('#attendanceTable',
            "{{ route('admin.attendance-data') }}", columns, [2]);

    </script>  --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#attendanceTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("admin.attendance-data") }}',
                    type:'GET',
                    data: function (d) {
                        // Add additional parameters here if needed
                        d.search.value = $('input[type="search"]').val();
                    }
                },
                columns: [
                    {
                        data: 'student_name',
                        name:'student_name',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'formatted_sign_in_date',
                        name:"sign_in",
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'formatted_sign_out_date',
                        name:"sign_out",
                    },
                    {
                        data: 'total_hours',
                    },
                    // Add more columns as needed
                ],
                search: {
                    regex: true // Enable regex search if needed
                },

            });

        });
    </script>

@endpush
