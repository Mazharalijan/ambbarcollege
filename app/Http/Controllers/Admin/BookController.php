<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookController extends Controller
{
    protected $folderLink = 'admin.books.';
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
     * @param Request $request
     * @return mixed
     */
    public function bookData(Request $request){
        $search = $request->search;
        $queryBuilder = Book::query();
        $queryBuilder->whereNull('parent_id');
        if(!empty($search)) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', "%$search%");
            });
        }
        $result = $queryBuilder->orderBy('title','asc')->paginate(4);
        return $result;
    }

    public function bookChapter($id){
        $data['id'] = $id;
        $data['book'] = Book::find($id);
        return view($this->folderLink.'book-chapters',$data);
    }
    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function bookChapterData(Request $request){
        $search = $request->search;
        $queryBuilder = Book::query();
        $queryBuilder->where('parent_id',$request->id);
        $queryBuilder->whereNotNull('parent_id');
        if(!empty($search)) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($search) {
                $query->where('chapter', 'LIKE', "%$search%");
            });
        }
        $result = $queryBuilder->orderBy('chapter_no','asc')->paginate(4);
        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->folderLink.'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bookData = $request->all();
        $validation = [
            'title' => 'required',
//            'book_video' => 'max:5120' => 5MB
            //'book_video' => 'max:200120' //200MB
        ];
        $messages = [
            'title.required' => 'Please enter title',
           // 'book_video.max' => 'Maximum file size to upload is 200MB ',
        ];
        $validator = Validator::make($bookData, $validation, $messages);
        if ($validator->fails()) {
            $validator->validate();
        }
        /*if ($request->hasFile('file')){
            $fileName = 'book_'.time().'.'.$request->file->extension();
            $request->file->move(public_path('uploads/books'), $fileName);
            $bookData['file'] = $fileName;
        }*/

        if ($request->hasFile('book_image')){
            $fileName = 'book_'.time().'.'.$request->book_image->extension();
            $request->book_image->move(public_path('uploads/book-images'), $fileName);
            $bookData['book_image'] = $fileName;
        }
        /*if ($request->hasFile('book_video')){
            $videoFileName = 'book_video_'.time().'.'.$request->book_video->extension();
            $request->book_video->move(public_path('uploads/book-videos'), $videoFileName);
            $bookData['book_video'] = $videoFileName;
        }*/
        $book  = Book::create($bookData);
        if ($book) {
            $response = ['status' => true, 'message' => 'Added Successfully', 'redirect' => route('admin.books.index')];
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
        $data['book'] = Book::findOrFail($id);
        $data['bookFiles'] = $data['book']->files;
        return view($this->folderLink.'book-detail',$data);
    }

    /**
     * view single book view
     * @param $id
     * @param $languageId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewBook($id,$languageId){
        $data['book'] = Book::findOrFail($id);
        $data['bookFile'] = $data['book']->files()->where('language_id',$languageId)->first();
        if(empty( $data['bookFile'])){
            abort(404);
        }
        return view($this->folderLink.'view-book',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['book'] = Book::findOrfail($id);
        return view($this->folderLink.'edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $bookData = Arr::except($request->all(),['file','_method','old_file','old_book_image','old_book_video']);
        $validation = [
//            'title' => 'required',
            'title' => 'required',
            'book_video' => 'max:200120' //200MB
        ];
        $messages = [
            'title.required' => 'Please enter title',
//            'title.required' => 'Please enter title',
            'book_video.max' => 'Maximum file size to upload is 200MB ',
        ];
        $validator = Validator::make($request->all(), $validation, $messages);
        if ($validator->fails()) {
            $validator->validate();
        }
        /*if ($request->hasFile('file')){
            $fileName = 'book_'.time().'.'.$request->file->extension();
            $request->file->move(public_path('uploads/books'), $fileName);
            $bookData['file'] = $fileName;
            \File::delete(public_path('uploads/books/'.$request->old_file));
        }*/
        //upload book image
        if ($request->hasFile('book_image')){
            $fileName = 'book_'.time().'.'.$request->book_image->extension();
            $request->book_image->move(public_path('uploads/book-images'), $fileName);
            $bookData['book_image'] = $fileName;
            \File::delete(public_path('uploads/book-images/'.$request->old_book_image));

        }else {
            $bookData['book_image'] = $request->old_book_image;
        }
        //upload book video
        /*if ($request->hasFile('book_video')){
            $videoFileName = 'book_video_'.time().'.'.$request->book_video->extension();
            $request->book_video->move(public_path('uploads/book-videos'), $videoFileName);
            $bookData['book_video'] = $videoFileName;
            \File::delete(public_path('uploads/book-videos/'.$request->old_book_video));
        } else {
            $bookData['book_video'] = $request->old_book_video;
        }*/
        $book  = Book::where('id',$id)->update($bookData);
        if ($book) {
            $response = ['status' => true, 'message' => 'Updated Successfully', 'redirect' => route('admin.books.index')];
        } else {
            $response = ['status' => false, 'message' => 'Something went wrong.Please try again.'];
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
        $book  = Book::find($id);
        if ($book->students->count() > 0){
            return response()->json([
                'status' => false,
                'message' => 'Book has Assign to students.',
            ]);
        }
        $chapters = Book::where('id',$id)->whereNotNull('parent_id')->get();
        if ($chapters->isNotEmpty()){
            foreach ($chapters as $chapter){
                \File::delete(public_path('uploads/books/'.$chapter->book_file));
                Book::destroy($chapter->id);
            }
        }
        /*$bookFiles = $book->files;
        if ($bookFiles->isNotEmpty()){
            foreach ($bookFiles as $file){
                 \File::delete(public_path('uploads/books/'.$file->book_file));
            }
        }*/
        \File::delete(public_path('uploads/book-images/'.$book->book_image));
        //\File::delete(public_path('uploads/books/'.$book->file));

        /*
        \File::delete(public_path('uploads/book-videos/'.$book->book_video));
        $book->files()->detach();*/
        $book->delete();
        $response = ['status' => true, 'message' => 'Deleted Successfully', 'redirect' => route('admin.books.index')];
        return response()->json($response);
    }

    /**
     * Upload book file view
     */
    public function uploadBookFile()
    {
        $data['books']  = Book::all();
        $data['languages']  = Language::all();
        return view($this->folderLink.'upload-book-file',$data);
    }

    /**
     * view single book view
     * @param $bookId
     * @param $languageId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editUploadBookFile($bookId,$languageId){
        $data['bookId']  = $bookId;
        $data['languageId']  = $languageId;
        $data['books']  = Book::all();
        $data['languages']  = Language::all();
        return view($this->folderLink.'edit-upload-book-file',$data);
    }
    /**
     * upload books file submit
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadBookFileSubmit(Request $request){
        $bookData = Arr::except($request->all(),['file','_method','old_file']);
        $validation = [
            'book_id' => 'required',
            'language_id' => 'required',
            'file' => 'required|mimes:pdf',
        ];
        $messages = [
            'book_id.required' => 'Please select book',
            'language_id.required' => 'Please select language',
        ];
        $validator = Validator::make($request->all(), $validation, $messages);

        $validator->after(function ($validator) use ($bookData){
            $book = Book::find($bookData['book_id']);
            if (!empty($book) && $book->files()->where('language_id',$bookData['language_id'])->first()) {
                $validator->errors()->add('language_id', 'Book already added for this language.');
            }
        });

        if ($validator->fails()) {
            $validator->validate();
        }

        if ($request->hasFile('file')){
            $fileName = 'book_'.time().'.'.$request->file->extension();
            $request->file->move(public_path('uploads/books'), $fileName);
            $bookData['file'] = $fileName;
        }
        $book = Book::find($request->book_id);
        $book->files()->sync([
                $bookData['language_id'] => ['file' => $bookData['file']]
        ],false);
        if ($book) {
            $response = ['status' => true, 'message' => 'Added Successfully'];
        } else {
            $response = ['status' => false, 'message' => 'Something went wrong.Please try again.'];
        }
        return response()->json($response);
    }

    /**
     * upload books file submit
     * @param Request $request
     * @param $bookId
     * @param $languageId
     * @return \Illuminate\Http\JsonResponse
     */
    public function editUploadBookFileSubmit(Request $request,$bookId,$languageId){
        $bookData = Arr::except($request->all(),['file','_method','old_file']);
        $book = Book::find($bookId);
        $validation = [
            'language_id' => 'required',
        ];
        $messages = [
            'language_id.required' => 'Please select language',
        ];
        $validator = Validator::make($request->all(), $validation, $messages);
        //$pivot_primary_id = $book->files()->where('language_id',$bookData['language_id'])->first()->pivot->id;
        $bookFileLanguage = \DB::table('book_file_languages')
                ->where('language_id','!=',$languageId)
                ->where('book_id',$bookId)
                ->where('language_id',$request->language_id)
                ->first();
        //$dd = \DB::select('SELECT * FROM book_file_languages WHERE language_id != '.$languageId.' AND book_id = '.$bookId.' AND language_id = '.$request->language_id.' ');
        $validator->after(function ($validator) use ($bookFileLanguage){
            if (!empty($bookFileLanguage)) {
                $validator->errors()->add('language_id', 'Book already added for this language.');
            }
        });

        if ($validator->fails()) {
            $validator->validate();
        }
        if ($request->hasFile('file')){
            $fileName = 'book_'.time().'.'.$request->file->extension();
            $request->file->move(public_path('uploads/books'), $fileName);
            \File::delete(public_path('uploads/books/'.$book->files()->where('language_id',$bookData['language_id'])->first()->book_file));
            $bookData['file'] = $fileName;
        } else {
            $bookData['file'] = $book->files()->where('language_id',$bookData['language_id'])->first()->book_file;
        }
        $book->files()->sync([
            $bookData['language_id'] => ['file' => $bookData['file']]
        ],false);
        if ($book) {
            $response = ['status' => true, 'message' => 'Updated Successfully','redirect' => route('admin.books.index')];
        } else {
            $response = ['status' => false, 'message' => 'Something went wrong.Please try again.'];
        }
        return response()->json($response);
    }

    /**
     * remove book file
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBook(Request $request){
        $book = Book::find($request->book_id);
        if ($book){
            $book->files()->detach($request->language_id);
            \File::delete(public_path('uploads/books/'.$request->old_file));
        }
        $response = ['status' => true, 'message' => 'Deleted Successfully', 'redirect' => route('admin.books.show',$request->book_id)];
        return response()->json($response);
    }
}
