<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function index()
    {
        $comments = Comment::paginate(10);
        return CommentResource::collection($comments);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $comments = Comment::where('username', 'like', "%$query%")
                           ->orWhere('comment', 'like', "%$query%")
                           ->paginate(10);

        return CommentResource::collection($comments);
    }
}