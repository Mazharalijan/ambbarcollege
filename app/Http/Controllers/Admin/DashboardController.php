<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    protected $folderLink = 'admin.dashboard.';
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
        
        $users = User::whereHas('role', function ($q) {
            $q->where('slug','student');
        })->select('id');
        $activeUsers = 0;
        if ($users->get()->isNotEmpty()){
            foreach ($users->get() as $user) {
                if (Cache::has('user-is-online-' . $user->id))
                    $activeUsers++;
            }
        }
        $data['studentsCount'] = $users->count();
        $data['activeStudentsCount'] = $activeUsers;
        $data['books_count'] = Book::select('id')->whereNull('parent_id')->count();
        $data['latestAttendances'] = Attendance::with('student')->latest()->limit(10)->get();
        return view($this->folderLink."index",$data);
    }

    public function test(){
        $to_name = 'RECEIVER_NAME';
        $to_email = 'test@gmail.com';
        $data = array('name'=>'Cloudways (sender_name)', 'body' => 'A test mail');
        Mail::send([], $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                ->subject('Laravel Test Mail')
                ->from('example@gmail.com');
        });
    }

}
