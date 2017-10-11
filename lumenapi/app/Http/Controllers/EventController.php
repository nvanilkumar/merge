<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Validator;
use App\Services\EventService;

class EventController extends Controller
{

    protected $_validator;
    protected $request;
    protected $eventService;

    public function __construct(Validator $validator, Request $request, EventService $eventService)
    {
        $this->_validator = $validator;
        $this->request = $request;
        $this->eventService = $eventService;
    }

    /**
     * @SWG\Get(
     *     path="events/list",
     *     summary="Get the Events list",
     *     description="To get the user specific active events list",
     *     produces= {"application/json"},
     *     tags={"Events"},
      @SWG\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="login user id",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="events list",
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
        $this->_validator->validate("eventListValidations");
        $details = $this->eventService->getEvents();
        return $this->success($details);
    }

    /**
     * @SWG\Post(
     *     path="events/attende/confirmation",
     *     summary="To Change the Event user status",
     *     description="To Change the user status related to given event",
     *     produces= {"application/json"},
     *     tags={"Events"},
     *  @SWG\Parameter(
     *         name="user_id",
     *         in="formData",
     *         description="login user id",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *  @SWG\Parameter(
     *         name="status",
     *         in="formData",
     *         description="event related user status",
     *         required=true,
     *         type="string",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *  @SWG\Parameter(
     *         name="event_id",
     *         in="formData",
     *         description="Selected Event Id",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="updated status",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Invalid login details (or) event details",
     *     )
     *      
     *      
     * )
     */
    public function changeEventUserStatus()
    {
        $this->_validator->validate("eventUserStatusValidations");
        $details = $this->eventService->changeEventUserStatus();
        return $this->success($details);
    }
    
    /**
     * @SWG\GET(
     *     path="events/{event_id}",
     *     summary="To get the specific event related details",
     *     description="To get the specific event related details",
     *     produces= {"application/json"},
     *     tags={"Events"},
     *     @SWG\Parameter(
     *         description="event_id",
     *         format="int64",
     *         in="path",
     *         name="event_id",
     *         required=true,
     *         type="integer"
     *     ),
     * @SWG\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="login user id",
     *         required=true,
     *         type="integer",
     *         @SWG\Items(type="string"),
     *         collectionFormat="multi"
     *     ),
      *     @SWG\Response(
     *         response=200,
     *         description="event details",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Invalid event details",
     *     )
     *      
     *      
     * )
     */
    public function getEvent($event_id)
    {
        $this->request->request->add(['event_id'=> $event_id]);
        $this->_validator->validate("getEventValidations");
        

        $details = $this->eventService->getSpecificEvent();
        return $this->success($details);
    }        

}
