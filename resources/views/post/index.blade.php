@extends('layouts.app')


@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h1>Post Lists</h1>
                    </div>

                    <div class="card-body">

                        <div class="mb-3 d-flex justify-content-between align-items-center">
                           <div class="">
                               <a href="{{ route('post.create') }}" class="btn btn-primary">
                                   Post Create
                               </a>
                               @isset(request()->search)
                                   <a href="{{ route('post.index') }}" class="btn btn-outline-primary mr-3">
                                       <i class="feather-list"></i>
                                       All Post
                                   </a>
                                   <span>Search By : " {{ request()->search }} "</span>
                               @endisset
                           </div>

                            <form action="" method="get" class="w-25">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Search Something" name="search" value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search "></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Photo</th>
                                <th>Is Publish</th>
                                <th>Category</th>
                                <th>Owner</th>
                                <th>Control</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($posts as $post)
                                <tr>
                                    <td>{{ $post->id }}</td>
                                    <td class="small">{{ \Illuminate\Support\Str::words($post->title,3) }}</td>
                                    <td>
                                        @forelse($post->photos()->latest('id')->limit(4)->get() as $photo)

                                            <a class="my-image-links" data-gall="{{ $post->id }}" href="{{ asset('storage/photo/'.$photo->name) }}">
                                                <img src="{{ asset('storage/thumbnail/'.$photo->name) }}" height="30" class="image rounded rounded-circle border border-white shadow-sm" alt="image alt" >
                                            </a>

{{--                                            <a class="my-link" href="{{ asset('storage/photo/'.$photo->name) }}">--}}
{{--                                                <img src="{{ asset('storage/thumbnail/'.$photo->name) }}" height="30" class="image rounded rounded-circle border border-white shadow-sm" alt="image alt"/>--}}
{{--                                            </a>--}}
{{--                                            <img src="{{ asset('storage/thumbnail/'.$photo->name) }}" alt="">--}}
                                        @empty
                                            <p class="text-muted">No photo</p>
                                        @endforelse
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault" {{ $post->is_publish ? 'checked' : '' }}>
                                            <label class="form-check-label" for="flexSwitchCheckDefault">
                                                {{ $post->is_publish ? 'Publish' : 'unpublish' }}
                                            </label>
                                        </div>
                                    </td>
                                    <td>{{ $post->category->title }}</td>
                                    <td>{{ $post->user->name ?? 'unknown' }}</td>
                                    <td>

                                        <div class="btn-group">
                                            <a href="{{ route('post.show',$post->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-shower"></i>
                                            </a>
                                            <a href="{{ route('post.edit',$post->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-pencil-alt fa-fw"></i>
                                            </a>
                                            <button class="btn-sm btn btn-danger" form="postDeleteForm{{ $post->id }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>

                                        <form action="{{ route('post.destroy',$post->id) }}" id="postDeleteForm{{ $post->id }}" class="d-inline-block" method="post">
                                            @csrf
                                            @method('delete')
                                        </form>


                                    </td>
                                    <td>
                                        <p class="small mb-0">
                                            <i class="fas fa-calendar text-primary">
                                                {{ $post->created_at->format('Y-m-d') }}
                                            </i>
                                        </p>
                                        <p class="small mb-0">
                                            <i class="fas fa-calendar">
                                                {{ $post->created_at->format('H:i a') }}
                                            </i>
                                        </p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">There is no Posts</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-between align-items-center">
                            {{ $posts->appends(request()->all())->links() }}
                            <p class="font-weight-bold h5 mb-0 ">Total : {{ $posts->total() }}</p>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('style')
    <style>
        .image{
            margin-left: -10px;
        }
    </style>
@endsection

