<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class UpdateStudentSignOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:signout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $to_name = 'RECEIVER_NAME';
        $to_email = 'test@gmail.com';
        $data = array('name'=>'Cloudways (sender_name)', 'body' => 'A test mail');
        Mail::send([], $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                ->subject('Laravel Test Mail')
                ->from('example@gmail.com');
        });

        $allTodayAttendance = Attendance::whereDate('sign_in',date('Y-m-d'))->limit(100)->get();
        if($allTodayAttendance->isNotEmpty()){
            foreach ($allTodayAttendance as $attendance) {
                $currentDateTime = date('Y-m-d H:i:s');
                $totalHours = calculateHoursBetweenDates($attendance->sign_in,$currentDateTime);
                if($totalHours == 1){
                    Attendance::where('id',$attendance->id)->update(['sign_out' => $currentDateTime]);
                }
            }
        }
    }
}
