<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Resources\CommentResource;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * Get a paginated list of comments.
     *
     * @return \App\Http\Resources\CommentResource
     */
    public function index()
    {
        // Get paginated comments
        $comments = Comment::paginate(10);
        
        return CommentResource::collection($comments);
    }

    /**
     * Search comments based on the provided query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\CommentResource
     */
    public function search(Request $request)
    {
        // Get query from request
        $query = $request->input('query');

        // Search for comments matched
        $comments = Comment::where('username', 'like', "%$query%")
                           ->orWhere('comment', 'like', "%$query%")
                           ->paginate(10);

        return CommentResource::collection($comments);
    }
}