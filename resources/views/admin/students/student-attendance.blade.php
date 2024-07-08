@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Student Attendance</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Student Attendance</li>
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
                        <form action="{{route('admin.student-attendance-search',[$user->id])}}" method="POST"
                              id="student-attendance-search">

                            <div class="card ">
                                <div class="card-header">
                                    <h3 class="card-title">Search Filter</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool " data-card-widget="collapse"
                                                title="Collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body collapse" id="">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="report_type">Report Type</label>
                                                {{--<input type="text" name="title" id="title"
                                                       placeholder="Book Title" class="form-control">--}}
                                                <select name="report_type" id="report_type" class="form-control">
                                                    <option value="monthly" {{$reportType == 'monthly'?"selected":""}} >
                                                        Monthly
                                                    </option>
                                                    <option value="yearly" {{$reportType == 'yearly'?"selected":""}}>
                                                        Yearly
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row"
                                                 style="display: {{$reportType == 'monthly'?"flex":"none"}}">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="month_from">Month From</label>
                                                        <input type="month" name="month_from" id="month_from"
                                                               placeholder="Month From" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="month_to">Month To</label>
                                                        <input type="month" name="month_to" id="month_to"
                                                               placeholder="Month To" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="display: {{$reportType == 'yearly'?"flex":"none"}}">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="year_from">Year From</label>
                                                        <select name="year_from" id="year_from" class="form-control">
                                                            @foreach(range(date('Y'), 2000) as $year)
                                                            <option value="{{$year}}">{{$year}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="year_to">Year To</label>
                                                        <select name="year_to" id="year_to" class="form-control">
                                                            @foreach(range(date('Y'), 2000) as $year)
                                                                <option value="{{$year}}">{{$year}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary search-btn">
                                        <span>Search </span>
                                    </button>
                                    <a href="{{route('admin.student-attendance',$user->id)}}" class="btn btn-default">Reset</a>
                                </div>

                            </div>
                            <!-- /.card -->

                    </form>

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
                            <h4 class="p-2">Student Name: {{$user->fullName}} {{$reportType}}</h4>
                            <table id="studentTable" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Year</th>
                                    @if($reportType == 'monthly')
                                        <th>Month</th> @endif
                                    <th>Total Attendance</th>
                                </tr>
                                </thead>
                                <tbody id="result">
                                @forelse($studentAttendances as $attendance)
                                    <tr>
                                        <td>{{$attendance->year}}</td>
                                        @if($reportType == 'monthly')
                                            <td>{{date("F", mktime(0, 0, 0, $attendance->month, 10))}}</td>@endif
                                        <td>{{$attendance->total_attendance}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">No Records Found</td>
                                    </tr>
                                @endforelse
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
        $(document).on('change', '#report_type', function () {
            let reportType = $(this).val();
            if (reportType === 'yearly') {
                window.location.href = "{{route('admin.student-attendance',[$user->id,'report-type' => 'yearly'])}}"
            } else if (reportType === 'monthly') {
                window.location.href = "{{route('admin.student-attendance',[$user->id,'report-type' => 'monthly'])}}"
            }
            console.log(reportType);
        });

        $(document).on('submit', '#student-attendance-search', function (e) {
            e.preventDefault();

            $(this).find('button[type="submit"]').append(' <i class="fa fa-spinner fa-spin"></i>');
            $(this).find('button[type="submit"]').prop('disabled', true);

            $(this).ajaxSubmit({
                success:function(result){
                    let rows = '';
                    if(result.status === true){
                        //monthly report
                        if(result.report_type === 'monthly'){
                            if(result.result.length > 0){
                                $.each(result.result,function (key,row) {
                                    rows += `<tr><td>${row.year}</td><td>${row.month}</td><td>${row.total_attendance}</td></tr> `;
                                });
                            } else {
                                rows = '<td colspan="3">No Records Found</td>';
                            }
                        } else if(result.report_type === 'yearly'){ // yearly report
                            if(result.result.length > 0){
                                $.each(result.result,function (key,row) {
                                    rows += `<tr><td>${row.year}</td><td>${row.total_attendance}</td></tr> `;
                                });
                            } else {
                                rows = '<td colspan="2">No Records Found</td>';
                            }
                        }
                    } else {
                        rows = '<td colspan="3">No Records Found</td>';
                    }
                    $('#result').html(rows);
                },
                error: function (jqXHR, textStatus, errorThrown, form) {
                    $(form).find(':input.is-invalid').removeClass('is-invalid');
                    $(form).find('.error').remove();

                    if (jqXHR.status == 422) {
                        //notifyError(Object.values(jqXHR.responseJSON.errors)[0]);
                        $.each(jqXHR.responseJSON.errors, function (key, item) {

                            $(form).find(':input[name="' + key + '"]').addClass('is-invalid');

                            var field = $(form).find(':input[name="' + key + '"]');
                            $("<span style='color:red;padding-top:2px' class='error'>" + item + "</span>").insertAfter(field);

                        });

                        if ($(form).find(':input.is-invalid:first').length > 0) {
                            $(form).find(':input.is-invalid:first').focus().scroll();

                            var new_position = $(form).find(':input.is-invalid:first').offset().top - 200;

                            $('html, body').animate({scrollTop: new_position}, 500);
                        }
                    } else {
                        notifyError(errorThrown);

                    }
                },
                complete: function (jqXHR, textStatus, form) {
                    form.find('button[type="submit"]').prop('disabled', false);
                    form.find('button[type="submit"] i.fa-spin').remove();

                    if (textStatus == 'success') {
                        // form[0].reset();
                        form.find(':input.is-invalid').removeClass('is-invalid');
                        form.find('.error').remove();
                    }
                }
            });
        });
    </script>
@endpush
