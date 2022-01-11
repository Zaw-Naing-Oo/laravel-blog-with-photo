@extends('layouts.app')

@section('content')


    <div class="container">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h1>Create Category</h1>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('category.store') }}" method="post">
                            @csrf
                            <div class="row align-items-end">
                                <div class="col-6 col-lg-3">
                                    <label for="">Category Title</label>
                                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" @error('title') is-invalid @enderror">
                                </div>
                                <div class="col-6 col-lg-3">
                                    <button class="btn btn-primary">Add Category</button>
                                </div>
                                @error('title')
                                <p class="text-danger small">{{ $message }}</p>
                                @enderror
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
