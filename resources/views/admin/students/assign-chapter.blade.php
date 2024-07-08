@extends('layout.master')
@section('content')
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Assign Chapters  to {{$student->first_name .' '. $student->last_name}}</h1> 
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Assign Chapters</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{route('admin.assign-chapter',$student->id)}}" class="add_advertiser">
                                    <i class="fas fa-list"></i> Assign Chapters
                                </a>
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <form role="form" method="post" action="{{route('admin.assing-book-chpater')}}" enctype="multipart/form-data">
                                @csrf
                                <input type="integer" hidden name="user_id" value="{{$student->id}}">
                                <div class="card-body row">
                                    <div class="form-group col-6">
                                        <label for="level">Select Book</label>
                                        <select name="book_id" id="book_ids" class="select2bs4 " data-placeholder="Select a Book" style="width: 100%;">
                                            @if($books->isNotEmpty())
                                            @foreach($books as $book)
                                                <option value="" selected disabled>Select Book</option>
                                                <option value="{{$book->id}}">{{$book->title}} </option>
                                            @endforeach
                                            @endif
                                        </select>

                                        @if($errors->has('book_id'))
                                            <span class="error text-red">{{ $errors->first('book_id') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="level">Select Chapters</label>
                                        <select name="chpater_ids[]" id="chapter_id" class="select2bs4 " multiple data-placeholder="Select Chapters" style="width: 100%;">
                                            
                                        </select>
                                        @if($errors->has('chpater_ids'))
                                            <span class="error text-red">{{ $errors->first('chpater_ids') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class=" ml-4">
                                    <button type="submit" class="btn btn-primary">
                                        <!----> <span>Save  </span>
                                    </button>
                                </div>
                            </form>

                            <h4 class="breadcrumb-item active ml-2  mt-4">Assigned Books Chapters</h4>
                            <table class="table">
                                <thead>
                                  <tr>
                                    <th scope="col">Book</th>
                                    <th scope="col">Chapters</th>
                                    <th scope="col">Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @if (count($chapters) > 0)
                                        
                                   
                                    @foreach ($chapters as $chapter)  
                                    <tr>
                                      <td>{{$chapter->parent_id == null ? $chapter->title : $chapter->parent->title}}</td>
                                      <td>{{$chapter->parent_id == null ? "All" : $chapter->chapter}}</td>
                                      <td><a class="btn btn-danger text-white delete-chapter" data-type="{{$chapter->parent_id == null ? 'parent_id' : 'book_id'}}" data-chapter-id="{{$chapter->id}}" data-student-id="{{$student->id}}" data-toggle="modal" data-target="#delete_chapter" >Delete</a></td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="3">
                                            <h5 class="breadcrumb-item active ml-2  text-center">No Chapters Assigned</h5>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <div class="float-right">
                                {{$chapters->links()}}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="delete_chapter" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{route('admin.delete-student-chapter')}}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-body">
                        <input type="text" hidden name="user_id" id="user_id" placeholder="user id">
                        <input type="text" hidden name="book_id" id="book_id" placeholder="book id">
                        <input type="text" hidden name="type" id="type" placeholder="type">
                        <h4 class="text-center">
                            Do you want to delete this chapter?
                        </h4>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function(){
            $("#book_ids").change(function() {
                let value = $(this).val();
                var url = '{{ route("admin.get-book-chapter", ":id") }}';
                url = url.replace(':id', value);

                $.ajax({
                    type: "get",
                    url: url,
                    success: function(data) {
                        if(data){
                            chapterData = data;
                            $('#chapter_id').empty();
                            if (chapterData.length) {
                                $('#chapter_id').append(`<option value="all">Select All</option>`);
                                $.each(chapterData, function(key, value) {
                                    $('#chapter_id').append(`<option value="${value.id}">${value.chapter}</option>`);
                                });
                            }
                        }else{
                            $('#chapter_id').empty();
                        }
                    },
                });
            });

            $('.delete-chapter').click(function() {
                let chapter_id  = $(this).data('chapter-id');

                $("#user_id").val($(this).data('student-id'));
                $("#type").val($(this).data('type'));
                $("#book_id").val(chapter_id);
            })

            // $('#chapter_id').change(function(){
            //     var selected_chapter = $(this).val();
            //     if (selected_chapter == 'all') {
                    
            //         $('#chapter_id').append(`<option value="all">Select All</option>`);
            //         $('#chapter_id option[value=all]').attr('selected','selected');
            //     }/*  else {
            //         $('#chapter_id').empty();
            //         $('#chapter_id').append(`<option value="all">Select All</option>`);
            //         $.each(chapterData, function(key, value) {
            //             $('#chapter_id').append(`<option value="${value.id}">${value.chapter}</option>`);
            //         });
            //     } */
            // })
        })
    </script>
@endpush