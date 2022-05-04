<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

use Illuminate\Support\Facades\Log;

class PostPolicy
{
    use HandlesAuthorization;

    public function update(User $user,Post $post){
        return $user->id === $post->user_id;
    }

    // when this policy is called, user is already authenticated 
    // this is only condition for creating post (to be logged in)
    public function store(User $user){
        return true;
    }

    public function destroy(User $user, Post $post){
        return $user->id === $post->user_id;
    }

    // for now, anyone can see all posts
    public function show(User $user){
        return true;
    }
}
