<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class ChapterTrainingVideoController extends Controller
{
    protected $folderLink = 'admin.chapters-training-video.';
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
        $result = $queryBuilder->paginate(4);
        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['books'] = Book::whereNull('parent_id')->get();
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
            'chapter_id' => 'required',
            // 'link' => 'required',
            'training_video' => 'max:400120' //200MB
        ];
        $messages = [
            'parent_id.required' => 'Please select book',
            'chapter_id.required' => 'Please select chapter',
            // 'training_video.required' => 'Please select training video ',
            // 'link.required' => 'Please enter training video link',
            'training_video.max' => 'Maximum file size to upload is 400MB ',
        ];
        $validator = Validator::make($bookData, $validation, $messages);
        if ($validator->fails()) {
            $validator->validate();
        }
        if ($request->hasFile('training_video')){
            $videoFileName = 'training_video_'.time().'.'.$request->training_video->extension();
            $request->training_video->move(public_path('uploads/book-videos'), $videoFileName);
            $bookData['training_video'] = $videoFileName;
        }else{
            $bookData['training_video'] = null;
        }
        $book  = Book::where('id',$bookData['chapter_id'])->update([
            'training_video' =>  $bookData['training_video'],
            'link' =>  $bookData['link']
        ]);
        if ($book) {
            $response = ['status' => true, 'message' => 'Added Successfully', 'redirect' => route('admin.training-video.index')];
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
        /*$data['book'] = Book::findOrfail($id);
        $data['books'] = Book::whereNull('parent_id')->get();*/
        $data['books'] = Book::whereNull('parent_id')->get();
        $data['chapter'] = Book::findOrFail($id);
        $data['parent'] = Book::findOrFail( $data['chapter']->parent_id);
        if(empty( $data['parent']) || empty($data['chapter'])){
            abort(404);
        }
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
        $bookData = Arr::except($request->all(),['_method','old_training_video']);
        $validation = [
            'parent_id' => 'required',
            'chapter_id' => 'required',
            // 'link' => 'required',
            'training_video' => 'max:400120' //200MB
        ];
        $messages = [
            'parent_id.required' => 'Please select book',
            'chapter_id.required' => 'Please select chapter',
            // 'link.required' => 'Please enter video link',
            // 'training_video.required' => 'Please select training video ',
            'training_video.max' => 'Maximum file size to upload is 400MB ',
        ];
        $validator = Validator::make($bookData, $validation, $messages);
        if ($validator->fails()) {
            $validator->validate();
        }
        if ($request->hasFile('training_video')){
            $videoFileName = 'training_video_'.time().'.'.$request->training_video->extension();
            $request->training_video->move(public_path('uploads/book-videos'), $videoFileName);
            $bookData['training_video'] = $videoFileName;
            \File::delete(public_path('uploads/book-videos/'.$request->old_training_video));

        }else{
            $bookData['training_video'] = $request->old_training_video;
        }
        $book  = Book::where('id',$id)->update([
            'training_video' =>  $bookData['training_video'],
            'link' =>  $bookData['link']
        ]);
        if ($book) {
            $response = ['status' => true, 'message' => 'Updated Successfully', 'redirect' => route('admin.training-video.index')];
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
        \File::delete(public_path('uploads/books/'.$book->file));
        \File::delete(public_path('uploads/book-images/'.$book->book_image));
        \File::delete(public_path('uploads/book-videos/'.$book->book_video));
        //\File::delete(public_path('uploads/books/'.$book->file));
        //$book->files()->detach();
        $book->delete();
        $response = ['status' => true, 'message' => 'Deleted Successfully', 'redirect' => route('admin.chapters.index')];
        return response()->json($response);
    }

    /**
     * get chapters by book
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChapterByBook(Request $request){
        $bookId = $request->bookId;
        $chapters  = Book::select('id','chapter')->whereNotNull('parent_id')->where('parent_id',$bookId)->get();
        if ($chapters->isNotEmpty()){
            $response = ['status' => true ,'data' => $chapters];
        } else {
            $response = ['status' => false ,'message' => 'No Chapters Found'];
        }
        return response()->json($response);
    }
}
