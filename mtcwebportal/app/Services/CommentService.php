<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\CommentModel;
use App\Helpers\DateHelper;
use Validator;

class CommentService
{

    protected $request;
    protected $commentModel;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->commentModel = new CommentModel();
        $this->commentModel->setTableName("comments");
    }

    public function createComment()
    {
        //validations
        if ($this->createCommentValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->createCommentValidator())
                            ->withInput();
        }
        //insert the values
        $insertArray = [
            "comment_text" => $this->request->input("comment_description"),
            "topic_id" => $this->request->input("topic_id"),
            "created_by" => $this->request->session()->get('user_id'),
            "updated_by" => $this->request->session()->get('user_id'),
            "created_at" => DateHelper::todayDateTime(),
            "updated_at" => DateHelper::todayDateTime(),
            "status" => "active"
        ];
        $this->commentModel->setInsertUpdateData($insertArray);
        $insert_id = $this->commentModel->insertData();

        //update the topic updated date time
        $this->commentModel->setTableName("topics");
        $whereArray = [
            ["topic_id", '=', $this->request->input("topic_id")],
        ];

        $updatedArray = [
            "updated_at" => DateHelper::todayDateTime(),
        ];

        $this->commentModel->setInsertUpdateData($updatedArray);
        $this->commentModel->setWhere($whereArray);
        $this->commentModel->updateData();

        return true;
    }

    /**
     * Create user related validation Rules
     * @return type
     */
    public function createCommentValidator()
    {
        return Validator::make($this->request->all(), [
                    'comment_description' => 'required||string',
                    'topic_id' => 'required||integer',
        ]);
    }
    
    /**
     * Update comment related validation Rules
     * @return type
     */
    public function updateCommentValidator()
    {
        return Validator::make($this->request->all(), [
                    'comment_description' => 'required||string',
                    'comment_id' => 'required||integer',
        ]);
    }

    /**
     * To update the link details
     * @return type
     */
    public function updateComment()
    {
        //validations
        if ($this->updateCommentValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->updateCommentValidator())
                            ->withInput();
        }

        //update logic
        $updateArray = [
            "comment_text" => $this->request->input("comment_description"),
            "updated_by" => $this->request->session()->get('user_id'),
            "updated_at" => DateHelper::todayDateTime(),
            "status" => "active"
        ];

        $whereArray = [
            ["comment_id", '=', $this->request->input("comment_id")]
        ];

        $this->commentModel->setUpdateDataWithDates($updateArray);
        $this->commentModel->setWhere($whereArray);
        $updatedId = $this->commentModel->updateData();

        return $updatedId;
    }

    /**
     * To delete the category id
     */
    public function deleteComment()
    {
        //validations
        if ($this->deleteCommentValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->deleteCommentValidator())
                            ->withInput();
        }

        //delete logic
        $whereArray = [
            "comment_id" => $this->request->input("comment_id"),
        ];
        $this->commentModel->setWhere($whereArray);
        $this->commentModel->deleteData();



        return TRUE;
    }

    /**
     * delete category related validations
     * @return type
     */
    public function deleteCommentValidator()
    {
        return Validator::make($this->request->all(), [
                    'comment_id' => 'required|integer'
        ]);
    }

    /**
     * To Bring the specific comment details
     * @return type
     */
    public function getComment()
    {
        $where = [];

        $commentId = $this->request->input("comment_id");
        if ($commentId) {
            $where[] = ["comment_id", "=", $commentId];
        }
        $this->commentModel->setWhere($where);
        $comment = $this->commentModel->getData();

        return $comment;
    }

}
