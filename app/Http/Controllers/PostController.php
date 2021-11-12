<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function createPost(Request $request){

        if ($request->hasFile('thumbnail')){
            $fileName = rand(8). $request->file('thumbnail')->getClientOriginalExtension();
            $request->file('thumbnail')->move(public_path('thumbnail'),$fileName);
        }
        $post = Post::create([
            'thumbnail' => $fileName
        ]);
        return response([
            'post' => $post,
            'status' => 'posted'
        ]);

    }
}
