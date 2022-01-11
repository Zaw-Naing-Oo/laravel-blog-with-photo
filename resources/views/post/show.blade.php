@extends('layouts.app')
@section('content')


    <div class="container">
        <div class="row">
            <div class="col-12">

                <div class="card">

                    <div class="card-header">
                        {{ $post->title }}
                    </div>
                    <div class="card-body">

                        <div class="">
                            {{ $post->user->name }} <br>
                            {{ $post->category->title }}
                        </div>

                        {{ $post->description }}

                        <hr>

                        <div class="">
                            <a href="{{ route('post.create') }}" class="btn btn-primary">
                                Create Post
                            </a>
                            <a href="{{ route('post.index') }}" class="btn btn-outline-primary">
                                All Post
                            </a>
                        </div>



                    </div>

                </div>

            </div>
        </div>
    </div>


@endsection
