<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Post;
use App\Tag;
use App\Category;
class VPostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function indexjson()
    {
        return view('admin.vposts.index');
    }

    public function index()
    {
        $posts = Post::with('category')->with('tags')->get();

        return response()->json(['posts'=>$posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return response()->json(['tags'=>$tags, 'categories'=>$categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $area = json_decode($request->post);
        // $dd($request);

        $post = Post::find($id);

        $post->title = $area->title;
        $post->content = $area->content;
        $post->setCategory($area->category_id);
        $post->date = $area->date;
        $post->setTags($area->tags_id);
        $post->uploadImage($request->image);
        $post->save();
        return $post;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        // dd($id);
        $post = Post::find($id);
        $categories = Category::all();
        $tags = Tag::all();
        $selectedTags = $post->tags->all();
        // dd($selectedTags);
        return response()->json(['post'=>$post, 'tags'=>$tags, 'categories'=>$categories, 'selTags'=>$selectedTags]);
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

        $area = json_decode($request->post);
        // dd($area);


        $post = Post::find($id);
        dd($area->tags);

        $post->title = $area->title;
        $post->content = $area->content;
        $post->setCategory($area->category_id);
        $post->date = $area->date;
        $post->setTags($area->tags);
        // $post->uploadImage($request->image);
        // $post->save();
        return $post;

        // $post = Post::find($id);
        // $post->edit($area);
        // // $post->uploadImage($request->file('image'));
        // $post->setCategory($area->category_id);
        // $post->setTags($area->tags_id);
        // return $post;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Post::find($id)->remove();
        return;
    }
}
