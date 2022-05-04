<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;

 use App\Exceptions\TestException;

use Response;

class PostController extends Controller
{
    public function index() {

        DB::listen(function($query){
            logger($query->sql, $query->bindings);
        });

        $allPosts = request()->user()->posts;

        // if(true){
        //     throw ValidationException::withMessages(['field_name' => 'This value is incorrect']);
        // }
        

        return response()->json($allPosts);
    }

    public function create(){
        // not used for api call
    }

    public function show(Request $request, Post $post){
        if(request()->user()->cant('show',$post)){
            return response()->json(["status"=>"not allowed"]);
        }

        return response()->json($post);
    }

    public function store(Request $request){
        $title = $request->title;
        $text = $request->text;

        Post::create([
            'title'=>$title,
            'text'=>$text,
            'user_id'=>Auth::id()
        ]);

        return response()->json(['status'=>'Post successfully created']);
    }

    public function edit(){

    }

    // implicit binding (post is created by its id )
    public function update(Request $request, Post $post) {

        //////////////////////////////////////////////////////////////////
        // Moze na oba nacina, preko Gate facade i preko usera iz requesta

        // if(!Gate::allows('update',$post)){
        //     return response()->json(['status'=>'not allowed']);
        // }

        if (request()->user()->cant('update',$post)) {
            return response()->json(['status'=>'not allowed']);
        }

        /////////////////////////////////////////////////////////////////
        
        $input = $request->all();

        $post->fill($input)->save();

        return response()->json(['status'=>'Post successfully updated']);
    }

    public function destroy(Request $request, Post $post){
        
        if(request()->user()->cant('destroy',$post)){
            return response()->json(['status'=>'not allowed']);
        }

        $post->delete();

        return response()->json(['status'=>'Post successfully deleted']);
    }

    public function throwerror(){

        throw new TestException("This is sample exception");

    }
}
