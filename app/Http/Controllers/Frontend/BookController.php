<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\UserChapter;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $folderLink = 'frontend.books.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->folderLink . 'index');
    }

    /**
     * get dashboards list
     * @return mixed
     * @throws \Exception
     */
    public function bookData(Request $request)
    {
        $search = $request->search;
        $queryBuilder = auth()->user()->books();
        if(!empty($search)) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', "%$search%");
            });
        }
        $result = $queryBuilder->orderBy('title','asc')->paginate(4);
        return $result;
    }

    public function bookDetail($id)
    {
        if (auth()->user()->books()->count() > 0 && auth()->user()->books->where('id',$id)->count() > 0){
            $data['book'] = Book::findOrFail($id);
            //get book file by student language
            $data['bookFile'] = $data['book']->files()->where('language_id',auth()->user()->language_id)->first();
            if(empty( $data['bookFile'])){
                abort(404);
            }
            return view($this->folderLink . 'book-detail', $data);
        }
        abort(404);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bookChapter($id){
        if (auth()->user()->books()->count() > 0 && auth()->user()->books->where('id',$id)->count() > 0){
            $data['id'] = $id;
            $data['book'] = Book::find($id);
            return view($this->folderLink.'book-chapters',$data);
        }
       abort(404);
    }
    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function bookChapterData(Request $request){
        $search = $request->search;

        $checkParent = UserChapter::where(['user_id'=> auth()->user()->id, 'parent_id'=>$request->id, 'all_chapters' => 1])->first();

        if($checkParent == null) {
            $queryBuilder = Book::query();
            $queryBuilder->whereHas('assigned_students', function($q) {
                $q->where('user_id', auth()->user()->id);
            });
            $queryBuilder->where('parent_id',$request->id);
            $queryBuilder->whereNotNull('parent_id');
            if(!empty($search)) {
                $queryBuilder = $queryBuilder->where(function ($query) use ($search) {
                    $query->where('chapter', 'LIKE', "%$search%");
                });
            }
            $result = $queryBuilder->orderBy('chapter_no','asc')->paginate(4);
        } else {
            $queryBuilder = Book::query();
            $queryBuilder->where('parent_id',$request->id);
            $queryBuilder->whereNotNull('parent_id');
            if(!empty($search)) {
                $queryBuilder = $queryBuilder->where(function ($query) use ($search) {
                    $query->where('chapter', 'LIKE', "%$search%");
                });
            }
            $result = $queryBuilder->orderBy('chapter_no','asc')->paginate(4);
            
        }




        return $result;
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function chapter($id){
        $data['book'] = Book::findOrFail($id);
//        $parentBook = $this
        //$data['bookFile'] = $data['book']->files()->where('language_id',$languageId)->first();
        if(empty( $data['book'])){
            abort(404);
        }
        return view($this->folderLink.'view-chapter',$data);

    }
}


