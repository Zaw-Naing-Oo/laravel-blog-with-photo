<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Photo;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::when(isset(request()->search),function ($query){
           $search = request()->search;
           $query->where("title","LIKE","%$search%")->orWhere("description","LIKE","%$search%");
        })->latest('id')->paginate(7);
        return view('post.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {


        $request->validate([
            "title" => "required|unique:posts,title|min:3",
            "category" => "required|integer|exists:categories,id",
            "description" => "required|min:20",
            "photo" => "nullable",
            "photo.*" => "file|max:3000|mimes:jpg,png,jpeg"

        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->description = $request->description;
        $post->excerpt = Str::words($request->description,20,'>>>');
        $post->user_id = Auth::id();
        $post->category_id = $request->category;
        $post->is_publish = true;
        $post->save();

        if(!Storage::exists("public/thumbnail")){
            Storage::makeDirectory("public/thumbnail");
        }

        if($request->hasFile('photo')){
            foreach ( $request->file('photo') as $photo){
                $newName = uniqid()."_photo.".$photo->extension();
                $photo->storeAs('public/photo',$newName);


                //making thumbnail
                $img = Image::make($photo);

                //reducing size
                $img->fit(200,200);
                $img->save("storage/thumbnail/".$newName);


                // save in databases
                $photo = new Photo();
                $photo->name = $newName;
                $photo->post_id = $post->id;
                $photo->user_id = Auth::id();
                $photo->save();
            }
        }

        return redirect()->route('post.index')->with('status',"Post Created");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('post.show',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('post.edit',compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $request->validate([
            "title" => "required|unique:posts,title,$post->id|min:3",
            "category" => "required|integer|exists:categories,id",
            "description" => "required|min:20"

        ]);

        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->description = $request->description;
        $post->excerpt = Str::words($request->description,20);
        $post->category_id = $request->category;
        $post->update();
        return redirect()->route('post.index')->with('status',"Post Updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        foreach ($post->photos as $photo){
            //File delete
            Storage::delete('public/photo/'.$photo->name);
            Storage::delete('public/thumbnail/'.$photo->name);

//            //delete db record
//            $photo->delete();
        }

        // delete all record from has Many
        $post->photos()->delete();

        $post->delete();
        return redirect()->route('post.index')->with('status',"Post Deleted");

    }
}
