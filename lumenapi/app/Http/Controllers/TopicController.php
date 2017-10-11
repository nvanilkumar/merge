<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Validator;
use App\Services\TopicService;

class TopicController extends Controller
{

    protected $_validator;
    protected $request;
    protected $linkService;

    public function __construct(Validator $validator, Request $request, TopicService $topicService)
    {
        $this->_validator = $validator;
        $this->request = $request;
        $this->topicService = $topicService;
    }
    
    /**
     * @SWG\Get(
     *     path="categories/list",
     *     summary="Get the Categories list",
     *     description="To get the Categories list",
     *     produces= {"application/json"},
     *     tags={"Message Board"},
     *     @SWG\Response(
     *         response=200,
     *         description="categories list",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="No data available",
     *     )
     * )
     */
    public function categoryList()
    {
        $details = $this->topicService->categoryList();
        return $this->success($details);
    }    
    
     /**
     * @SWG\Get(
     *     path="topics/list",
     *     summary="Get the Category Related Topic list",
     *     description="To get the Category Related Topic list",
     *     produces= {"application/json"},
     *     tags={"Message Board"},     
     *  @SWG\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Category id",
     *         type="integer",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *  @SWG\Parameter(
     *         name="page",
     *         in="query",
     *         description="Current Page",
     *         type="integer",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *  @SWG\Parameter(
     *         name="page_size",
     *         in="query",
     *         description="Records per page",
     *         type="integer",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *  @SWG\Parameter(
     *         name="topic_name",
     *         in="query",
     *         description="Topic Name",
     *         type="string",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Topic list",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="No data available",
     *     )
     *      
     *      
     * )
     */
    public function index()
    {
        $status = $this->_validator->validate("topicListValidations");
        $details = $this->topicService->topicList();
        return $this->success($details);
    } 
    
    /**
     * @SWG\Put(
     *     path="topics/create",
     *     summary="To insert the topic details",
     *     description="To insert category specific the topic details",
     *     produces= {"application/json"},
     *     tags={"Message Board"},
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         description="Create Topic Object",
     *         required=true,
     *          @SWG\Schema(
     *              required={"category_id","topic_name", "topic_description",	"user_id"},
     *              @SWG\Property(property="category_id",  type="integer",  ),
     *              @SWG\Property(property="topic_name",  type="string",  ),
     *              @SWG\Property(property="topic_description",  type="string",  ),
     *              @SWG\Property(property="user_id",  type="integer",  ),
     *          ),
     *      ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successfully inserted",
     *        
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="missing property name",
     *     )
     * )
     */
    public function createTopic()
    {
        $status = $this->_validator->validate("createTopicValidations");
        $details = $this->topicService->createTopic();
        return $this->success($details);
    }        
    
    /**
     * @SWG\Post(
     *     path="topics/{topic_id}/reply",
     *     summary="To Send the topic related Comment",
     *     description="To Send the topic related Comment",
     *     produces= {"application/json"},
     *     tags={"Message Board"},
     *     @SWG\Parameter(
     *         name="topic_id",
     *         in="path",
     *         description="selected topic id",
     *         required=true,
     *         format="int64",
     *         type="integer" 
     *     ),
     *     @SWG\Parameter(
     *         name="user_id",
     *         in="formData",
     *         description="login user id",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *     @SWG\Parameter(
     *         name="comment_description",
     *         in="formData",
     *         description="Commented text",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successfully inserted",
     *        
     *     ),
     *     @SWG\Response(
     *         response="401",
     *         description="Invalid topic id",
     *     )
     *      
     *      
     * )
     */
    public function createComment($topic_id)
    { 
        $this->request->request->add(['topic_id'=> $topic_id]);
        $this->_validator->validate("createCommentValidations");

        $details = $this->topicService->createComment();
        return $this->success($details);
    } 
    
    /**
     * @SWG\Get(
     *     path="topics/{topic_id}",
     *     summary="Get the Topic list",
     *     description="To get the Topic list",
     *     produces= {"application/json"},
     *     tags={"Message Board"},
     *     @SWG\Parameter(
     *         description="topic id",
     *         format="int64",
     *         in="path",
     *         name="topic_id",
     *         required=true,
     *         type="integer"
     *     ),
     *  @SWG\Parameter(
     *         name="page",
     *         in="query",
     *         description="Current Page",
     *         type="integer",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *  @SWG\Parameter(
     *         name="page_size",
     *         in="query",
     *         description="Records per page",
     *         type="integer",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),     
     *     @SWG\Response(
     *         response=200,
     *         description="topic details",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="No data available",
     *     )
     * )
     */
    public function getTopic($topic_id)
    {
        
        $this->request->request->add(['topic_id'=> $topic_id]);
        $this->_validator->validate("getTopicValidations");
        $details = $this->topicService->getTopic();
        return $this->success($details);
    }  
    
    /**
     * @SWG\Get(
     *     path="comments/review/{comment_id}",
     *     summary="Change the comment status",
     *     description="To change the comment status",
     *     produces= {"application/json"},
     *     tags={"Message Board"},
     *     @SWG\Parameter(
     *         description="comment_id",
     *         format="int64",
     *         in="path",
     *         name="comment_id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successfully marked comment for reviewed "
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Invalid comment id",
     *     )
     * )
     */
    public function markComment($comment_id)
    {
        $this->request->request->add(['comment_id'=> $comment_id]);
        $this->_validator->validate("markCommentValidations");
        $details = $this->topicService->markComment();
        return $this->success($details);
    }        

  
}
