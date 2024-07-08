<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class CronJobController extends Controller
{
    public function updateCheckOut(){
        $allTodayAttendance = Attendance::whereDate('sign_in',date('Y-m-d'))->limit(200)->get();
        if($allTodayAttendance->isNotEmpty()){
            foreach ($allTodayAttendance as $attendance) {
                $currentDateTime = date('Y-m-d H:i:s');
                $totalHours = calculateHoursBetweenDates($attendance->sign_in,$currentDateTime);
                if($totalHours == 4){
                    Attendance::where('id',$attendance->id)->update(['sign_out' => $currentDateTime]);
                }
            }
        } else{
            echo "No Records";
        }
    }
}
