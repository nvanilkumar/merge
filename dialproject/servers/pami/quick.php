<?php

require_once 'vendor/autoload.php';
require_once 'config.php';

use PAMI\Client\Impl\ClientImpl;
use PAMI\Listener\IEventListener;
use PAMI\Message\Event\EventMessage;
use PAMI\Message\Action\ListCommandsAction;
use PAMI\Message\Action\ListCategoriesAction;
use PAMI\Message\Action\CoreShowChannelsAction;
use PAMI\Message\Action\CoreSettingsAction;
use PAMI\Message\Action\CoreStatusAction;
use PAMI\Message\Action\StatusAction;
use PAMI\Message\Action\ReloadAction;
use PAMI\Message\Action\CommandAction;
use PAMI\Message\Action\HangupAction;
use PAMI\Message\Action\LogoffAction;
use PAMI\Message\Action\AbsoluteTimeoutAction;
use PAMI\Message\Action\OriginateAction;
use PAMI\Message\Action\BridgeAction;
use PAMI\Message\Action\CreateConfigAction;
use PAMI\Message\Action\GetConfigAction;
use PAMI\Message\Action\GetConfigJSONAction;
use PAMI\Message\Action\AttendedTransferAction;
use PAMI\Message\Action\RedirectAction;
use PAMI\Message\Action\DAHDIShowChannelsAction;
use PAMI\Message\Action\DAHDIHangupAction;
use PAMI\Message\Action\DAHDIRestartAction;
use PAMI\Message\Action\DAHDIDialOffHookAction;
use PAMI\Message\Action\DAHDIDNDOnAction;
use PAMI\Message\Action\DAHDIDNDOffAction;
use PAMI\Message\Action\AgentsAction;
use PAMI\Message\Action\AgentLogoffAction;
use PAMI\Message\Action\MailboxStatusAction;
use PAMI\Message\Action\MailboxCountAction;
use PAMI\Message\Action\VoicemailUsersListAction;
use PAMI\Message\Action\PlayDTMFAction;
use PAMI\Message\Action\DBGetAction;
use PAMI\Message\Action\DBPutAction;
use PAMI\Message\Action\DBDelAction;
use PAMI\Message\Action\DBDelTreeAction;
use PAMI\Message\Action\GetVarAction;
use PAMI\Message\Action\SetVarAction;
use PAMI\Message\Action\PingAction;
use PAMI\Message\Action\ParkedCallsAction;
use PAMI\Message\Action\SIPQualifyPeerAction;
use PAMI\Message\Action\SIPShowPeerAction;
use PAMI\Message\Action\SIPPeersAction;
use PAMI\Message\Action\SIPShowRegistryAction;
use PAMI\Message\Action\SIPNotifyAction;
use PAMI\Message\Action\QueuesAction;
use PAMI\Message\Action\QueueStatusAction;
use PAMI\Message\Action\QueueSummaryAction;
use PAMI\Message\Action\QueuePauseAction;
use PAMI\Message\Action\QueueRemoveAction;
use PAMI\Message\Action\QueueUnpauseAction;
use PAMI\Message\Action\QueueLogAction;
use PAMI\Message\Action\QueuePenaltyAction;
use PAMI\Message\Action\QueueReloadAction;
use PAMI\Message\Action\QueueResetAction;
use PAMI\Message\Action\QueueRuleAction;
use PAMI\Message\Action\MonitorAction;
use PAMI\Message\Action\PauseMonitorAction;
use PAMI\Message\Action\UnpauseMonitorAction;
use PAMI\Message\Action\StopMonitorAction;
use PAMI\Message\Action\ExtensionStateAction;
use PAMI\Message\Action\JabberSendAction;
use PAMI\Message\Action\LocalOptimizeAwayAction;
use PAMI\Message\Action\ModuleCheckAction;
use PAMI\Message\Action\ModuleLoadAction;
use PAMI\Message\Action\ModuleUnloadAction;
use PAMI\Message\Action\ModuleReloadAction;
use PAMI\Message\Action\ShowDialPlanAction;
use PAMI\Message\Action\ParkAction;
use PAMI\Message\Action\MeetmeListAction;
use PAMI\Message\Action\MeetmeMuteAction;
use PAMI\Message\Action\MeetmeUnmuteAction;
use PAMI\Message\Action\EventsAction;
use PAMI\Message\Action\VGMSMSTxAction;
use PAMI\Message\Action\DongleSendSMSAction;
use PAMI\Message\Action\DongleShowDevicesAction;
use PAMI\Message\Action\DongleReloadAction;
use PAMI\Message\Action\DongleStartAction;
use PAMI\Message\Action\DongleRestartAction;
use PAMI\Message\Action\DongleStopAction;
use PAMI\Message\Action\DongleResetAction;
use PAMI\Message\Action\DongleSendUSSDAction;
use PAMI\Message\Action\DongleSendPDUAction;

