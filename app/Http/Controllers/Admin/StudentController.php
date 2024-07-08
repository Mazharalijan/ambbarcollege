<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\AttendanceImport;
//use App\Imports\StudentsImport;
use App\Imports\StudentsImport;
use App\Models\Attendance;
use App\Models\Book;
use App\Models\Language;
use App\Models\User;
use App\Models\UserChapter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Pdf;

class StudentController extends Controller
{
    protected $folderLink = 'admin.students.';

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
     * get dashboards list
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function studentData(Request $request)
    {
        $users = User::whereHas('role', function ($q) {
            $q->where('slug', 'student');
        })->orderBy('id', 'DESC');

        return datatables()->of($users)
            ->addColumn('full_name', function ($model) {
                return $model->fullName;
            })
            /*->addColumn('student_level', function ($model) {
                return "Level ".$model->level;
            })
            ->addColumn('language', function ($model) {
                return ($model->language()->exists())?$model->language->language:"";
            })*/
            ->addColumn('actions', function ($model) {
                return '
                <a href="'.route('admin.students.edit', [$model->id]).'" class="btn btn-primary"><i class="fa fa-pen-alt"></i> </a>
                 <form action="'.route('admin.students.destroy', [$model->id]).'" method="POST" class="ajax-form">
                                    '.method_field('DELETE').'
                                    '.csrf_field().'
                     <button class="btn btn-danger" type="submit"><i class="fa fa-trash" aria-hidden="true"></i> </button>
               </form>
                <a href="'.route('admin.students.show', [$model->id]).'" alt="Student Detail" class="btn btn-primary"><i class="fa fa-eye"></i> </a>

';
            })
            ->addColumn('assign-chapter', function ($model) {
                return '<a href="'.route('admin.assign-chapter', $model->id).'" alt="Student Attendance" class="btn btn-primary"><i class="fa fa-book"></i> </a>
';
            })
            ->addColumn('student-attendance', function ($model) {
                return '<a href="'.route('admin.print-attendance', [$model->id]).'" alt="Student Attendance" class="btn btn-primary"><i class="fa fa-book-open"></i> </a>
';
            })
            ->orderColumn('id', 'DESC')
            ->rawColumns(['actions', 'student-attendance', 'assign-chapter'])
            ->toJson();
    }

    public function assignChapter($id)
    {
        $student = User::find($id);
        $books = Book::whereHas('students', function ($q) use ($id) {
            $q->where('user_id', $id);
        })->get();
        //    $chapters = Book::whereHas('assigned_students', function($q) use($id) {
        //         $q->where('user_id', $id);
        //     })->paginate(4);

        $chapterData = UserChapter::where('user_id', $id)->get();

        $chapterIds = $chapterData->where('book_id', '!=', null)->pluck('book_id')->toArray();

        $bookIds = $chapterData->where('parent_id', '!=', null)->pluck('parent_id')->toArray();

        $allRecord = array_merge($chapterIds, $bookIds);

        $chapters = Book::whereIn('id', $allRecord)->paginate(10);

        return view('admin.students.assign-chapter', compact('books', 'student', 'chapters'));
    }

    public function bookChapter($id)
    {
        $chapters = Book::where('parent_id', $id)->get();

        return $chapters;
    }

    public function assingBookChpater(Request $request)
    {
        $request->validate([
            'book_id' => 'required',
            'chpater_ids' => 'required',
        ]);
        if (in_array('all', $request->chpater_ids)) {

            UserChapter::where('user_id', $request->user_id)->where('book_parent_id', $request->book_id)->delete();
            UserChapter::insert([
                [
                    'user_id' => $request->user_id,
                    'parent_id' => $request->book_id,
                    'all_chapters' => 1,
                ],
            ]);
        } else {

            UserChapter::where('user_id', $request->user_id)->where('parent_id', $request->book_id)->where('all_chapters', 1)->delete();

            for ($i = 0; $i < count($request->chpater_ids); $i++) {
                UserChapter::insert([
                    [
                        'user_id' => $request->user_id,
                        'book_id' => $request->chpater_ids[$i],
                        'book_parent_id' => $request->book_id,
                    ],
                ]);
            }
        }

        return redirect()->route('admin.assign-chapter', $request->user_id);
    }

    public function destroyChapter(Request $request)
    {
        $studentChapter = UserChapter::where('user_id', $request->user_id)->where($request->type, $request->book_id)->first();
        $studentChapter->delete();

        return redirect()->back();
    }

