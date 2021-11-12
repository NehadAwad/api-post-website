<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\Post as PostResource;

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

    public function index(){
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

    public function show(Post $post){

        return new PostResource($post);
//        try {
//            $post = Post::find($id);
//            if($post){
//                return response([
//                    'data' => Post::find($id),
//                    'status' => 'Success',
//                    'status_code' => 200,
//                ]);
//            }else{
//                return response()->json([
//                    'status_code' => 500,
//                    'message' => 'Post not found',
//                ]);
//            }
//
//
//        } catch (\Exception $error){
//            return response()->json([
//                'status_code' => 500,
//                'message' => 'Post not found',
//                'error' => $error,
//            ]);
//        }
    }

    public function updatePost(Request $request, $id){

        //dd($request);
        if ($request->hasFile('thumbnail')){
            $fileName = date('YmdHis') . "." .$request->file('thumbnail')->getClientOriginalExtension();
            $request->file('thumbnail')->move(public_path('thumbnail'),$fileName);

        }

        $post=Post::where('id', $id)
            ->update([
                'title' => $request->title,
                'description' => $request->description,
                'thumbnail' => $fileName,
                'status' => $request->status
            ]);
        //dd($post);

        try {

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'thumbnail' => 'required',
                'status' => 'required'
            ]);

            //dd('ok');
            if ($request->hasFile('thumbnail')){
                $fileName = date('YmdHis') . "." .$request->file('thumbnail')->getClientOriginalExtension();
                $request->file('thumbnail')->move(public_path('thumbnail'),$fileName);

            }



            $post=where('id', $id)
                ->update([
                   'title' => $request->title,
                    'description' => $request->description,
                    'thumbnail' => $fileName,
                    'status' => $request->status
                ]);
            return response([
                'data' =>  $post,
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

    public function deletePost($id){

        try{
            $post = Post::find($id);
            dd($post);

        } catch (\Exception $error){
            return response()->json([
                'status_code' => 500,
                'message' => 'Post not found',
                'error' => $error,
            ]);
        }

    }
}
