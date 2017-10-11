<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\EventsModel;
use DateHelper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventService
{

    protected $request;
    protected $eventsModel;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->eventsModel = new EventsModel();
    }

    /**
     * To Bring the user specific events list
     */
    public function getEvents()
    {
        $responseMessage = array();

        $whereArray = [
            'user_id' => $this->request->input("user_id"),
            'event_end_date' => DateHelper::todayDate(),
            'status_name' => 'pending',
        ];

        $this->eventsModel->setWhere($whereArray);
        $events = $this->eventsModel->getEvents();

        if ($events == null) {
            throw new NotFoundHttpException(config('mts-config.links.zerorecords'), null, 10060);
        }
         

        $responseMessage["statusCode"] = STATUS_OK;
        $responseMessage["response"]['status'] = true;
        $responseMessage["response"]['events'] = $events;
        return $responseMessage;
    }

    public function changeEventUserStatus()
    {
        $responseMessage = array();
        //check the details first

        $this->eventsModel->setTableName('event_users');
        $whereArray = [
            ["event_id", '=', $this->request->input("event_id")],
            ["user_id", '=', $this->request->input("user_id")],
        ];
        $this->eventsModel->setWhere($whereArray);
        $eventRecord = $this->eventsModel->getData();

        if ($eventRecord == null) {
            throw new NotFoundHttpException(config('mts-config.links.zerorecords'), null, 10060);
        }

        //update if found
        $insertArray = [
            "event_id" => $this->request->input("event_id"),
            "user_id" => $this->request->input("user_id"),
            "user_attend_status" => $this->request->input("status_id")
        ];

        $whereArray = [
            ["event_id", '=', $this->request->input("event_id")],
            ["user_id", '=', $this->request->input("user_id")],
        ];


        $this->eventsModel->setInsertUpdateData($insertArray);
        $this->eventsModel->setWhere($whereArray);
        $this->eventsModel->updateData();

        $responseMessage["statusCode"] = STATUS_OK;
        $responseMessage["response"]['status'] = true;
        $responseMessage["response"]['message'] = config('mts-config.events.statusupdate');
        return $responseMessage;
    }

    /** 
     * To Bring the specific event related details
     * @return type
     * @throws NotFoundHttpException
     */
    public function getSpecificEvent()
    {
        $responseMessage = array();

        $whereArray = [ 'event_id' => $this->request->input("event_id") ,
                         "user_id" => $this->request->input("user_id")
                ];

        $this->eventsModel->setWhere($whereArray);
        $events = $this->eventsModel->getSpecificEvent();
      
        if ($events == null) {
            throw new NotFoundHttpException(config('mts-config.links.zerorecords'), null, 10060);
        }

        $responseMessage["statusCode"] = STATUS_OK;
        $responseMessage["response"]['status'] = true;
        $responseMessage["response"]['event'] = $events[0];
        return $responseMessage;
    }        
}
