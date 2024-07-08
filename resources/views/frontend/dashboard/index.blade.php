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
                @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{session()->get('success')}}
                    </div>
                @elseif(session()->has('error'))
                    <div class="alert alert-danger">
                        {{session()->get('error')}}
                    </div>
                @endif
                <div class="row">

                    <div class="col-lg-6">
                        <form action="{{route('submit-attendance')}}" method="post" class="ajax-form" >
                           
                            @csrf
                        <table class="table table-active">
                            <tr>
                                <th>Date</th>
                                <td>{{date('Y-m-d')}}</td>
                            </tr>
                            @if(empty($todayAttendance))
                                <!--<tr>-->
                                <!--    <th>Check In : ( {{date('Y-m-d h:i A')}} )</th>-->
                                <!--    <td><button type="submit" class="btn btn-primary">Check In</button></td>-->
                                <!--</tr>-->
                                @else
                                <tr>
                                    <th>Check In Date : </th>
                                    <td> {{date('D d M, Y',strtotime($todayAttendance->sign_in))}} </td>
                                </tr>
                                <tr>
                                    <th>Check In Time: </th>
                                    <td>{{date('h:i A',strtotime($todayAttendance->sign_in))}}</td>
                                </tr>
                                @if(!empty($todayAttendance->sign_out))
                                    <tr>
                                        <th>Check Out Time: </th>
                                        <td>{{date('h:i A',strtotime($todayAttendance->sign_out))}}</td>
                                    </tr>
                                @endif
                               
                           @endif
                        </form>
                        <!--@if(!empty($todayAttendance))-->
                        <!--    @if(empty($todayAttendance->sign_out))-->
                        <!--        <tr>-->
                        <!--            <th><input type="text" hidden name="is_sign_out" value="1"></th>-->
                        <!--            <td><a class="btn btn-primary text-white" id="check-out-modal" >Check Out</a></td>-->
                        <!--        </tr>-->
                        <!--    @endif-->
                        <!--@endif-->
                        </table>
                    </div>
                </div>
                {{-- checkout modal --}}
                <div class="modal fade" id="confirm-check-out-modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post" action="{{route('logout')}}">
                                @csrf
                                <h1 class="text-center mt-4">Do you want to checkout?</h1>
                                <div class="modal-body">
                                    <!--<div class="row">-->
                                    <!--    <div class="col-md-6">-->
                                    <!--        <div class="form-group">-->
                                    <!--            <input type="text" hidden name="is_sign_out" id="is_sign_out" value="1"/>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-warning text-white">Logout</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                {{-- checkout modal end --}}
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
                            <a href="{{route('books')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->


                    <!-- ./col -->
                </div>
                <!-- /.row -->
                <!-- Main row -->

            <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $(document).on('click','#check-out-modal',function(){
            $('#confirm-check-out-modal').modal('show');
        })
    })
</script>
