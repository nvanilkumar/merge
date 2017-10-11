<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\TopicsModel;
use App\Helpers\DateHelper;
use Validator;

class TopicService
{

    protected $request;
    protected $topicModel;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->topicsModel = new TopicsModel();
        $this->topicsModel->setTableName("topics");
    }

    /**
     * To Bring the topics list
     * @return type
     */
    public function getTopicList()
    {
        $where = [];
        $categoryId = $this->request->input("category_id");
        if ($categoryId) {
            $where[] = ["category_id", "=", $categoryId];
        }

        $this->topicsModel->setWhere($where);
        $topics = $this->topicsModel->getOrderByData("topic_id");
        return $topics;
    }

    /**
     * To Bring the specific topic details
     * @return type
     */
    public function getTopic()
    {
        $where = [];

        $topicId = $this->request->input("topic_id");
        if ($topicId) {
            $where[] = ["topic_id", "=", $topicId];
        }
        $this->topicsModel->setWhere($where);
        $topic = $this->topicsModel->getData();

        return $topic;
    }

    /**
     * To create the topic
     */
    public function createTopic()
    {
        //validations
        if ($this->topicValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->topicValidator())
                            ->withInput();
        }

        $insertArray = [
            "topic_title" => $this->request->input("topic_title"),
            "topic_description" => $this->request->input("topic_description"),
            "category_id" => $this->request->input("category_id"),
            "updated_by" => $this->request->session()->get('user_id'),
            "created_by" => $this->request->session()->get('user_id'),
            "status" => "active"
        ];


        $this->topicsModel->setInsertDataWithDates($insertArray);
        $topicId = $this->topicsModel->insertData();

        return true;
    }

    /**
     * insert topic validations
     * @return type
     */
    public function topicValidator()
    {

        return Validator::make($this->request->all(), [
                    'topic_title' => 'required',
                    'topic_description' => 'required',
                    'category_id' => 'required|integer',
        ]);
    }

    /**
     * To delete the topic
     */
    public function deleteTopic()
    {
        //validations
        if ($this->deletedTopicValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->deletedTopicValidator())
                            ->withInput();
        }
        $topic_id = $this->request->input("topic_id");
        //delete logic
        $whereArray = [
            "topic_id" => $topic_id,
        ];
        $this->topicsModel->setWhere($whereArray);
        $this->topicsModel->deleteData();

        //delete the comments
        $this->deleteTopicComments($topic_id);

        return TRUE;
    }

    /**
     * delete survey related validations
     * @return type
     */
    public function deletedTopicValidator()
    {
        return Validator::make($this->request->all(), [
                    'topic_id' => 'required|integer'
        ]);
    }

    /**
     * To delete the Specific topic related Comments
     * @param type $topic_id
     */
    public function deleteTopicComments($topic_id)
    {
        $whereArray = [
            "topic_id" => $topic_id,
        ];
        $this->topicsModel->setTableName("comments");
        $this->topicsModel->setWhere($whereArray);
        $this->topicsModel->deleteData();
    }

    /**
     * To Update the topic details
     */
    public function updateTopic()
    {
        //validations
        if ($this->topicValidator()->fails()) {
            return redirect()->back()
                            ->withErrors($this->topicValidator())
                            ->withInput();
        }

        //update logic
        $updateArray = [
            "topic_title" => $this->request->input("topic_title"),
            "topic_description" => $this->request->input("topic_description"),
            "category_id" => $this->request->input("category_id"),
        ];

        $whereArray = [
            ["topic_id", '=', $this->request->input("topic_id")]
        ];

        $this->topicsModel->setUpdateDataWithDates($updateArray);
        $this->topicsModel->setWhere($whereArray);
        $this->topicsModel->updateData();
        return true;
    }

    /**
     * To Bring the topic related flagged comments list
     * @return type
     */
    public function getTopicReviewComments()
    {
        $where = [["comments.status", "=", "review"]];
        $this->topicsModel->setWhere($where);
        $topic = $this->topicsModel->getTopicComments();

        return $topic;
    }

    /**
     * To Bring the specific topic related comments & topic information
     * @return type
     */
    public function getTopicComments()
    {
        $where = [["topics.topic_id", "=",  $this->request->input("topic_id")]];
        $this->topicsModel->setWhere($where);
        $topic = $this->topicsModel->getTopicComments();

        return $topic;
    }

    /**
     * To Remove the topics & comments under specified 
     */
    public function deleteCategoryTopics()
    {
        $topics = $this->getTopicList();
        if (count($topics) > 0) {
            $topicIds = [];
            foreach ($topics as $topic) {
                $topicIds[] = $topic->topic_id;
            }

            //Remove topis
            $whereArray = [
                "category_id" => $this->request->input("category_id"),
            ];
            $this->topicsModel->setTableName("topics");
            $this->topicsModel->setWhere($whereArray);
            $this->topicsModel->deleteData();

            //remove the comments
            $topicListString = implode(",", $topicIds);
            $this->topicsModel->deleteComments($topicListString);
        }
    }

}
