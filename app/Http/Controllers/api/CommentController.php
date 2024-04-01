<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\ReviewRating;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    /**
     * Display a listing of the comments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::all();
        return response()->json($comments);
    }

    /**
     * Store a newly created comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'text' => 'required|string',
            'is_approved' => 'boolean',
        ]);

        $comment = Comment::create($request->all());

        return response()->json($comment, Response::HTTP_CREATED);
    }

    /**
     * Display the specified comment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = Comment::findOrFail($id);
        return response()->json($comment);
    }

    /**
     * Update the specified comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'text' => 'required|string',
            'is_approved' => 'boolean',
        ]);

        $comment = Comment::findOrFail($id);
        $comment->update($request->all());

        return response()->json($comment);
    }

    /**
     * Remove the specified comment from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function reviewstore(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'comment_id' => 'required|exists:comments,id',
            'star_rating' => 'required|integer|min:1|max:5',
        ]);

        $review = new ReviewRating();
        $review->user_id = $request->user_id;
        $review->comment_id = $request->comment_id;
        $review->star_rating = $request->star_rating;
        $review->save();

        return redirect()->back()->with('flash_msg_success', 'Your review has been submitted successfully.');
    }

}
