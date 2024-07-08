@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Attendance</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Add Attendance</li>
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
                                <a href="{{route('admin.attendances.index')}}" class="add_advertiser">
                                    <i class="fas fa-list"></i> Attendance List
                                </a>
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form role="form" method="post" action="{{route('admin.attendances.store')}}"
                                  class="ajax-form" enctype="multipart/form-data">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="user_id"> Student </label>
                                        <select name="user_id" id="user_id" class="form-control" >
                                            <option value=""> Select Student</option>
                                            @forelse($students as $student)
                                                <option value="{{$student->id}}">{{$student->fullName}}</option>
                                                @empty
                                                <option value="">No Students</option>
                                            @endforelse
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Check In </label>
                                        <input type="datetime-local" name="sign_in" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Check Out </label>
                                        <input type="datetime-local" name="sign_out" class="form-control">
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <!----> <span>Add </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
