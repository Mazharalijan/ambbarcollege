<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $folderLink = 'frontend.dashboard.';
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['books_count'] = auth()->user()->books->count();
         $attendance = Attendance::where('user_id',auth()->id())->whereDate('sign_in',date('Y-m-d'))->first();
          
        if(empty($attendance)){
            $attendance = Attendance::create(
                [
                    'user_id' => auth()->id(),
                    'sign_in' => date('Y-m-d H:i:s'),
                ]);
        }
         if ($attendance) {
            //session()->flash('success','Submit Successfully');
            $response = ['status' => true, 'message' => 'Submit Successfully', 'redirect' => route('dashboard')];

        } else {
            //session()->flash('error','Something went wrong.Please try again.');
            $response = ['status' => false, 'message' => 'Something went wrong.Please try again.'];
        }
        $data['todayAttendance'] =$attendance;
       
       
        return view($this->folderLink."index",$data);
    }

    /**
     * submit attendance
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAttendance(Request $request){
        $attendance = Attendance::where('user_id',auth()->id())->whereDate('sign_in',date('Y-m-d'))->first();
        if(empty($attendance)){
            $attendance = Attendance::create(
                [
                    'user_id' => auth()->id(),
                    'sign_in' => date('Y-m-d H:i:s'),
                ]);
        }
        if ($attendance) {
            session()->flash('success','Submit Successfully');
            $response = ['status' => true, 'message' => 'Submit Successfully', 'redirect' => route('dashboard')];

        } else {
            session()->flash('error','Something went wrong.Please try again.');
            $response = ['status' => false, 'message' => 'Something went wrong.Please try again.'];
        }
        if ($request->ajax()){
            return response()->json($response);
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * check user sign in
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUserSignIn(Request $request){
        $todayAttendance = Attendance::where('user_id',auth()->id())->whereDate('sign_in',date('Y-m-d'))->WhereNull('sign_out')->first();
        if ($todayAttendance){
            $response = ['status' => true,'sign_in_datetime' => date('D d M, Y h:i A ',strtotime($todayAttendance->sign_in))];
        } else{
            $response = ['status' => false,'message' => 'Something went wrong.Please try again.'];
        }
        return response()->json($response);
    }
}
