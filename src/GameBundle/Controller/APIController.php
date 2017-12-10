<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use GameBundle\Handler\APIHandler;

/* 
 * This file will handle the routing and pass to the handler methods,
 * and return the handler's responses
 */ 
class APIController extends Controller
{
    public $entityManager;
    public $apiHandler;
    
    // example setup
    public function APIAction()
    {
        $APIHandler = $this->callAPIHandler();
        return $APIHandler->APILocationAction();
    }
    
    public function handleActionAction(Request $request, $action, $entity) 
    {
        $APIHandler = $this->callAPIHandler();
        $methodToCall = 'API' . ucfirst($action) . ucfirst($entity) . 'Action'; // e.g: APICreateCharacterAction
        return $APIHandler->$methodToCall($request);
    }
   
    // Method to hold further constructor arguments
    public function callAPIHandler() 
    {
        $this->setup();
        $APIHandler = new APIHandler($this->entityManager);
        return $APIHandler;
    }
    
    // Call this to set up the entity manager etc where required
    public function setup()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
    }

}
