<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TopicService;
use App\Services\CategoryService;
use Session;

class TopicController extends Controller
{

    public function __construct(Request $request, TopicService $topicService)
    {
        $this->request = $request;
        $this->topicService = $topicService;
        $this->categoryService = new CategoryService($this->request);
    }

    public function createTopicView()
    {
        $categories = $this->categoryService->getCategoryList();
        return view('message-board.topic', ["title" => "Create Topic",
            "topic" => "",
            "categories" => $categories
        ]);
    }

    public function updateTopicView($topic_id)
    {
        $this->request->request->add(['topic_id' => $topic_id]);
        $details = $this->topicService->getTopic();
        if ($details == NULL) {
            return redirect('/dashboard');
        }
        $topic = (array) $details[0];
        $categories = $this->categoryService->getCategoryList();
        return view('message-board.topic', ["title" => "Update Topic",
            "topic" => $topic,
            "categories" => $categories
        ]);
    }

    public function createTopic()
    {
        $this->topicService->createTopic();
        return redirect('topics/list');
    }

    /**
     * To update the posted topic details
     * @return type
     */
    public function updateTopic()
    {
        $this->topicService->updateTopic();
        return redirect('/topics/update/' . $this->request->input("topic_id"))
                        ->with('status', 'Topic updated successfully');
    }

    /**
     * To Bring the Topic list
     * @return type
     */
    public function getTopicList($categoryId = NULL)
    {
        $this->request->request->add(['category_id' => $categoryId]);
        $categories = $this->categoryService->getCategoryList();
        if ($categories == NULL) {
            return redirect('/dashboard');
        }
        if (empty($categoryId)) {
            $this->request->request->add(['category_id' => $categories[0]->category_id]);
        }
        $lists = $this->topicService->getTopicList();
        return view('message-board.topicList', ["title" => "All Topics",
            "topicList" => $lists,
            "categories" => $categories,
            "category_id" => $this->request->input("category_id")
        ]);
    }

    /**
     * To delete the specified topic id
     * @return type
     */
    public function deleteTopic($topic_id)
    {
        $this->request->request->add(['topic_id' => $topic_id]);
        $details = $this->topicService->getTopic();
        $this->topicService->deleteTopic();
        Session::flash('message', 'Success! Record is deleted successfully.');
        return redirect('/topics/list/' . $details[0]->category_id);
    }

    public function topicDetailsView($topic_id)
    {
        $this->request->request->add(['topic_id' => $topic_id]);
        $topicComments = $this->topicService->getTopicComments();

        return view('message-board.topicDetails', ["title" => "Topic Details",
            "topic" => $topicComments
        ]);
    }

    public function flaggedComments()
    {

        $lists = $this->topicService->getTopicReviewComments();
        return view('message-board.flaggedCommentsList', ["title" => "Marked Comments",
            "topicList" => $lists,
        ]);
    }

}
