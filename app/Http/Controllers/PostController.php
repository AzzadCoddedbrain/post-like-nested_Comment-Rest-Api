<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;



class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return response()->json(['data' => $posts]);
    }

    public function store(Request $request)
    {
        $post = new Post;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->user_id = $request->user_id;
        $post->save();
        return response()->json(['data' => $post]);
    }

    public function show(Post $post)
    {
        return response()->json(['data' => $post]);
    }

    public function like(Post $post)
    {
        $post->likes++;
        $post->save();
        return response()->json(['data' => $post]);
    }

    public function addComment(Post $post, Request $request)
    {
        $comment = new Comment;
        $comment->content = $request->content;
        $comment->user_id = $request->user_id;
        $comment->post_id = $post->id;
        $comment->parent_id = $request->parent_id;
        $comment->save();
        return response()->json(['data' => $comment]);
    }
}
