<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommentService;
use Session;

class CommentController extends Controller
{

    public function __construct(Request $request, CommentService $commentService)
    {
        $this->request = $request;
        $this->commentService = $commentService;
    }

    public function createComment()
    {

        $this->commentService->createComment();
        return redirect('/topics/details/' . $this->request->input("topic_id"));
    }

    public function updateComment()
    {
        $this->commentService->updateComment();
        $flaggedStatus = $this->request->input("flagged_status");
        if ($flaggedStatus) {

            return redirect('/topics/flagged/comments');
        }

        return redirect('/topics/details/' . $this->request->input("topic_id"));
    }

    /**
     * To delete the specified category id
     * @return type
     */
    public function deleteComment($comment_id)
    {
        $this->request->request->add(['comment_id' => $comment_id]);
        $details = $this->commentService->getComment();
        $this->commentService->deleteComment();
        Session::flash('message', 'Success! Comment is deleted successfully.');
        $flaggedStatus = $this->request->input("flagged_status");
        if ($flaggedStatus) {
            return redirect('/topics/flagged/comments');
        }
       
        return redirect('/topics/details/' . $details[0]->topic_id);
    }

}
