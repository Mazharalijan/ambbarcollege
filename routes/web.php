<?php

use App\Http\Controllers\Admin\AttendanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/pdf', [AttendanceController::class, 'attendancePdf']);
Route::get('/install', function () {
    Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
        '--seed' => true,
    ]);
});
Route::get('/demo', 'Admin\AttendanceController@demo');

Route::get('/test-email', 'Admin\DashboardController@test');
Route::get('/update-check-out', 'Admin\CronJobController@updateCheckOut');

Route::get('/clear_cache', function () {
    $exitCode = Illuminate\Support\Facades\Artisan::call('cache:clear');
    $exitCode .= Illuminate\Support\Facades\Artisan::call('view:clear');
});
Route::get('/', 'Auth\LoginController@showLoginForm');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//admin routes
Route::group(['middleware' => ['auth:web', 'admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/print-student-attendance/{studentId}', 'Admin\StudentController@printAttendance')->name('print-attendance');
    //Route::get('/', 'Admin\DashboardController@index')->name('dashboard');
    Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');
    // Route::get('/dashboard', function () {
    //     return 'Hello Routes';
    // })->name('dashboard');
    Route::get('/attendance-form', 'Admin\AttendanceController@PreAttendanceForm')->name('attendanceform');
    Route::post('/attendance-form', 'Admin\AttendanceController@storedata')->name('attendance-form');

    //change password routes
    Route::resource('change-password', 'Admin\PasswordController');

    //profile routes
    Route::resource('profile', 'Admin\ProfileController');

    //bookings routes
    Route::resource('/books', 'Admin\BookController');
    Route::get('books-data', 'Admin\BookController@bookData')->name('books-data');
    Route::get('book-chapters/{id}', 'Admin\BookController@bookChapter')->name('book-chapter');
    Route::get('book-chapters-data', 'Admin\BookController@bookChapterData')->name('book-chapters-data');

    //courses routes
    Route::resource('/chapters', 'Admin\ChapterController');
    Route::get('chapters-data', 'Admin\ChapterController@chapterData')->name('chapters-data');
    Route::get('/view-chapter/{id}', 'Admin\ChapterController@chapterDetail')->name('chapter-detail');

    //chapters training videos
    Route::resource('/training-video', 'Admin\ChapterTrainingVideoController');
    Route::post('/get-chapter-by-book', 'Admin\ChapterTrainingVideoController@getChapterByBook')->name('get-chapter-by-book');

    //upload book files
    Route::get('/book/upload-book-file', 'Admin\BookController@uploadBookFile')->name('books.upload-book-file');
    Route::post('/books/upload-book-file-submit', 'Admin\BookController@uploadBookFileSubmit')->name('books.upload-book-file-submit');

    //edit book files
    Route::get('/book/edit-upload-book-file/{bookId}/{languageId}', 'Admin\BookController@editUploadBookFile')->name('books.edit-upload-book-file');
    Route::post('/books/edit-upload-book-file-submit/{bookId}/{languageId}', 'Admin\BookController@editUploadBookFileSubmit')->name('books.edit-upload-book-file-submit');

    Route::get('books/view-book/{bookId}/{languageId}', 'Admin\BookController@viewBook')->name('books.view-book');
    Route::post('books/delete-book-file', 'Admin\BookController@deleteBook')->name('books.delete-book-file');

    //students routes
    Route::resource('/students', 'Admin\StudentController');
    Route::post('/import-students', 'Admin\StudentController@importStudents')->name('students-import');
    Route::post('/import-attendance', 'Admin\StudentController@importAttendance')->name('attendance-import');
    Route::get('students-data', 'Admin\StudentController@studentData')->name('students-data');
    Route::get('assign-chapter/{studentId}', 'Admin\StudentController@assignChapter')->name('assign-chapter');
    Route::get('get-book-chapter/{bookId}', 'Admin\StudentController@bookChapter')->name('get-book-chapter');
    Route::post('assing-book-chpater', 'Admin\StudentController@assingBookChpater')->name('assing-book-chpater');
    Route::post('delete-student-chapter', 'Admin\StudentController@destroyChapter')->name('delete-student-chapter');
    Route::get('students-books-data/{studentId}', 'Admin\StudentController@studentBookData')->name('student-books-data');

    Route::get('students/student-attendance/{studentId}', 'Admin\StudentController@studentAttendance')->name('student-attendance');
    Route::get('/attendances/demo', 'Admin\AttendanceController@demo')->name('attendance-demo');
    Route::post('students/student-attendance-search/{studentId}', 'Admin\StudentController@studentAttendanceSearch')->name('student-attendance-search');

    //get books by language
    Route::post('get-books-by-language', 'Admin\StudentController@getBooksByLanguage')->name('get-books-by-language');

    //attendance routes
    Route::resource('/attendances', 'Admin\AttendanceController');

    Route::get('attendance-data', 'Admin\AttendanceController@attendanceData')->name('attendance-data');

});

//student routes
Route::group(['middleware' => ['auth:web', 'frontend']], function () {

    //Route::get('/', 'Frontend\DashboardController@index')->name('dashboard');
    Route::get('/dashboard', 'Frontend\DashboardController@index')->name('dashboard');

    //change password routes
    Route::resource('change-password', 'Frontend\PasswordController');

    //profile routes
    Route::resource('profile', 'Frontend\ProfileController');

    //bookings routes
    Route::get('/books', 'Frontend\BookController@index')->name('books');
    Route::get('/book/{id}', 'Frontend\BookController@bookDetail')->name('book-detail');
    Route::get('books-data', 'Frontend\BookController@bookData')->name('books-data');

    Route::get('book-chapters/{id}', 'Frontend\BookController@bookChapter')->name('book-chapter');
    //    Route::get('book-chapters//{id}', 'Frontend\BookController@bookChapter')->name('book-chapter');
    Route::get('book-chapters-data', 'Frontend\BookController@bookChapterData')->name('book-chapters-data');
    Route::get('chapter/{id}', 'Frontend\BookController@chapter')->name('chapter');

    Route::post('submit-attendance', 'Frontend\DashboardController@submitAttendance')->name('submit-attendance');
    Route::post('check-user-sign-in', 'Frontend\DashboardController@checkUserSignIn')->name('check-user-sign-in');

    //chapters training videos
    Route::resource('/training-videos', 'Frontend\ChapterTrainingVideoController');
    Route::get('training-videos-book-chapters/{id}', 'Frontend\ChapterTrainingVideoController@bookChapter')->name('book-chapter');

});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