    /**
     * get books by language
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBooksByLanguage(Request $request)
    {
        $result = Language::find($request->languageId)->books;

        return response()->json([
            'status' => true,
            'result' => $result,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['languages'] = Language::all();
        $data['books'] = Book::whereNull('parent_id')->orderBy('title', 'ASC')->get();

        return view($this->folderLink.'create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $studentData = $request->all();
        $validation = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            //'language_id' => 'required',
        ];
        $messages = [
            'first_name.required' => 'Please enter first name',
            'last_name.required' => 'Please enter last name',
            'email.required' => 'Please enter email',
            'password.required' => 'Please enter password',
            //'language_id.required' => 'Please select language',
        ];
        $validator = Validator::make($studentData, $validation, $messages);
        if ($validator->fails()) {
            $validator->validate();
        }
        $studentData['role_id'] = 3;
        $studentData['password'] = Hash::make($request->password);
        $student = User::create($studentData);
        if (! empty($request->book_ids)) {
            $student->books()->sync($request->book_ids, false);
        }
        if ($student) {
            $response = ['status' => true, 'message' => 'Added Successfully', 'redirect' => route('admin.students.index')];
        } else {
            $response = ['status' => false, 'message' => 'Something went wrong.Please try again.'];
        }

        return response()->json($response);
    }

    public function importStudents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fileInput' => 'required|file|mimes:csv', // Example of max file size
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('message', 'Only csv file can be upload');
        }

        Excel::import(new StudentsImport, $request->file('fileInput'));
        Excel::import(new AttendanceImport, $request->fileInput);

        return redirect()->back()->with('message', 'Records imported successfully!');

    }

    public function importAttendance(Request $request)
    {

        Excel::import(new AttendanceImport, $request->fileInput);

        return redirect()->back()->with('message', 'Records imported successfully!');

    }

    public function printAttendance($id)
    {

        try {
            $user = User::where('role_id', 3)->where('id', $id)->first();
            $attendance = Attendance::where('user_id', $id)->get();
            $monthlyHours = $attendance->groupBy(function ($date) {
                // Group by year and month
                return Carbon::parse($date->sign_in)->format('M Y');
            })->map(function ($month) {
                // Calculate total hours for each month
                return $month->reduce(function ($carry, $item) {
                    $signIn = Carbon::parse($item->sign_in);
                    $signOut = Carbon::parse($item->sign_out);
                    $hours = $signOut->diffInHours($signIn);

                    return $carry + $hours;
                }, 0);
            });
            $monthlyHoursArray = $monthlyHours->toArray();
            $pdf = PDF::loadView('admin.students.print-student-attendance', [
                'user' => $user,
                'attendance' => $attendance,
                'monthlywisehoures' => $monthlyHoursArray,
            ]);

            // Return the generated PDF
            return $pdf->download('attendance.pdf');
            // return auth()->user();

        } catch (\Exception $error) {
            return response()->json(['status' => 500, 'message' => $error->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['student'] = User::find($id);
        if (empty($data['student'])) {
            abort(404);
        }

        return view($this->folderLink.'student-detail', $data);
    }

    /**
     * get student books
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function studentBookData($studentId)
    {
        $studentBooks = User::find($studentId)->books;

        return response()->json([
            'data' => $studentBooks,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$data['books'] = Book::select('id','title')->get();
        $data['user'] = User::with('books')->where('id', $id)->firstOrFail();
        $data['languages'] = Language::all();
        //        $data['books'] = Language::find($data['user']->language_id)->books ?? collect(null);
        $data['books'] = Book::whereNull('parent_id')->orderBy('title', 'ASC')->get();

        return view($this->folderLink.'edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $book_parent_id = UserChapter::where('book_parent_id', '!=', null)->where('user_id', $id)->distinct()->pluck('book_parent_id')->toArray();
        $parent_id = UserChapter::where('parent_id', '!=', null)->where('user_id', $id)->distinct()->pluck('parent_id')->toArray();

        $bookIds = array_unique(array_merge($book_parent_id, $parent_id));
        // 1,2,3

        $reqBookIds = $request->book_ids ? $request->book_ids : [];
        // 1,3

        $deleteAssignedBookIds = array_diff($bookIds, $reqBookIds);

        $uniqueBookIds = [];
        if (count($reqBookIds) > 0) {
            $uniqueBookIds = array_merge(array_diff($bookIds, $reqBookIds), array_diff($reqBookIds, $bookIds));
        }

        $studentData = Arr::except($request->all(), ['_method', 'password', 'update_password', 'book_ids']);
        $validation = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            //'language_id' => 'required',
        ];
        $messages = [
            'first_name.required' => 'Please enter first name',
            'last_name.required' => 'Please enter last name',
            'email.required' => 'Please enter email',
            //'language_id.required' => 'Please select language',
        ];
        $validator = Validator::make($request->all(), $validation, $messages);
        if ($validator->fails()) {
            $validator->validate();
        }
        if ($request->has('update_password') && $request->update_password == '1') {
            $studentData['password'] = Hash::make($request->password);
        }
        $student = User::find($id);

        if (! empty($request->book_ids)) {
            $student->books()->sync($request->book_ids);
        } else {
            $student->books()->detach();
        }

        $result = $student->update($studentData);
        if ($result) {
            $response = ['status' => true, 'message' => 'Updated Successfully', 'redirect' => route('admin.students.index')];
        } else {
            $response = ['status' => false, 'message' => 'Something went wrong.Please try again.'];
        }

        if (count($reqBookIds) < 1) {
            UserChapter::where('user_id', $id)->delete();
        }
        if (count($uniqueBookIds) > 0) {
            for ($i = 0; $i < count($uniqueBookIds); $i++) {

                UserChapter::where('user_id', $id)->where('book_parent_id', $uniqueBookIds[$i])->delete();
                UserChapter::insert([
                    [
                        'user_id' => $id,
                        'parent_id' => $uniqueBookIds[$i],
                        'all_chapters' => 1,
                    ],
                ]);
            }
        }

        if (count($deleteAssignedBookIds) > 0) {
            UserChapter::where('user_id', $id)->whereIn('parent_id', $deleteAssignedBookIds)->orWhereIn('book_parent_id', $deleteAssignedBookIds)->delete();
        }

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = User::find($id);
        $book->books()->detach();
        $book->delete();
        $response = ['status' => true, 'message' => 'Deleted Successfully', 'redirect' => route('admin.students.index')];

        return response()->json($response);
    }

    /**
     * get student attendance
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function studentAttendance($id)
    {
        /*$dataa= Attendance::select(\DB::raw('count(id) as `total_attendance`'),\DB::raw('YEAR(sign_in) year'),\DB::raw('MONTH(sign_in) month'))
            ->groupBy(\DB::raw('YEAR(sign_in)'), \DB::raw('MONTH(sign_in)'))
            ->get();*/
        //$data['studentAttendances'] = Attendance::where('user_id',$id)->get();
        $reportType = (request()->has('report-type') && ! empty(request('report-type'))) ? request('report-type') : 'monthly';
        $data['reportType'] = $reportType;
        $data['user'] = User::findOrFail($id);
        if ($reportType == 'monthly') {
            $data['studentAttendances'] = Attendance::select(\DB::raw('count(id) as `total_attendance`'), \DB::raw('YEAR(sign_in) year'), \DB::raw('MONTH(sign_in) as month'))
                ->where('user_id', $id)
                ->groupBy(\DB::raw('YEAR(sign_in)'), \DB::raw('MONTH(sign_in)'))
                ->get();
        } elseif ($reportType == 'yearly') {
            $data['studentAttendances'] = Attendance::select(\DB::raw('count(id) as `total_attendance`'), \DB::raw('YEAR(sign_in) year'))
                ->where('user_id', $id)
                ->groupBy(\DB::raw('YEAR(sign_in)'))
                ->get();
        }

