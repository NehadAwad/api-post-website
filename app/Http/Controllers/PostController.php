<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function createPost(Request $request){


        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'thumbnail' => 'required',
                'status' => 'required'
            ]);

            if ($request->hasFile('thumbnail')){
                $fileName = date('YmdHis') . "." .$request->file('thumbnail')->getClientOriginalExtension();
                $request->file('thumbnail')->move(public_path('thumbnail'),$fileName);
            }
            $post = Post::create([
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => auth()->id(),
                'thumbnail' => $fileName,
                'status' => $request->status
            ]);
            return response([
                'post' => $post,
                'status' => 'post created',
                'status_code' => 200,
            ]);



        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error',
                'error' => $error,
            ]);
        }

    }

    public function viewPosts(){
        try {
            return response([
                'data' => Post::all(),
                'status' => 'Success',
                'status_code' => 200,
            ]);

        } catch (\Exception $error){
            return response()->json([
                'status_code' => 500,
                'message' => 'Error',
                'error' => $error,
            ]);
        }
    }

    public function viewPost($id){
        try {
            return response([
                'data' => Post::find($id),
                'status' => 'Success',
                'status_code' => 200,
            ]);

        } catch (\Exception $error){
            return response()->json([
                'status_code' => 500,
                'message' => 'Post not found',
                'error' => $error,
            ]);
        }
    }
}
