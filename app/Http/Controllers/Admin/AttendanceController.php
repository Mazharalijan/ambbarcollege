<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Pdf;

class AttendanceController extends Controller
{
    protected $folderLink = 'admin.attendance.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->folderLink.'index');
    }

    /**
     * get attendance data
     *
     * @return mixed
     *
     * @throws \Exception
     */
    // public function attendanceData()
    // {
    //     $query = Attendance::whereHas('student', function ($q) {
    //         $q->where('username', '!=', null);
    //     })->get();

    //     return DataTables::of($query)
    //         ->filter(function ($query) use ($request) {
    //             if ($request->has('search') && ! empty($request->search['value'])) {
    //                 $searchValue = $request->search['value'];
    //                 $query->where(function ($query) use ($searchValue) {
    //                     $query->where('username', 'like', "%{$searchValue}%");

    //                 });
    //             }
    //         })->make(true);

    //     // return datatables()->of(Attendance::whereHas('student',function($q){
    //     //     $q->where('username','!=', NULL);
    //     // })->get())
    //     //     ->addColumn('student_name', function ($model) {
    //     //         return ($model->student)?$model->student->fullName : "";
    //     //     })
    //     //     ->addColumn('formatted_sign_in_date', function ($model) {
    //     //         return (!empty($model->sign_in))?date('D d M, Y h:i A ',strtotime($model->sign_in)) : "";
    //     //     })
    //     //     ->addColumn('formatted_sign_out_date', function ($model) {
    //     //         return (!empty($model->sign_out))?date('D d M, Y h:i A ',strtotime($model->sign_out)) : "";
    //     //     })
    //     //     ->addColumn('total_hours', function ($model) {
    //     //         $checkInDateTime = Carbon::parse($model->sign_in);
    //     //         $checkOutDateTime = Carbon::parse($model->sign_out);
    //     //         $totalHours  = $checkInDateTime->diffInHours($checkOutDateTime);
    //     //         if ($totalHours < 1){
    //     //             $totalMinutes  = $checkInDateTime->diffInMinutes($checkOutDateTime);
    //     //             $total = ($totalMinutes == 1) ? $totalMinutes." Minute":$totalMinutes." Minutes";
    //     //         } else {
    //     //             $total = ($totalHours == 1) ? $totalHours." Hour":$totalHours." Hours";
    //     //         }
    //     //         return (!empty($model->sign_out) && !empty($model->sign_out))?$total : "0";
    //     //     })

    //     //     ->toJson();

    // }

    public function attendanceData(Request $request)
    {

        //Log::info('Request Info', $request->all());

        $query = Attendance::whereHas('student', function ($q) {
            $q->where('username', '!=', null);
        });

        // Apply search filter
        if ($request->has('search') && ! empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $query->whereHas('student', function ($q) use ($searchValue) {
                $q->where('username', 'like', "%{$searchValue}%");
            });
            $query = $query->get();
        }

        return DataTables::of($query)
            ->addColumn('student_name', function ($model) {
                return ($model->student) ? $model->student->fullName : '';
            })
            ->addColumn('formatted_sign_in_date', function ($model) {
                return (! empty($model->sign_in)) ? date('D d M, Y h:i A', strtotime($model->sign_in)) : '';
            })
            ->addColumn('formatted_sign_out_date', function ($model) {
                return (! empty($model->sign_out)) ? date('D d M, Y h:i A', strtotime($model->sign_out)) : '';
            })
            ->addColumn('total_hours', function ($model) {
                $checkInDateTime = Carbon::parse($model->sign_in);
                $checkOutDateTime = Carbon::parse($model->sign_out);
                $totalHours = $checkInDateTime->diffInHours($checkOutDateTime);
                if ($totalHours < 1) {
                    $totalMinutes = $checkInDateTime->diffInMinutes($checkOutDateTime);
                    $total = ($totalMinutes == 1) ? $totalMinutes.' Minute' : $totalMinutes.' Minutes';
                } else {
                    $total = ($totalHours == 1) ? $totalHours.' Hour' : $totalHours.' Hours';
                }

                return (! empty($model->sign_out) && ! empty($model->sign_out)) ? $total : '0';
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['students'] = $users = User::whereHas('role', function ($q) {
            $q->where('slug', 'student');
        })->select('id', 'first_name', 'last_name', 'username')->get();

        return view($this->folderLink.'create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attendanceData = $request->all();
        $validation = [
            'user_id' => 'required',
            'sign_in' => 'required',
            'sign_out' => 'required',
        ];
        $messages = [
            'user_id.required' => 'Please select student',
            'sign_in.required' => 'Please select check in',
            'sign_out.required' => 'Please select check out',
        ];
        $validator = Validator::make($attendanceData, $validation, $messages);
        $validator->after(function ($validator) use ($request) {
            $sign_in = strtotime($request->sign_in);
            $sign_out = strtotime($request->sign_out);
            if ($sign_in > $sign_out) {
                $validator->errors()->add('sign_out', 'Check out should be greater than check in.');
            }
        });
        if ($validator->fails()) {
            $validator->validate();
        }
        $attendance = Attendance::create($attendanceData);

        if ($attendance) {
            $response = ['status' => true, 'message' => 'Added Successfully'];
        } else {
            $response = ['status' => false, 'message' => 'Something went wrong.Please try again.'];
        }

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function PreAttendanceForm()
    {
        $users = User::where('role_id', 3)->orderBy('first_name', 'ASC')->get();
        $data = compact('users');

        return view($this->folderLink.'previous_record')->with($data);
    }

    public function storedata(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'student' => 'required',
            'to_date' => 'required',
            'from_date' => 'required',
            'hours' => 'required',

        ]);
        $id = $request->student;
        if ($validator->passes()) {
            $old_to_date = $request->to_date;
            $to_middle = strtotime($old_to_date);
            $new_to_date = date('Y-d-m H:i:s', $to_middle);

            $old_from_date = $request->from_date;
            $from_middle = strtotime($old_from_date);
            $new_from_date = date('Y-d-m H:i:s', $from_middle);

            $todate = Carbon::createFromFormat('Y-d-m H:i:s', $new_to_date);
            $fromdate = Carbon::createFromFormat('Y-d-m H:i:s', $new_from_date);

            $period = CarbonPeriod::create($fromdate, $todate);
            $days = $request->days;
            $period2 = [];
            foreach ($period as $date) {
                $a = $date->format('D');
                foreach ($days as $day) {
                    if ($a == $day) {
                        $todate2 = $date->format('Y-m-d H:i:s');
                        $todate3 = Carbon::createFromFormat('Y-m-d H:i:s', $todate2)->addHours($request->hours);
                        $period3[] = [
                            'sign_in' => $date->format('Y-m-d H:i:s'),
                            'sign_out' => $todate3->format('Y-m-d H:i:s'),
                            'user_id' => $request->student,
                            'created_at' => Carbon::now(),
                        ];
                    }
                }

            }
            $attendance = Attendance::insert($period3);

            if ($attendance) {
                $response = ['status' => true, 'message' => 'Added Successfully'];
            } else {
                $response = ['status' => false, 'message' => 'Something went wrong.Please try again.'];
            }

            return response()->json($response);
            //return $period3;

        } else {
            $response = ['status' => false, 'message' => 'Something went wrong.Please try again.'];

            return response()->json($response);
        }

    }

    public function demo()
    {

        $attendance = Attendance::where('user_id', 194)->get();

        $data = [];
        foreach ($attendance as $att) {
            $date1 = new \DateTime($att->sign_in);
            //  print_r($date1);
            $date2 = new \DateTime($att->sign_out);
            //  print_r($date2);
            $diff = $date1->diff($date2);

            $data[] = $diff->format('%h');
        }

        return $data;
        //         $start = Carbon::parse("2022-08-19 11:44:03");
        //         $end = Carbon::parse("2022-08-19 02:44:03");

        //         $total = $end->diffInHours($start);

        // echo $total;
    }

    public function attendancePdf()
    {
        try {
            if (auth()->user()->role_id == 3) {
                $studentInfo = [
                    'student_name' => auth()->user()->first_name.' '.auth()->user()->last_name,
                    'student_email' => auth()->user()->email,
                    'student_phone' => auth()->user()->phone_number,
                ];

                // // Here you should fetch the attendance records of the student
                $attendanceRecords = Attendance::where('user_id', auth()->user()->id)->get();

                // return view('admin.attendance.attendance_pdf', [
                //     'studentInfo' => $studentInfo,
                //     'attendanceRecords' => $attendanceRecords,
                // ]);
                // // Generate PDF
                $pdf = PDF::loadView('admin.attendance.attendance_pdf', [
                    'studentInfo' => $studentInfo,
                    'attendanceRecords' => $attendanceRecords,
                ]);

                // Return the generated PDF
                return $pdf->download('attendance.pdf');
                // return auth()->user();
            } else {
                return redirect('admin/attendances')->with(['success' => false, 'message' => 'Only students can print their attendance']);
            }
        } catch (\Exception $error) {
            return response()->json(['status' => 500, 'message' => $error->getMessage()]);
        }
    }
}
