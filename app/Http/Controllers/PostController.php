<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Photo;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
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


        $posts = Post::when(Auth::user()->role == 1,function ($query){
            $query->where("user_id",Auth::id());
        })->search()->latest("id")->paginate(7);

        return view('post.index',compact('posts'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('create', Post::class);
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


        if(!Storage::exists("public/thumbnail")){
            Storage::makeDirectory("public/thumbnail");
        }

//        DB::transaction(function () use ($request){
//
//        DB::beginTransaction();
//
//        try{




        $post = new Post();
        $post->title = $request->title;
        $post->slug = $request->title;
        $post->category_id = $request->category;
        $post->description = $request->description;
        $post->excerpt = Str::words($request->description,20);
        $post->user_id = Auth::id();
        $post->isPublish = '1';
        $post->save();




        $post->tags()->attach($request->tags);

        if($request->hasFile('photos')){

            foreach ($request->file('photos') as $photo){

                $newName = uniqid()."_photo.".$photo->extension();
                $photo->storeAs('public/photo',$newName);


                $img = Image::make($photo);
                $img->fit(200,200);
                $img->save("storage/thumbnail/".$newName,100);

                $photo = new Photo();
                $photo->name = $newName;
                $photo->post_id = $post->id;
                $photo->user_id = Auth::id();
                $photo->save();
            }

        }

//        DB::commit();

//        }catch (\Exception $e){
//            DB::rollBack();
//            throw $e;
//        }

//        });


//        return $request;


        return redirect()->route('post.index')->with("status","Post Created");

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return $post;
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
//        return [$post,$post->tags];
        Gate::authorize('view',$post);
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


        $post->title = $request->title;
        $post->slug = $request->title;
        $post->category_id = $request->category;
        $post->description = $request->description;
        $post->excerpt = Str::words($request->description,20);

        $post->update();

        $post->tags()->detach();
        $post->tags()->attach($request->tags);

        return redirect()->route('post.index')->with("status","post updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {

//        return $post;

        Gate::authorize('delete',$post);

        foreach ($post->photos as $photo){
            Storage::delete('public/photo/'.$photo->name);
            Storage::delete('public/thumbnail/'.$photo->name);
        }

        // delete pivot records
        $post->tags()->detach();

        // delete db records
        $post->photos()->delete();

        // post delete
        $post->delete();

        return redirect()->route('post.index')->with("status","aung p aung p aung p");
    }
}
