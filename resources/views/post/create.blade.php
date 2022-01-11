@extends('layouts.app')

@section('content')


    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8">

                <div class="card">
                    <div class="card-header">
                        <h1>Create Post</h1>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('post.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="">Post Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" @error('title') is-invalid @enderror">
                            </div>
                            @error('title')
                            <p class="text-danger small">{{ $message }}</p>
                            @enderror
                            <div class="mb-3">
                                <label class="form-label">Select Category</label>
                                <select class="form-select @error('category') is-invalid @enderror" name="category">
                                    @foreach(\App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == old('category') ? 'selected' : '' }}>{{ $category->title }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                <p class="text-danger small">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="">Photo</label>
                                <input type="file" name="photo[]" class="form-control" multiple @error('photo') is-invalid @enderror">
                            </div>
                            @error('photo')
                            <p class="text-danger small">{{ $message }}</p>
                            @enderror
                            <div class="mb-3">
                                <label for="">Post Title</label>
                                <textarea name="description" id="" class="form-control"  cols="30" rows="10"  @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                            <p class="text-danger small">{{ $message }}</p>
                            @enderror

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" required>
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Default switch checkbox input</label>
                                </div>
                                <button class="btn btn-primary">Create Post</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
