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

            if ($characterEntity) {
                $success = true;
                $message = 'You have created a new character.';
            } else {
                throw new \Exception('Unable to create new character.'); 
            }
        } catch (\Exception $e) { // TODO BUG: \Exception does not catch mysql errors eg duplicate entries
            $furtherData = null;
            $success = false;
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
        } catch (Exception $e) {
            $furtherData = null;
            $success = false;
            $message = 'Your character could not be saved. Error: ' . $e->getMessage();
        }
        
        return $this->returnStandardizedResponse($success, $message, $furtherData);
    }
    
    public function APIGetCharacterLocationAction($request)
    {
        try {
            $characterId = $request->get('characterId');
            $characterEntity = $this->entityManager->getRepository('GameBundle:GameCharacter')->find($characterId);
        
            if (!$characterEntity) {
                throw new \Exception('Could not find character with ID of ' . $characterId);
            }
            
            $locationEntity = $characterEntity->getCurrentLocation();
            
            $allCharactersPresentArray = [];
            
            $otherCharactersPresentArray = $this->entityManager->getRepository('GameBundle:GameCharacter')
                ->findBy(
                    [
                        'currentLocation' => $locationEntity->getId(),
                        'hasRecentlyActed' => 1,
                    ]
                );
            
            foreach ($otherCharactersPresentArray as $characterPresent) {
                $allCharactersPresentArray[] = 
                    [
                        'id' => $characterPresent->getId(),
                        'name' => $characterPresent->getName(),
                    ];
            }
            
            $furtherData = [
                'locationName' => $locationEntity->getName(),
                'locationDescription' => $locationEntity->getDescription(),
                'allPlayersInLocation' => $allCharactersPresentArray,
                'moveNorth' => $locationEntity->getNorth() ? $locationEntity->getNorth()->getId() : null,
                'moveEast' => $locationEntity->getEast() ? $locationEntity->getEast()->getId() : null,
                'moveSouth' => $locationEntity->getSouth() ? $locationEntity->getSouth()->getId() : null,
                'moveWest' => $locationEntity->getWest() ? $locationEntity->getWest()->getId() : null,
            ];
            $success = true;
            $message = $characterEntity->getName() . ' has moved to ' . $locationEntity->getName();
        } catch (\Exception $e) {
            $furtherData = null;
            $success = false;
            $message = 'Your character could not be moved. Error: ' . $e->getMessage();
        }
        
        return $this->returnStandardizedResponse($success, $message, $furtherData);
    }
    
    public function APIMoveCharacterLocationAction($request)
    {
        $characterId = $request->get('characterId');
        $characterEntity = $this->entityManager->getRepository('GameBundle:GameCharacter')->find($characterId);

        // Later, lock movement to only being allowable from a linked location unless overriden
//        $currentLocation = $characterEntity->getCurrentLocation();
        
        $locationEntity = $this->entityManager->getRepository('GameBundle:Location')
                ->find($request->get('moveTo'));  
        
        $characterEntity->setCurrentLocation($locationEntity);
        
        $this->entityManager->persist($characterEntity);
        $this->entityManager->flush();
        
        return $this->APIGetCharacterLocationAction($request);
    }
    
    public function setCharacterStartingData(GameCharacter $characterEntity)
    {
        $locationEntity = $this->entityManager->getRepository('GameBundle:Location')
                ->find(25);        
       
        $characterEntity->setName(uniqid());
        $characterEntity->setHasRecentlyActed(false);
        $characterEntity->setCurrentLocation($locationEntity);
        
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
