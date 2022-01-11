@extends('layouts.app')

@section('style')
    <style>
        .uploader-ui{
            width: 100px;
            height: 100px;
        }
    </style>
@endsection

@section('content')


    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8">

                <div class="card">
                    <div class="card-header">
                        <h1>Edit Post</h1>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('post.update',$post->id) }}" id="updateForm" method="post">
                            @csrf
                            @method('put')
                        </form>

                        <div class="mb-3">
                            <label for="">Post Title</label>
                            <input type="text" name="title" form="updateForm" class="form-control" value="{{ old('title',$post->title) }}" @error('title') is-invalid @enderror">
                        </div>
                        @error('title')
                        <p class="text-danger small">{{ $message }}</p>
                        @enderror

                        <div class="mb-3">
                            <label class="form-label">Select Category</label>
                            <select class="form-select @error('category') is-invalid @enderror" name="category" form="updateForm">
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == old('category',$post->category_id) ? 'selected' : '' }}>{{ $category->title }}</option>
                                @endforeach
                            </select>
                            @error('category')
                            <p class="text-danger small">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="">Photo</label>
                            <div class="border rounded p-3 d-flex overflow-scroll">
                                <form action="{{ route('photo.store') }}" method="post" enctype="multipart/form-data" class="d-none" id="photoUploadForm">
                                    @csrf
                                    <input type="hidden" class="form-control" name="post_id" value="{{ $post->id }}">
                                    <div class="mb-3">
                                        <label class="form-label" >Photo</label>
                                        <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photoInput" value="{{ old('photo') }}" name="photo[]" id="photoInput" multiple >
                                        @error('photo')
                                        <p class="text-danger small">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <button class="btn btn-primary">Upload</button>
                                </form>

                                <div class="btn btn-primary rounded-3 me-1 uploader-ui d-flex justify-content-center align-items-center px-3" id="photoUploadUi">
                                    <i class="fas fa-plus fa-2x"></i>
                                </div>

                                @forelse($post->photos as $photo)
                                    <div class="position-relative">
                                        <form action="{{ route('photo.destroy',$photo->id) }}" class="position-absolute bottom-0 start-0" method="post">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        <img src="{{ asset('storage/thumbnail/'.$photo->name) }}" alt="" height="100"  class="rounded me-1">
                                    </div>
                                @empty
                                    <p class="text-muted">No photo</p>
                                @endforelse

                            </div>
                        </div>


                        <div class="mb-3">
                            <label for="">Post Description</label>
                            <textarea name="description" id="" class="form-control"  cols="30" rows="10" form="updateForm"  @error('description') is-invalid @enderror">{{ old('description',$post->description) }}</textarea>
                        </div>
                        @error('description')
                        <p class="text-danger small">{{ $message }}</p>
                        @enderror

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" required>
                                <label class="form-check-label" for="flexSwitchCheckDefault">Confirm</label>
                            </div>
                            <button type="submit" form="updateForm" class="btn btn-lg btn-primary">Update Post</button>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        let photoUploadForm = document.getElementById('photoUploadForm');
        let photoUploadUi = document.getElementById('photoUploadUi');
        let photoInput = document.getElementById('photoInput');

        photoUploadUi.addEventListener('click',function (){
            photoInput.click();
        });

        photoInput.addEventListener('change',function (){
            photoUploadForm.submit();
        })

    </script>


@endsection
