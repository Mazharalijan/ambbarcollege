@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Profile</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Profile</li>
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
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form role="form" method="post" action="{{route('admin.profile.update',auth()->id())}}" class="ajax-form" enctype="multipart/form-data">
                                @method('PUT')
                                <input type="hidden" name="old_image" value="{{auth()->user()->profile_image ?? ''}}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="username">Username </label>
                                                <input type="text" name="username" id="username" value="{{auth()->user()->username}}" placeholder="Username" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="first_name">First Name </label>
                                                <input type="text" name="first_name" id="first_name" value="{{auth()->user()->first_name}}" placeholder="First Name" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="last_name">Last Name </label>
                                                <input type="text" name="last_name" id="last_name" value="{{auth()->user()->last_name}}" placeholder="Last Name" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="retype_password">Email</label>
                                                <input type="text"  placeholder="Email" value="{{auth()->user()->email}}" disabled class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="phone_number">Phone Number</label>
                                                <input type="text" name="phone_number" id="phone_number"  placeholder="Phone Number" value="{{auth()->user()->phone_number}}"  class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="image">Profile Image</label>
                                                <input type="file" name="image" id="image" class="">
                                                <img src="{{asset('uploads/profile/'.auth()->user()->profile_image)}}" class="img img-circle img-responsive" style="width: 120px;height: 100px;"  alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">
                                        <!----> <span>Update </span>
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
@push('scripts')

@endpush
