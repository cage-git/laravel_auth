<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{

    public function register(Request $request){
        if(!empty($request)){
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            if($user){
            return response()->json(['message'=>'record created successfully','user'=>$user],200);
            }else{
            return response()->json(['message'=>'record not created'],500);
            }    
        }
    }

    public function login(Request $request){
        if(!Auth::attempt($request->only('email','password'))){
            return response([
                'message'=>'invalid credentials'
            ],400);
        }
        $user = Auth::user();
        $token = $user->createToken('API Token')->accessToken;
        $cookie = cookie('jwt',$token,60*24);
        return response([
            "message"=>"Success",
            "token"=>$token,
        ])->withCookie($cookie);
    }

    public function user(){
        return Auth::user();
    }

    public function logout(){
        $user = Auth::user();
        $user->tokens()->delete();
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    public function createPost(Request $request){
        if(!Auth::user()){
            return response([
                "message" => "User is not authenticated"
            ]);
        }
        $user = Auth::user();
        $user_id = $user->id;
        $title = $request->title;
        $content = $request->content;
        $post = Post::create([
            'user_id' => $user_id,
            'title' => $title,
            'content' => $content
        ]);
        
        if(!$post){
            return response([
                "message" => "Something went wrong"
            ],500);
        }
        return response([
            "status" => "success",
            "title" => $post->title,
            "content" => $post->content,
            "user_id" => $post->user_id
        ],200);
    }

    public function viewPosts(){
        $user = Auth::user();
        $posts = Post::where('user_id',$user->id)->get();
        if($posts->isEmpty()){
            return response([
                "message"=> "No posts with this user"
            ]);
        }
        return response()->json([
            "Post" => $posts
        ]);
    }

    public function removePost($id){
        if(!Auth::user()){
            return response([
                "message"=> "User not authenticated"
            ]);
        }
        $user = Auth::user();
        try{
            Post::where('id',$id)
            ->where('user_id',$user->id)
            ->delete();
            return response([
                "message" => "Deleted successfully"
            ],200);
        }catch(\Exception $e){
            return response([
                "Error" => $e->getMessage()
            ]);
        }   
    }

    public function updatePost(Request $request,$id){
        if(!Auth::user()){
            return response([
                "message" => "User is not authenticated"
            ]);
        }
        $user = Auth::user();
        $title = $request->title;
        $content = $request->content;
        try{
            $updated_post = Post::where('id',$id)
            ->where('user_id',$user->id)
            ->update([
                'title' => $title,
                'content' => $content
            ]);
            return response()->json($updated_post);
        }catch(\Exception $e){
            return response([
                'Error' => $e->getMessage()
            ]);
        }
    }

    public function getPosts(){
        if(!Auth::user()){
            return response([
                "message" => "User not authenticated"
            ]);
        }
        $user = Auth::user();
        $posts = $user->posts()->get();
        if(!$posts->isEmpty()){
            return response()->json([
                'Posts' => $posts
            ]);
        }
        return response([
            "message" => "No post for this user"
        ]);
    }
}
