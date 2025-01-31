<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Comment;

use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    private $comment;

    public function __construct(Comment $comment_model){
        $this->comment = $comment_model;
    }

    public function store(Request $request,$post_id){
        $request->validate([
            'comment'=>'required|max:150'
        ]);

        $this->comment->user_id = Auth::user()->id;
        $this->comment->post_id = $post_id;
        $this->comment->body = $request->comment;
        $this->comment->save();

        return redirect()->back();
    }

    public function destroy($id){
        #There are 2 ways on deleting the data
        
        //1st way is using findOrFail　
#        $comment = $this->comment->findOrFail($id);
#        $comment->delete();

        //2nd way is using destroy 
        $this->comment->destroy($id);

        return redirect()->back();
    }

   
}
