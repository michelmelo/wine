<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Comment;
use App\Wine;
use App\Customer;

use Illuminate\Http\Request;
use Session;

class CommentController extends Controller
{
    // Show all comments
    public function index()
    {
      // Collect all entries from database table
      $comments = Comment::paginate(5);
      // Direct browser to a page
      return view('admin.comments.index')->with('comments', $comments);
    }

    // And save it to the database
    public function store()
    {
      // Validate entered user data
      $this->validate(request(), [
        'wine_id' => 'required',
        'customer_id' => 'required',
        'title' => 'required|max:40',
        'body' => 'required|max:100',
        'rating' => 'required',
      ]);

      // Send data to database
      $comment = Comment::create([
        'wine_id' => request()->wine_id,
        'customer_id' => request()->customer_id,
        'title' => request()->title,
        'body' => request()->body,
        'rating' => request()->rating,
      ]);

      // Onscreen message
      Session::flash('success', 'New comment was added for this wine');

      // Route back to the same page
      return redirect()->back();
    }

    // Edit a comment
    public function edit(Comment $comment)
    {
      $wine = Wine::all();

      return view('admin.comments.edit')->with('comment', $comment)->with('wine', $wine);
    }

    // Update a comment
    public function update($id)
    {
      // Validate entered user data
      $this->validate(request(), [
        'wine_id' => 'required',
        'title' => 'required|max:40',
        'body' => 'required|max:191',
      ]);
      // Find id from database
      $comment = Comment::findOrFail($id);
      // Enter updated data to database
      $comment->wine_id = request()->wine_id;
      $comment->title = request()->title;
      $comment->body = request()->body;
      // Save to database
      $comment->save();

      // Show message
      Session::flash('success', 'Comment was updated!');

      // redirect user to all wines page
      return redirect('/admin/comments');
    }

    // Delete a comment
    public function destroy($id)
    {
      // Collect correct entry from table
      $comment = Comment::findOrFail($id);
      // Delete it
      $comment->delete();
      // Display a message
      Session::flash('success', 'Comment has been deleted successfully');
      // Redirect back to comments
      return redirect('/admin/comments/');
    }

    // Customer comments
    public function showCustomerComment()
    {
      // Get all entries
      $wine = Wine::all();
      // Get the id of the logged in user
      $id = Auth::guard('customer')->user()->id;
      // Get all comments created by the logged in user
      $comments = Comment::where('customer_id', $id)->latest()->paginate(5);

      // Send to page
      return view('customers.comments.index')->with('comments', $comments)->with('wine', $wine);
    }
    // Edit a comment (customer area)
    public function customerCommentEdit(Comment $comment)
    {
      // Get all entries from Wine table
      $wine = Wine::all();
      // Show comment edit form
      return view('customers.comments.edit')->with('comment', $comment)->with('wine', $wine);
    }

    // Update a comment (customer area)
    public function customerCommentUpdate($id)
    {
      // Validate entered user data
      $this->validate(request(), [
        'wine_id' => 'required',
        'title' => 'required|max:40',
        'body' => 'required|max:191',
        'rating' => 'required',
      ]);
      // Find id from database
      $comment = Comment::findOrFail($id);
      // Enter updated data to database
      $comment->wine_id = request()->wine_id;
      $comment->title = request()->title;
      $comment->body = request()->body;
      $comment->rating = request()->rating;
      // Save to database
      $comment->save();

      // Show message
      Session::flash('success', 'Comment was updated!');

      // redirect user to all wines page
      return redirect()->route('customerComment.show');
    }

    // Delete a commment (customer area)
    public function customerCommentDelete($id)
    {
      // Collect correct comment from database table & delete it
      $comment = Comment::findOrFail($id);
      $comment->delete();

      // Display a message
      Session::flash('success', 'Comment has been deleted successfully');
      // redirect back to all comments
      return redirect()->route('customerComment.show');
    }
}