        return view($this->folderLink.'student-attendance', $data);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function studentAttendanceSearch(Request $request, $id)
    {
        $reportType = $request->report_type;
        $studentAttendances = collect(null);
        $response = [];

        //get monthly report
        if ($reportType == 'monthly') {
            $converted_date_from = Carbon::parse($request->month_from)->startOfMonth()->format('Y-m-d');
            $converted_date_to = Carbon::parse($request->month_to)->endOfMonth()->format('Y-m-d');
            $studentAttendances = Attendance::select(\DB::raw('count(id) as `total_attendance`'), \DB::raw('YEAR(sign_in) year'), \DB::raw('MONTH(sign_in) as month'))
                ->where('user_id', $id)
                ->whereDate('sign_in', '>=', $converted_date_from)
                ->whereDate('sign_in', '<=', $converted_date_to)
                ->groupBy(\DB::raw('YEAR(sign_in)'), \DB::raw('MONTH(sign_in)'))
                ->get();
            if ($studentAttendances->isNotEmpty()) {
                $response = [
                    'status' => true,
                    'result' => $studentAttendances->map(function ($model) {
                        return [
                            'total_attendance' => $model->total_attendance,
                            'year' => $model->year,
                            'month' => date('F', mktime(0, 0, 0, $model->month, 10)),
                        ];
                    }),
                    'report_type' => $reportType,
                ];
            } else {
                $response = [
                    'status' => false,
                ];
            }

        } elseif ($reportType == 'yearly') { //get yearly report
            $year_from = $request->year_from;
            $year_to = $request->year_to;
            $studentAttendances = Attendance::select(\DB::raw('count(id) as `total_attendance`'), \DB::raw('YEAR(sign_in) year'))
                ->where('user_id', $id)
                ->whereYear('sign_in', '>=', $year_from)
                ->whereYear('sign_in', '<=', $year_to)
                ->groupBy(\DB::raw('YEAR(sign_in)'))
                ->get();
            if ($studentAttendances->isNotEmpty()) {
                $response = [
                    'status' => true,
                    'result' => $studentAttendances->map(function ($model) {
                        return [
                            'total_attendance' => $model->total_attendance,
                            'year' => $model->year,
                        ];
                    }),
                    'report_type' => $reportType,
                ];
            } else {
                $response = [
                    'status' => false,
                ];
            }
        }

        return response()->json($response);
    }
}
