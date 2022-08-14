<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public static function newPost(Request $request)
    {
        $post = new Post;
        $post->body = $request->body;
        $post->profile_id = $request->profileId;
        $post->author_id = Auth::user()->id;

        if ($request->image) {
            $post->image = $request->image;
        }
        $post->save();
        $result = new PostResource($post);
        return $result;
    }

    public static function getPosts($profileId)
    {
        $posts = Post::where('profile_id', $profileId);
        $authUserId = Auth::user()->id;
        // $posts->foreach(function(post){
        //     if($post->)
        // });
        $collection = new PostCollection($posts);
        return $collection;
    }
    
    public static function updatePost(Request $request)
    {
        $post = Post::where('id', $request->postId);
        $post->body = $request->body;

        if ($request->image) {
            $post->image = $request->image;
        }
        $post->save();
        return $post;
    }
}
