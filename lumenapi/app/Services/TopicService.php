<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\TopicsModel;
use DateHelper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TopicService
{

    protected $request;
    protected $topicModel;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->topicsModel = new TopicsModel();
    }

    public function categoryList()
    {
        $category_name = $this->request->input("category_name");

        $this->topicsModel->setTableName('categories');
        $whereArray = [];
        if ($category_name) {
            $whereArray = ["category_name" => $category_name];
        }
        $this->topicsModel->setWhere($whereArray);
        $categories = $this->topicsModel->getCategoriesList();


        if (count($categories) == 0) {
            throw new NotFoundHttpException(config('mts-config.links.zerorecords'), null, 10060);
        }

        $responseMessage["statusCode"] = STATUS_OK;
        $responseMessage["response"]['status'] = true;
        $responseMessage["response"]['categories'] = $categories;
        return $responseMessage;
    }

    /**
     * To get topic list
     */
    public function topicList()
    {
        $this->topicsModel->setTableName('topics');
        $whereArray = [];

        if ($this->request->input("category_id")) {
            $whereArray[] = ["category_id", '=', $this->request->input("category_id")];
        }
        if ($this->request->input("topic_name")) {

            $whereArray[] = ["topic_title", 'like', "%" . $this->request->input("topic_name") . "%"];
        }

        //pagination related logic
        $startingIndex = 0;
        $pageNo = 1;
        if ($this->request->input("page")) {
            $pageNo = $this->request->input("page");
        }

        $perPage = 10; //default value
        if ($this->request->input("page_size")) {
            $perPage = $this->request->input("page_size");
        }

        if ($pageNo > 1) {
            $startingIndex = ($pageNo - 1) * $perPage;
        }

        $this->topicsModel->setStartingIndex($startingIndex);
        $this->topicsModel->setRecords($perPage);

        $this->topicsModel->setWhere($whereArray);
        $topics = $this->topicsModel->getTopicList();

        if (count($topics) == 0) {
            throw new NotFoundHttpException(config('mts-config.links.zerorecords'), null, 10060);
        }

        $responseMessage["statusCode"] = STATUS_OK;
        $responseMessage["response"]['status'] = true;
        $responseMessage["response"]['current_page'] = $pageNo;
        $responseMessage["response"]['page_size'] = $perPage;
        $responseMessage["response"]['total_records'] = $topics['count'];
        $responseMessage["response"]['topics'] = $topics['records'];
        return $responseMessage;
    }

    /**
     * To insert the topic information 
     * @return type
     * @throws NotFoundHttpException
     */
    public function createTopic()
    {
        //check the category id
        $this->topicsModel->setTableName('categories');
        $whereArray = [["category_id", '=', $this->request->input("category_id")]];
        $this->topicsModel->setWhere($whereArray);
        $categories = $this->topicsModel->getData();

        if ($categories == NULL) {
            throw new NotFoundHttpException(config('mts-config.topics.category_id_fail'), null, 10060);
        }

        //insert the values
        $this->topicsModel->setTableName("topics");
        $insertArray = [
            "topic_title" => $this->request->input("topic_name"),
            "topic_description" => $this->request->input("topic_description"),
            "created_by" => $this->request->input("user_id"),
            "updated_by" => $this->request->input("user_id"),
            "created_at" => DateHelper::todayDateTime(),
            "updated_at" => DateHelper::todayDateTime(),
            "category_id" => $this->request->input("category_id"),
            "status" => "active"
        ];

        $this->topicsModel->setInsertUpdateData($insertArray);
        $insert_id = $this->topicsModel->insertData();

        $responseMessage["statusCode"] = STATUS_CREATED;
        $responseMessage["response"]['status'] = true;
        $responseMessage["response"]['message'] = config('mts-config.devicetoken.record_created');
        $responseMessage["response"]['insert_id'] = $insert_id;
        return $responseMessage;
    }

    /**
     * To insert the topic information 
     * @return type
     * @throws NotFoundHttpException
     */
    public function createComment()
    {
        //check the topic id
        $this->topicsModel->setTableName('topics');
        $whereArray = [["topic_id", '=', $this->request->input("topic_id")]];
        $this->topicsModel->setWhere($whereArray);
        $topics = $this->topicsModel->getData();

        if ($topics == NULL) {
            throw new NotFoundHttpException(config('mts-config.topics.topic_id_fail'), null, 10060);
        }

        //insert the values
        $this->topicsModel->setTableName("comments");
        $insertArray = [
            "comment_text" => $this->request->input("comment_description"),
            "topic_id" => $this->request->input("topic_id"),
            "created_by" => $this->request->input("user_id"),
            "updated_by" => $this->request->input("user_id"),
            "created_at" => DateHelper::todayDateTime(),
            "updated_at" => DateHelper::todayDateTime(),
            "status" => "active"
        ];

        $this->topicsModel->setInsertUpdateData($insertArray);
        $insert_id = $this->topicsModel->insertData();
        
        //update the topic updated date time
        $this->topicsModel->setTableName("topics");
        $whereArray = [
            ["topic_id", '=', $this->request->input("topic_id")],
        ];
        
        $updatedArray = [
            "updated_at" => DateHelper::todayDateTime(),
        ];

        $this->topicsModel->setInsertUpdateData($updatedArray);
        $this->topicsModel->setWhere($whereArray);
        $this->topicsModel->updateData();

        $responseMessage["statusCode"] = STATUS_CREATED;
        $responseMessage["response"]['status'] = true;
        $responseMessage["response"]['message'] = config('mts-config.devicetoken.record_created');
        $responseMessage["response"]['insert_id'] = $insert_id;
        return $responseMessage;
    }

    public function getTopic()
    {
        $whereArray = [["topics.topic_id", '=', $this->request->input("topic_id")]];

        //pagination related logic
        $startingIndex = 0;
        $pageNo = 1;
        if ($this->request->input("page")) {
            $pageNo = $this->request->input("page");
        }

        $perPage = 10; //default value
        if ($this->request->input("page_size")) {
            $perPage = $this->request->input("page_size");
        }

        if ($pageNo > 1) {
            $startingIndex = ($pageNo - 1) * $perPage;
        }

        $this->topicsModel->setStartingIndex($startingIndex);
        $this->topicsModel->setRecords($perPage);

        $this->topicsModel->setWhere($whereArray);
        $comments = $this->topicsModel->getTopic();

        if ($comments == NULL) {
            throw new NotFoundHttpException(config('mts-config.topics.topic_id_fail'), null, 10060);
        }

        $topicDetails = [];
        $topicDetails['topic_name'] = $comments['records'][0]->topic_title;
        $topicDetails['topic_description'] = $comments['records'][0]->topic_description;
        $topicDetails['created_at'] = $comments['records'][0]->topics_created;

        //set comment count
        $commentCount = $comments['count'];
        $commentsArray = [];
        if ($comments['records'][0]->comment_id == NULL) {
            $commentCount = 0;
        } else {
            //prepare comments array
            foreach ($comments['records']  as $comment) {
                $commentArray = [];
                $commentArray ["comment_id"] = $comment->comment_id;
                $commentArray ["comment_text"] = $comment->comment_text;
                $commentArray ["created_at"] = $comment->created_at;
                $commentArray ["first_name"] = $comment->first_name;
                $commentArray["last_name"] = $comment->last_name;
                $commentsArray[]=$commentArray;
            }
        }

        $responseMessage["statusCode"] = STATUS_OK;
        $responseMessage["response"]['status'] = true;
        $responseMessage["response"]['current_page'] = $pageNo;
        $responseMessage["response"]['page_size'] = $perPage;
        $responseMessage["response"]['topic_details'] = $topicDetails;
        $responseMessage["response"]['total_records'] = $commentCount;
        $responseMessage["response"]['comments'] = $commentsArray;

        return $responseMessage;
    }

    public function markComment()
    {
        $responseMessage = array();
        //check the details first
        $this->topicsModel->setTableName('comments');
        $whereArray = [
            ["comment_id", '=', $this->request->input("comment_id")],
        ];

        $this->topicsModel->setWhere($whereArray);
        $commentDetails = $this->topicsModel->getData();

        if ($commentDetails == null) {
            throw new NotFoundHttpException(config('mts-config.links.zerorecords'), null, 10060);
        }

        //update if found
        $updateArray = [
            "status" => "review",
        ];

        $this->topicsModel->setInsertUpdateData($updateArray);
        $this->topicsModel->setWhere($whereArray);
        $this->topicsModel->updateData();

        $responseMessage["statusCode"] = STATUS_OK;
        $responseMessage["response"]['status'] = true;
        $responseMessage["response"]['message'] = config('mts-config.topics.comment_status');
        return $responseMessage;
    }

}
