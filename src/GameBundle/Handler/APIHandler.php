<?php

namespace GameBundle\Handler;

use Symfony\Component\HttpFoundation\Response;

use GameBundle\Entity\GameCharacter;

class APIHandler
{
    public $entityManager;
    
    public function __construct($entityManager) 
    {
        $this->entityManager = $entityManager;
    }
    
   
    
    public function APICreateCharacterAction($request)
    {
        // add conditions of failure for certain scenarios eg too many chars per account etc    
        try {
            $characterEntity = new GameCharacter();        
            $this->setCharacterStartingData($characterEntity);

            $accountEntity = $this->entityManager->getRepository('GameBundle:Account')->find($request->get('accountId'));
            $characterEntity->setAccount($accountEntity);
            
            $this->entityManager->persist($characterEntity);
            $this->entityManager->flush();

            $furtherData = [
                'characterId' => $characterEntity->getId(),
            ];

            $success = true;
            $message = 'You have created a new character.';
        } catch (\Exception $e) { 
            $furtherData = null;
            $success = true;
            $message = 'Unable to create new character. Error: ' . $e->getMessage();  
        }
        
        return $this->returnStandardizedResponse($success, $message, $furtherData);
    }
    
    public function APIUpdateCharacterAction($request)
    {
        try {
            $characterId = $request->get('characterId');
            
            $characterEntity = $this->entityManager->getRepository('GameBundle:GameCharacter')->find($characterId);
        
            if (!$characterEntity) {
                throw new \Exception('Could not find character with ID of ' . $characterId);
            }
            
            $name = $request->get('name');
            $characterEntity->setName($name);
            
            $this->entityManager->persist($characterEntity);
            $this->entityManager->flush();
            
            $furtherData = null;
            $success = true;
            $message = 'Your character has been saved.';
        } catch (Exception $ex) {
            $furtherData = null;
            $success = false;
            $message = 'Your character could not be saved. Error: ' . $ex->getMessage();
        }
        
        return $this->returnStandardizedResponse($success, $message, $furtherData);
    }
    
    
    public function setCharacterStartingData(GameCharacter $characterEntity)
    {
        $characterEntity->setName(uniqid());
        $characterEntity->setHasRecentlyActed(false);
        
        $this->entityManager->persist($characterEntity);
        $this->entityManager->flush();
    }
    
    
    
    
    
    
    
    
    
    public function returnStandardizedResponse($success, $message, array $furtherData = null)
    {
        $responseArray = ['success' => $success, 'message' => $message];
        
        if ($furtherData) {
            $responseArray = array_merge($responseArray, $furtherData);
        }
        
        $responseString = json_encode($responseArray);
        
        $response = new Response($responseString);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        
        return $response;
    }
    
    /*
     * Testing the API
     * Usage: AJAX
     */
    public function APILocationAction()
    {
        $responseString = json_encode(['success' => true, 'message' => 'well done']);
        
        $response = new Response($responseString);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        
        return $response;
    }
}