class A implements IEventListener {

    public function __construct($cli, $apiuri) {
        $this->cli = $cli;
        $this->flag = 0;
        $this->dbuser = 'root';
        $this->dbpwd = '';
        $this->customeragentmap = array();
        $this->destchannelmap = array();
        $this->phone = 757676;
        $this->apiuri = $apiuri;
        $this->allowmodes = array(
            'Dialing With Preview', 'Dialing Without Preview', 'Reverse Dialing With Preview', 'Reverse Dialing Without Preview'
        );
    }

    function getAndCallNewCustomer($calleridnum) {
        usleep(5000);
        // print_r($this->customeragentmap);
        // echo "Got into ne call \n\n";
        if (isset($this->customeragentmap[$calleridnum])) {
            //   echo "Call a new customer \n";
            $pest = new Pest($this->apiuri);
            $thing = $pest->post('/api/v1/campaigns', array(
                'AgentId' => $calleridnum
                    )
            );
            $cust = json_decode($thing);
            //   echo "$calleridnum\n";
            // print_r($cust);
            if (isset($cust->status) && $cust->status == 'success' && isset($cust->response->phone_number)) {
                $newphone = $this->phone + 1;

                $this->customeragentmap[$calleridnum]['callerid'] = $calleridnum;
                $this->customeragentmap[$calleridnum]['cust_phone'] = $cust->response->phone_number;
                $this->customeragentmap[$calleridnum]['cd_id'] = $cust->response->cd_id;
                $this->customeragentmap[$calleridnum]['campaign_id'] = $cust->response->campaign_id;
                $this->customeragentmap[$calleridnum]['campaign_type'] = $cust->response->campaign_type;
                $actionid = md5(uniqid());
                $pest = new Pest($this->apiuri);
                $thing = $pest->put('/api/v1/campaigns_data', array(
                    'agentExtn' => $calleridnum,
                    'customerExtn' => $this->customeragentmap[$calleridnum]['cust_phone'],
                    'campaignId' => $this->customeragentmap[$calleridnum]['campaign_id'],
                    'dailStatus' => 'Busy'
                        )
                );
                $thing = $pest->post('/api/v1/live_calls', array(
                    'agentExtn' => $calleridnum,
                    'customerExtn' => $this->customeragentmap[$calleridnum]['cust_phone'],
                    'campaignId' => $this->customeragentmap[$calleridnum]['campaign_id']
                        )
                );
                $priority = 0;
                if (isset($cust->response->priority)) {
                    $priority = $cust->response->priority - 4;
                }
                if ($priority < 0) {
                    $priority = 1;
                }
                // var_dump($this->cli->send(new PingAction()));
                $originateMsg = new OriginateAction('SIP/' . $cust->response->phone_number . '@voipgw');
                $originateMsg->setContext('dialer');
                $originateMsg->setPriority($priority);
                $originateMsg->setExtension($calleridnum);
                $originateMsg->setCallerId($cust->response->phone_number);
                $originateMsg->setVariable('campaign', str_replace(' ', '_', $cust->response->campaign_name));
                $originateMsg->setAsync(false);
                $originateMsg->setActionID($actionid);
                $orgresp = $this->cli->send($originateMsg);
                var_dump($originateMsg);
                var_dump($orgresp);
                $resporg = $orgresp->getKeys()['response'];
                if (in_array($cust->response->campaign_type, $this->allowmodes)) {
                    // if ($cust->response->campaign_type == 'Dialing With Preview') {
                    //Informing wss
                    $entryData = array(
                        'type' => 'call_initiated',
                        'AgentId' => $calleridnum,
                        'customerphone' => $cust->response->phone_number,
                        'campaign_id' => $cust->response->campaign_id
                    );
                    $context = new ZMQContext();
                    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                    $socket->connect("tcp://127.0.0.1:5555");
                    $socket->send(json_encode($entryData));
                }
                if ($resporg != "Success") {
                    $pest = new Pest($this->apiuri);
                    $thing = $pest->put('/api/v1/campaigns_data', array(
                        'agentExtn' => $calleridnum,
                        'customerExtn' => $this->customeragentmap[$calleridnum]['cust_phone'],
                        'campaignId' => $this->customeragentmap[$calleridnum]['campaign_id'],
                        'dailStatus' => 'New Call'
                            )
                    );
                    $thing = $pest->put('/api/v1/live_calls', array(
                        'agentExtn' => $calleridnum,
                        'customerExtn' => $this->customeragentmap[$calleridnum]['cust_phone'],
                        'campaignId' => $this->customeragentmap[$calleridnum]['campaign_id'],
                        'ansTime' => time(),
                        'endTime' => time()
                            )
                    );
                    //Informing wss
                    $entryData = array(
                        'type' => 'call_failed',
                        'AgentId' => $calleridnum,
                        'customerphone' => $cust->response->phone_number,
                        'campaign_id' => $cust->response->campaign_id,
                        'reason' => 'no response'
                    );
                    if (in_array($cust->response->campaign_type, $this->allowmodes)) {
                        //if ($cust->response->campaign_type == 'Dialing With Preview') {
                        $context = new ZMQContext();
                        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                        $socket->connect("tcp://127.0.0.1:5555");
                        $socket->send(json_encode($entryData));
                    }
                    $this->customeragentmap[$calleridnum] = array();
                    $this->getAndCallNewCustomer($calleridnum);
                }
            } else {
                // echo "hello";
                // $this->getAndCallNewCustomer($calleridnum);
            }
        }
    }

