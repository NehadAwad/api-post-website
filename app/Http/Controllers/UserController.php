<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Traits\ApiTrait;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\PostInterface;

class UserController extends Controller
{

    use ApiTrait;

    protected $testRepo;
    public function __construct(PostInterface $postInterface)
    {
        $this->postRepo = $postInterface;
    }
    public function loginUser(Request $request){
        try {
            $request->validate([
                'email' => 'email | required',
                'password' => 'required'
            ]);

            $credentials = request([ 'email', 'password']);
            if(!Auth::attempt($credentials)) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Unauthorized'
                ]);
            }

            $user = Auth::user();
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'status_code' => 200,
                'access_token' => $tokenResult,
                'user' => $user,
                'role' => $user->role,
                'token_type' => 'Bearer',
            ]);

        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
            ]);
        }

    }
    public function registerUser(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'email | required',
                'password' => 'required',
                'confirm_password' => 'required |same:password'
            ]);


            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role
            ]);
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'status_code' => 200,
                'access_token' => $tokenResult,
                'user' => $user,
                'token_type' => 'Bearer',
            ]);

        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Register',
                'error' => $error,
            ]);
        }
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response('Logged out');
    }

    public function allUsers(){
        try {

            if($this->isAdmin()){
                return response()->json([
                    'data' => User::all(),
                    'status'=> "success",
                    'status_code' => 200,
                ]);
            }
            return response()->json([
                'status_code' => 500,
                'message' => 'Access denied',

            ]);

        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Access denied',
                'error' => $error,
            ]);
        }
    }

    public function test(){
        $test = $this->postRepo->test();
        return $test;
    }
}
