<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\ReviewRating;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display the 5 most recently approved comments.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $approvedComments = Comment::where('approved', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json($approvedComments);
    }
    
    /**
     * Store a newly created comment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeComment(Request $request)
    {
        $comment = new Comment();
        $comment->user_id = Auth::user()->id;
        $comment->product_id = $request->product_id;
        $comment->text = $request->text;
        $comment->is_approved = 0; // Default to unapproved
        $comment->save();

        return response()->json($comment, Response::HTTP_CREATED);
    }

    /**
     * Store a newly created review in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reviewstore(Request $request)
    {
        $request->validate([
            'comment_id' => 'required|exists:comments,id',
            'star_rating' => 'required|integer|min:1|max:5',
        ]);

        $review = new ReviewRating();
        $review->user_id = Auth::user()->id;
        $review->comment_id = $request->comment_id;
        $review->star_rating = $request->star_rating;
        $review->save();

        // Update the average rating for the book
        $this->updateCommentRating($request->comment_ident_id);

        return redirect()->back()->with('flash_msg_success', 'Your review has been submitted successfully.');
    }

    /**
     * Calculate and update the average rating for a comment.
     *
     * @param  int  $comment_id
     * @return void
     */
    private function updateCommentRating($comment_id)
    {
        $comment = Comment::findOrFail($comment_id);

        // Get all review ratings for this comment
        $reviewRatings = ReviewRating::where('comment_id', $comment_id)->get();

        if ($reviewRatings->count() > 0) {
            // Calculate the average rating
            $averageRating = $reviewRatings->avg('star_rating');

            // Update the comment's rating
            $comment->rating = (int) round($averageRating);
            $comment->save();
        }
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

}
