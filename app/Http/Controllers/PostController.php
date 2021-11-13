<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\Post as PostResource;
use App\Traits\ApiTrait;


class PostController extends Controller
{

    use ApiTrait;

    public function store(Request $request){

        try {

            if($this->isAdmin()){
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
            } else{
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Access denied',
                ]);
            }


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

            $post = Post::all();
            return new PostCollection($post);

        } catch (\Exception $error){
            return response()->json([
                'status_code' => 500,
                'message' => 'Error',
                'error' => $error,
            ]);
        }
    }

    public function show(Post $post)
    {


        try {
            if($this->isAdmin()){
                return new PostResource($post);
            }else{
                eturn response()->json([
                    'status_code' => 500,
                    'message' => 'Access denied',
                ]);
            }


        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Not found',
                'error' => $error,
            ]);

        }
    }

    public function updatePost(Request $request, $id){


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


            $post=Post::find($id);

            $post->update([
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

    public function destroy(Post $post){

        try{
            $post->delete();
            return response()->json([
                'status_code' => 200,
                'message' => 'Post Deleted',
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
