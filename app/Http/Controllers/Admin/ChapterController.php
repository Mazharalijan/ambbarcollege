<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
    protected $folderLink = 'admin.chapters.';
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
     * @return mixed
     * @throws \Exception
     */
    public function chapterData(Request $request){
        $search = $request->search;
        $queryBuilder = Book::query();
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
        $data['books'] = Book::whereNull('parent_id')->orderBy('title','ASC')->get();
        return view($this->folderLink.'create',$data);
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
            'parent_id' => 'required',
            'chapter' => 'required',
            'chapter_no' => 'required',
            'file' => 'required|mimes:pdf',
            //'title' => 'required',
//            'book_video' => 'max:5120' => 5MB
            'book_video' => 'max:400120' //200MB
        ];
        $messages = [
            //'title.required' => 'Please enter title',
            'chapter.required' => 'Please enter chapter',
            'chapter_no.required' => 'Please enter chapter Number',
            'parent_id.required' => 'Please select book',
            'book_video.max' => 'Maximum file size to upload is 400MB ',
            'file.required' => 'Please select book PDF ',
            'file.mimes' => 'Please select PDF file ',
        ];
        $validator = Validator::make($bookData, $validation, $messages);
        if ($validator->fails()) {
            $validator->validate();
        }
        if ($request->hasFile('file')){
            $fileName = 'book_'.time().'.'.$request->file->extension();
            $request->file->move(public_path('uploads/books'), $fileName);
            $bookData['file'] = $fileName;
        }

        if ($request->hasFile('book_image')){
            $fileName = 'book_'.time().'.'.$request->book_image->extension();
            $request->book_image->move(public_path('uploads/book-images'), $fileName);
            $bookData['book_image'] = $fileName;
        }
        if ($request->hasFile('book_video')){
            $videoFileName = 'book_video_'.time().'.'.$request->book_video->extension();
            $request->book_video->move(public_path('uploads/book-videos'), $videoFileName);
            $bookData['book_video'] = $videoFileName;
        }
        $book  = Book::create($bookData);
        if ($book) {
            $response = ['status' => true, 'message' => 'Added Successfully', 'redirect' => route('admin.chapters.index')];
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
        //$data['bookFile'] = $data['book']->files()->where('language_id',$languageId)->first();
        if(empty( $data['book'])){
            abort(404);
        }
        return view($this->folderLink.'view-chapter',$data);
    }

    /**
     * view single chapter view
     * @param $id
     * @param $languageId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function chapterDetail($id){

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
        $data['books'] = Book::whereNull('parent_id')->orderBy('title','ASC')->get();
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
            'chapter' => 'required',
            'chapter_no' => 'required',
            'parent_id' => 'required',
//            'title' => 'required',
            'book_video' => 'max:400120' //200MB
        ];
        $messages = [
//            'title.required' => 'Please enter title',
            'chapter.required' => 'Please enter chapter',
            'chapter_no.required' => 'Please enter chapter number',
            'parent_id.required' => 'Please select book',
            'book_video.max' => 'Maximum file size to upload is 400MB ',
        ];
        $validator = Validator::make($request->all(), $validation, $messages);
        if ($validator->fails()) {
            $validator->validate();
        }
        if ($request->hasFile('file')){
            $fileName = 'book_'.time().'.'.$request->file->extension();
            $request->file->move(public_path('uploads/books'), $fileName);
            $bookData['file'] = $fileName;
            \File::delete(public_path('uploads/books/'.$request->old_file));
        }
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
        if ($request->hasFile('book_video')){
            $videoFileName = 'book_video_'.time().'.'.$request->book_video->extension();
            $request->book_video->move(public_path('uploads/book-videos'), $videoFileName);
            $bookData['book_video'] = $videoFileName;
            \File::delete(public_path('uploads/book-videos/'.$request->old_book_video));
        } else {
            $bookData['book_video'] = $request->old_book_video;
        }
        $book  = Book::where('id',$id)->update($bookData);
        if ($book) {
            $response = ['status' => true, 'message' => 'Updated Successfully', 'redirect' => route('admin.chapters.index')];
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
        /*if ($book->students->count() > 0){
            return response()->json([
                'status' => false,
                'message' => 'This Chapter Book has Assign to students.',
            ]);
        }*/
        /*$bookFiles = $book->files;
        if ($bookFiles->isNotEmpty()){
            foreach ($bookFiles as $file){
                \File::delete(public_path('uploads/books/'.$file->book_file));
            }
        }*/
        \File::delete(public_path('uploads/books/'.$book->file));
        \File::delete(public_path('uploads/book-images/'.$book->book_image));
        \File::delete(public_path('uploads/book-videos/'.$book->book_video));
        //\File::delete(public_path('uploads/books/'.$book->file));
        //$book->files()->detach();
        $book->delete();
        $response = ['status' => true, 'message' => 'Deleted Successfully', 'redirect' => route('admin.chapters.index')];
        return response()->json($response);
    }

}