    public function handle(EventMessage $event) { //
        $strevt = $event->getKeys()['event'];
        //  echo $strevt."------------------------------\n";
        if ($strevt == 'AgentLogin') {
            $calleridnum = $event->getKeys()['calleridnum'];
            echo "Agent Login $calleridnum \n";
            //Informing wss
            $entryData = array(
                'type' => 'agent_login',
                'AgentId' => $event->getKeys()['calleridnum']
            );
            $context = new ZMQContext();
            $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect("tcp://127.0.0.1:5555");
            $socket->send(json_encode($entryData));
            $this->customeragentmap[$calleridnum] = array();
            //get a customer to call
            $pest = new Pest($this->apiuri);
            $thing = $pest->post('/api/v1/campaigns', array(
                'AgentId' => $event->getKeys()['calleridnum']
                    )
            );
            $cust = json_decode($thing);
            $pest = new Pest($this->apiuri);
            $thing = $pest->put('/api/v1/users', array(
                'agentExtn' => $calleridnum,
                'status' => 1
                    )
            );



            //   print($calleridnum);
            //  print_r($cust);
            //	print('<pre>');
            //   print_r($this->customeragentmap);
            //	print('</pre>');
            // echo "hello";
            // exit;
            if (isset($cust->status) && $cust->status == 'success' && isset($cust->response->phone_number)) {


                $newphone = $this->phone + 1;

                $this->customeragentmap[$calleridnum]['callerid'] = $event->getKeys()['calleridnum'];
                $this->customeragentmap[$calleridnum]['cust_phone'] = $cust->response->phone_number;
                $this->customeragentmap[$calleridnum]['cd_id'] = $cust->response->cd_id;
                $this->customeragentmap[$calleridnum]['campaign_id'] = $cust->response->campaign_id;
                $this->customeragentmap[$calleridnum]['campaign_type'] = $cust->response->campaign_type;
                // $newthing = $pest->put('/api/v1/campaigns', $this->customeragentmap[$calleridnum]);
                // print_r($newthing);
                $actionid = md5(uniqid());
                $pest = new Pest($this->apiuri);
                $thing = $pest->put('/api/v1/campaigns_data', array(
                    'agentExtn' => $event->getKeys()['calleridnum'],
                    'customerExtn' => $this->customeragentmap[$event->getKeys()['calleridnum']]['cust_phone'],
                    'campaignId' => $this->customeragentmap[$event->getKeys()['calleridnum']]['campaign_id'],
                    'dailStatus' => 'Busy'
                        )
                );
                $thing = $pest->post('/api/v1/live_calls', array(
                    'agentExtn' => $event->getKeys()['calleridnum'],
                    'customerExtn' => $this->customeragentmap[$event->getKeys()['calleridnum']]['cust_phone'],
                    'campaignId' => $this->customeragentmap[$event->getKeys()['calleridnum']]['campaign_id']
                        )
                );
                $priority = 0;
                if (isset($cust->response->priority)) {
                    $priority = $cust->response->priority - 4;
                }
                if ($priority < 0) {
                    $priority = 1;
                }
                // var_dump($this->cli->send(new PingAction()));
                $originateMsg = new OriginateAction('SIP/' . $cust->response->phone_number . '@voipgw');
                $originateMsg->setContext('dialer');
                $originateMsg->setPriority($priority);
                $originateMsg->setExtension($event->getKeys()['calleridnum']);
                $originateMsg->setCallerId($cust->response->phone_number);
                $originateMsg->setVariable('campaign', str_replace(' ', '_', $cust->response->campaign_name));
                $originateMsg->setAsync(false);
                $originateMsg->setActionID($actionid);
                $orgresp = $this->cli->send($originateMsg);
                var_dump($originateMsg);
                var_dump($orgresp);
                $resporg = $orgresp->getKeys()['response'];
                if (in_array($cust->response->campaign_type, $this->allowmodes)) {
                    // if ($cust->response->campaign_type == 'Dialing With Preview') {
                    //Informing wss
                    $entryData = array(
                        'type' => 'call_initiated',
                        'AgentId' => $event->getKeys()['calleridnum'],
                        'customerphone' => $cust->response->phone_number,
                        'campaign_id' => $cust->response->campaign_id
                    );
                    $context = new ZMQContext();
                    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                    $socket->connect("tcp://127.0.0.1:5555");
                    $socket->send(json_encode($entryData));
                }
                if ($resporg != "Success") {
                    $pest = new Pest($this->apiuri);
                    $thing = $pest->put('/api/v1/campaigns_data', array(
                        'agentExtn' => $event->getKeys()['calleridnum'],
                        'customerExtn' => $this->customeragentmap[$event->getKeys()['calleridnum']]['cust_phone'],
                        'campaignId' => $this->customeragentmap[$event->getKeys()['calleridnum']]['campaign_id'],
                        'dailStatus' => 'New Call'
                            )
                    );

                    $thing = $pest->put('/api/v1/live_calls', array(
                        'agentExtn' => $event->getKeys()['calleridnum'],
                        'customerExtn' => $this->customeragentmap[$event->getKeys()['calleridnum']]['cust_phone'],
                        'campaignId' => $this->customeragentmap[$event->getKeys()['calleridnum']]['campaign_id'],
                        'ansTime' => time(),
                        'endTime' => time()
                            )
                    );

                    //Informing wss
                    $entryData = array(
                        'type' => 'call_failed',
                        'AgentId' => $event->getKeys()['calleridnum'],
                        'customerphone' => $cust->response->phone_number,
                        'campaign_id' => $cust->response->campaign_id,
                        'reason' => 'no response'
                    );
                    if (in_array($cust->response->campaign_type, $this->allowmodes)) {
                        //if ($cust->response->campaign_type == 'Dialing With Preview') {
                        $context = new ZMQContext();
                        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                        $socket->connect("tcp://127.0.0.1:5555");
                        $socket->send(json_encode($entryData));
                    }
                    $this->customeragentmap[$event->getKeys()['calleridnum']] = array();
                    $this->getAndCallNewCustomer($event->getKeys()['calleridnum']);
                }
            } else {
                // echo "hello";
                // $this->getAndCallNewCustomer($calleridnum);
            }
        }
        if ($strevt == 'Hangup') {
            echo "Hangup event\n";
            $eventdata = $event->getKeys();
            print_r($eventdata);

            echo "Hangup event ----- \n";
            // exit;
            if (strpos($eventdata['channel'], 'Local') !== false) {
                //	echo "Hangup event got in Local \n";
                //  print_r($eventdata);

                if (strpos($eventdata['channel'], ';2') === false) {
                    //	echo "Hangup event got in channel ;2 \n";
                    //  print_r($eventdata);

                    if (isset($this->customeragentmap[$eventdata['exten']]) && isset($this->customeragentmap[$eventdata['exten']]['cust_phone']) && $this->customeragentmap[$eventdata['exten']]['cust_phone'] == $eventdata['connectedlinenum'] && $eventdata['channelstatedesc'] == 'Up') {

                        echo "hangup Reached deep \n";

                        // print_r($eventdata);

                        $pest = new Pest($this->apiuri);
//                        $thing = $pest->put('/api/v1/campaigns_data', array(
//                            'agentExtn' => $eventdata['exten'],
//                            'customerExtn' => $eventdata['connectedlinenum'],
//                            'campaignId' => $this->customeragentmap[$eventdata['exten']]['campaign_id'],
//                            'dailStatus' => 'Complete'
//                                )
//                        );
                        $thing = $pest->put('/api/v1/live_calls', array(
                            'agentExtn' => $eventdata['exten'],
                            'customerExtn' => $this->customeragentmap[$eventdata['exten']]['cust_phone'],
                            'campaignId' => $this->customeragentmap[$eventdata['exten']]['campaign_id'],
                            'endTime' => time()
                                )
                        );

                        if (in_array($this->customeragentmap[$eventdata['exten']]['campaign_type'], $this->allowmodes)) {
                            //if ($this->customeragentmap[$eventdata['exten']]['campaign_type'] == 'Dialing With Preview') {
                            //Informing wss
                            $entryData = array(
                                'type' => 'call_ended',
                                'AgentId' => $eventdata['exten'],
                                'customerphone' => $this->customeragentmap[$eventdata['exten']]['cust_phone'],
                                'campaign_id' => $this->customeragentmap[$eventdata['exten']]['campaign_id']
                            );
                            $context = new ZMQContext();
                            $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                            $socket->connect("tcp://127.0.0.1:5555");
                            $socket->send(json_encode($entryData));
                        }

                        $this->customeragentmap[$eventdata['exten']] = array();

                        $this->getAndCallNewCustomer($eventdata['exten']);
                    }
                }
            }
        }
        if ($strevt == 'AgentLogoff') {
            $calleridnum = $event->getKeys()['calleridnum'];
            echo "Logoff caller id $calleridnum\n";
            //Informing wss
            $entryData = array(
                'type' => 'agent_logout',
                'AgentId' => $event->getKeys()['calleridnum']
            );
            $context = new ZMQContext();
            $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect("tcp://127.0.0.1:5555");
            $socket->send(json_encode($entryData));

            $pest = new Pest($this->apiuri);
            $thing = $pest->put('/api/v1/users', array(
                'agentExtn' => $calleridnum,
                'status' => 0
                    )
            );

            unset($this->customeragentmap[$calleridnum]);
            echo "AgentLogoff event\n";
            print_r($this->customeragentmap);
        }
        if ($strevt == 'DialEnd') {
            echo "Dial end event\n";
            $eventdata = $event->getKeys();
            print_r($eventdata);
            echo "Dial end event --- \n";
            //exit;
            if (strpos($eventdata['destchannel'], 'Local') !== false) {
                //	 echo "Dial end event reached in Local \n";
                //print_r($eventdata);

                if (isset($this->customeragentmap[$eventdata['exten']]) && isset($this->customeragentmap[$eventdata['exten']]['cust_phone']) && $this->customeragentmap[$eventdata['exten']]['cust_phone'] == $eventdata['destconnectedlinenum'] && $eventdata['dialstatus'] == 'ANSWER') {

                    echo "Dial end event reached deep in \n";

                    // print_r($eventdata);
                    //exit;    
                    if (in_array($this->customeragentmap[$eventdata['exten']]['campaign_type'], $this->allowmodes)) {
                        //if ($this->customeragentmap[$eventdata['exten']]['campaign_type'] == 'Dialing With Preview') {
                        $pest = new Pest($this->apiuri);
                        $thing = $pest->put('/api/v1/live_calls', array(
                            'agentExtn' => $eventdata['exten'],
                            'customerExtn' => $this->customeragentmap[$eventdata['exten']]['cust_phone'],
                            'campaignId' => $this->customeragentmap[$eventdata['exten']]['campaign_id'],
                            'ansTime' => time()
                                )
                        );
                        $this->customeragentmap[$eventdata['exten']]['channel'] = $eventdata['channel'];
                        //Informing wss
                        $entryData = array(
                            'type' => 'call_answered',
                            'AgentId' => $eventdata['exten'],
                            'customerphone' => $this->customeragentmap[$eventdata['exten']]['cust_phone'],
                            'campaign_id' => $this->customeragentmap[$eventdata['exten']]['campaign_id']
                        );
                        $context = new ZMQContext();
                        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                        $socket->connect("tcp://127.0.0.1:5555");
                        $socket->send(json_encode($entryData));
                    }
                }
            } else {
                
            }
        }
        if ($strevt == 'DialBegin') {

            $eventdata = $event->getKeys();
            echo $eventdata['dialstring'] . "\n";
            print_r($this->customeragentmap);

            if ($eventdata['dialstring'] == '1234567899874561@wakeup') {
                echo "Dial begin ... \n\n";
                echo $eventdata['dialstring'];
                echo "\n\nDial begin end... \n\n";
                print_r($this->customeragentmap);

                //  if (1 == 2) {
                foreach ($this->customeragentmap as $key => $agentdet) {
                    // $ds = $eventdata['dialstring'];
                    // $pieces = explode("@", $ds);
                    //$piece2 = explode("/",$pieces[0]);
                    //$calleridnum = (isset($piece2[1]))?$piece2[1]:'';
                    $calleridnum = $key;
                    $pest = new Pest($this->apiuri);
                    $thing = $pest->post('/api/v1/users', array(
                        'agentExtn' => $calleridnum
                            )
                    );
                    $cust = json_decode($thing);
                 //   print_r($cust);exit;
                    if (($cust->status == 'success' ) && ($cust->response == 'yes' )) {
                        if (isset($this->customeragentmap[$calleridnum]) && empty($this->customeragentmap[$calleridnum])) {
                            //Check if the agent need to be logoff and logghim off
                            $originateMsg = new AgentLogoffAction($calleridnum, true);
                            $orgresp = $this->cli->send($originateMsg);
                            var_dump($originateMsg);
                            var_dump($orgresp);
                        } else {
                            if (isset($this->customeragentmap[$calleridnum]['channel'])) {
                                $originateMsg = new HangupAction($this->customeragentmap[$calleridnum]['channel']);
                                $orgresp = $this->cli->send($originateMsg);
                                var_dump($originateMsg);
                                var_dump($orgresp);
                            } else {
                                $originateMsg = new AgentLogoffAction($calleridnum, true);
                                $orgresp = $this->cli->send($originateMsg);
                                var_dump($originateMsg);
                                var_dump($orgresp);
                            }
                        }
                    } else {
                        // print_r($this->customeragentmap[$calleridnum]);
                        if (isset($this->customeragentmap[$calleridnum]) && empty($this->customeragentmap[$calleridnum])) {
                            echo "Call a new customer \n";
                            $pest = new Pest($this->apiuri);
                            $thing = $pest->post('/api/v1/campaigns', array(
                                'AgentId' => $calleridnum
                                    )
                            );
                            $cust = json_decode($thing);
                            echo "$calleridnum\n";
                            print_r($cust);
                            if (isset($cust->status) && $cust->status == 'success' && isset($cust->response->phone_number)) {
                                $newphone = $this->phone + 1;

                                $this->customeragentmap[$calleridnum]['callerid'] = $calleridnum;
                                $this->customeragentmap[$calleridnum]['cust_phone'] = $cust->response->phone_number;
                                $this->customeragentmap[$calleridnum]['cd_id'] = $cust->response->cd_id;
                                $this->customeragentmap[$calleridnum]['campaign_id'] = $cust->response->campaign_id;
                                $this->customeragentmap[$calleridnum]['campaign_type'] = $cust->response->campaign_type;
                                $actionid = md5(uniqid());
                                $pest = new Pest($this->apiuri);
                                $thing = $pest->put('/api/v1/campaigns_data', array(
                                    'agentExtn' => $calleridnum,
                                    'customerExtn' => $this->customeragentmap[$calleridnum]['cust_phone'],
                                    'campaignId' => $this->customeragentmap[$calleridnum]['campaign_id'],
                                    'dailStatus' => 'Busy'
                                        )
                                );
                                $priority = 0;
                                if (isset($cust->response->priority)) {
                                    $priority = $cust->response->priority - 4;
                                }
                                if ($priority < 0) {
                                    $priority = 1;
                                }
                                // var_dump($this->cli->send(new PingAction()));
                                $originateMsg = new OriginateAction('SIP/' . $cust->response->phone_number . '@voipgw');
                                $originateMsg->setContext('dialer');
                                $originateMsg->setPriority($priority);
                                $originateMsg->setExtension($calleridnum);
                                $originateMsg->setCallerId($cust->response->phone_number);
                                $originateMsg->setVariable('campaign', str_replace(' ', '_', $cust->response->campaign_name));
                                $originateMsg->setAsync(false);
                                $originateMsg->setActionID($actionid);
                                $orgresp = $this->cli->send($originateMsg);
                                var_dump($originateMsg);
                                var_dump($orgresp);
                                $resporg = $orgresp->getKeys()['response'];
                                if (in_array($cust->response->campaign_type, $this->allowmodes)) {
                                    // if ($cust->response->campaign_type == 'Dialing With Preview') {
                                    //Informing wss
                                    $entryData = array(
                                        'type' => 'call_initiated',
                                        'AgentId' => $calleridnum,
                                        'customerphone' => $cust->response->phone_number,
                                        'campaign_id' => $cust->response->campaign_id
                                    );
                                    $context = new ZMQContext();
                                    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                                    $socket->connect("tcp://127.0.0.1:5555");
                                    $socket->send(json_encode($entryData));
                                }
                                if ($resporg != "Success") {
                                    $pest = new Pest($this->apiuri);
                                    $thing = $pest->put('/api/v1/campaigns_data', array(
                                        'agentExtn' => $calleridnum,
                                        'customerExtn' => $this->customeragentmap[$calleridnum]['cust_phone'],
                                        'campaignId' => $this->customeragentmap[$calleridnum]['campaign_id'],
                                        'dailStatus' => 'New Call'
                                            )
                                    );
                                    //Informing wss
                                    $entryData = array(
                                        'type' => 'call_failed',
                                        'AgentId' => $calleridnum,
                                        'customerphone' => $cust->response->phone_number,
                                        'campaign_id' => $cust->response->campaign_id,
                                        'reason' => 'no response'
                                    );
                                    if (in_array($cust->response->campaign_type, $this->allowmodes)) {
                                        // if ($cust->response->campaign_type == 'Dialing With Preview') {
                                        $context = new ZMQContext();
                                        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
                                        $socket->connect("tcp://127.0.0.1:5555");
                                        $socket->send(json_encode($entryData));
                                    }
                                    $this->customeragentmap[$calleridnum] = array();
                                    $this->getAndCallNewCustomer($calleridnum);
                                }
                            } else {
                                // echo "hello";
                                // $this->getAndCallNewCustomer($calleridnum);
                            }
                        } else {
                            
                        }
                    }
                }
                //	}
            }
        }
        //echo "Got at the end  $strevt \n";
        // echo "Some evnt occured $strevt \n";
    }

}

////////////////////////////////////////////////////////////////////////////////
// Code STARTS.
////////////////////////////////////////////////////////////////////////////////
error_reporting(E_ALL);
ini_set('display_errors', 1);
try {
    $options = array(
        'host' => $astrihost,
        'scheme' => 'tcp://',
        'port' => 5038,
        'username' => $astriuser,
        'secret' => $astripass,
        'connect_timeout' => 10,
        'read_timeout' => 10000000
    );
    $a = new ClientImpl($options);

    $a->registerEventListener(new A($a, $apiuri));
    $a->open();
    $time = time();
    while (true) {//(time() - $time) < 60) // Wait for events.
        usleep(1000); // 1ms delay
        // Since we declare(ticks=1) at the top, the following line is not necessary
        $a->process();
    }
    $a->close(); // send logoff and close the connection.
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}
