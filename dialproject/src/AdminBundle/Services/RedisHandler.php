<?php

namespace AdminBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * To communicate with the Redis server like setting the key value 
 * connect to the server and getting the key values
 */
class RedisHandler
{

    private $connection;

    public function __construct($request, $em, $doctrine, Container $container)
    {
        $this->doctrine = $doctrine;
        $this->em = $em;
        $this->request = $request->getCurrentRequest();
        $this->connection = $this->em->getConnection();

        $this->container = $container;
        $this->connectToRadisServer();
    }

    /**
     * To connect to the server with given host name & port number
     */
    public function connectToRadisServer()
    {
        $hostName = $this->container->getParameter('redishostname');
        $portNumber = $this->container->getParameter('redisportnumber');
        $this->redis = new \Redis();

        $this->redis->connect($hostName, $portNumber);
        $this->redis->auth('ces88in##redis');
    }

    /**
     * To set the key & value
     * @param type $keyName
     * @param type $value
     */
    public function setRadisKeyValue($keyName, $value)
    {
        $this->redis->set($keyName, $value);
    }

    /**
     * To get the specified key value
     * @param type $keyName
     */
    public function getRadisKeyValue($keyName)
    {
        return $this->redis->get($keyName);
    }

    public function setRedisArrayElement($keyName, $value)
    {
        $response = $this->getRadisKeyValue($keyName);

        $response = json_decode($response);

        //Check the value exist in the array before kept the value
        if (count($response) > 0) {
            $status = array_search($value, $response);
            if ($status === false) {
                $response[] = $value;
            }
        } else {
            $response[] = $value;
        }
        $this->redis->set($keyName, json_encode($response));
    }

    public function deleteRedisArrayElement($keyName, $value)
    {
        $response = $this->getRadisKeyValue($keyName);

        $response = json_decode($response);
        //Check the value exist in the array before kept the value
        if (count($response) > 0) {

            $deleteIndex = array_search($value, $response);
            if ($deleteIndex !== false) {
                array_splice($response, $deleteIndex, 1);
                $this->redis->set($keyName, json_encode($response));
            }
        }
    }

}
