@extends('layout.master')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{$books_count}}</h3>

                                <p>Total Books</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-book"></i>
                            </div>
                            <a href="{{route('admin.books.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{$activeStudentsCount}}</h3>
                                <p>Active Students</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-user"></i>
                            </div>
                            <a href="{{route('admin.attendances.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{$studentsCount}}</h3>
                                <p>Total Students</p>
                            </div>
                            <div class="icon">
                            {{--<i class="ion ion-person-add"></i>--}}
                                <i class="fa fa-user-plus"></i>
                            </div>
                            <a href="{{route('admin.students.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->

                    <!-- ./col -->
                </div>
                <!-- /.row -->
                <div class="row">
                    <h4>Latest Attendance</h4>
                    <div class="col-md-12">
                        <table class="table">
                            <tr>
                                <td>Student</td>
                                <td>Check In</td>
                                <td>Check Out</td>
                                <td>Hours</td>
                            </tr>
                            @isset($latestAttendances)
                            @forelse($latestAttendances as $attendances)
                            <tr>
                                <td>
                                   {{ $attendances->student ? $attendances->student->fullName : ""}}
                                </td>
                                <td>{{(!empty($attendances->sign_in))?date('D d M, Y h:i A ',strtotime($attendances->sign_in)) : ""}}</td>
                                <td>{{(!empty($attendances->sign_out))?date('D d M, Y h:i A ',strtotime($attendances->sign_out)) : ""}}</td>
                                <td>
                                    @php
                                        $total = "0";
                                            if (!empty($attendances->sign_out) && !empty($attendances->sign_out)){
                                                 $totalHours = calculateHoursBetweenDates($attendances->sign_in,$attendances->sign_out);
                                                if ($totalHours < 1){
                                                    $totalMinutes  = calculateMinutesBetweenDates($attendances->sign_in,$attendances->sign_out);
                                                    $total = ($totalMinutes == 1) ? $totalMinutes." Minute":$totalMinutes." Minutes";
                                                } else {
                                                    $total = ($totalHours == 1) ? $totalHours." Hour":$totalHours." Hours";
                                                }
                                            }
                                    @endphp
                                    {{$total}}
                                </td>
                            </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">No Records !</td>
                                    </tr>
                            @endforelse
                            @endisset
                        </table>
                    </div>
                </div>
                <!-- Main row -->

            <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
