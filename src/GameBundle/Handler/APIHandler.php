<?php

namespace GameBundle\Handler;

use Symfony\Component\HttpFoundation\Response;

use GameBundle\Entity\GameCharacter;
use GameBundle\Entity\EvolvedItem;

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
                $message = 'Please name your new character.';
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
            
            $droppedLootItems = $this->APIGenerateDroppedLootItems();
                    
            $furtherData = [
                'locationName' => $locationEntity->getName(),
                'locationDescription' => $locationEntity->getDescription(),
                'allPlayersInLocation' => $allCharactersPresentArray,
                'droppedLootItems' => $droppedLootItems,
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
    
    public function APIGetCharacterInventoryAction($request)
    {
        $characterId = $request->get('characterId');
        $characterEntity = $this->entityManager->getRepository('GameBundle:GameCharacter')->find($characterId);

        $inventoryEntity = $characterEntity->getInventory();
        
        if ($inventoryEntity) {
            $inventoryId = $inventoryEntity->getId(); 
        
            $inventoryItems = $this->entityManager->getRepository('GameBundle:EvolvedItem')->findByInventory($inventoryId);

            $characterInventoryItemArray = [];
            
            foreach ($inventoryItems as $inventoryItem) {
                $characterInventoryItemArray[] = 
                        [
                            'name' => $inventoryItem->getName(),
                            'rarityColour' => $inventoryItem->getRarityColour(),
                        ];
            }
            
            $furtherData = ['characterInventoryItems' => $characterInventoryItemArray];
            
            $message = 'Obtained inventory items';
            $success = true;
        } else {
            $furtherData = [];
            $message = 'Could not obtain inventory items';
            $success = false;
        }
        
        return $this->returnStandardizedResponse($success, $message, $furtherData);
    }
    
    // TODO remove hardcoding, use loot table instead
    public function APIGenerateDroppedLootItems()
    {
        $item = $this->entityManager->getRepository('GameBundle:Item')->find(1);
        
        $droppedLootItems = [
            1 => 
                [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'rarityColour' => $item->getRarityColour(),
                ]
        ];
        
        return $droppedLootItems;
    }
    
    public function APIaddCharacterItemAction($request)
    {
        $characterId = $request->get('characterId');
        $itemId = $request->get('itemId');
        $characterEntity = $this->entityManager->getRepository('GameBundle:GameCharacter')->find($characterId);
        $characterInventoryId = $characterEntity->getInventory()->getId();

        try {
            $evolvedItem = $this->takeEvolvedItem($characterInventoryId, $itemId);
            $success = true;
            $message = $evolvedItem->getName() . ' picked up by ' . $characterEntity->getName();
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }

        return $this->returnStandardizedResponse($success, $message);
    }
    
    public function createEvolvedItem($characterInventoryId, $itemId)
    {
        $item = $this->entityManager->getRepository('GameBundle:Item')->find($itemId);
        
        $characterItemsOwned = $this->entityManager->getRepository('GameBundle:EvolvedItem')->findByInventory($characterInventoryId);
        
        if ($characterItemsOwned >= $characterInventory->getSpaces()) {
            throw new \Exception('Inventory limit reached!');
        }
        
        $evolvedItem = new EvolvedItem();
        $evolvedItem->setOriginalItem($item);

        // TODO: split this into its own method, get all relevant properties from Item and set all onto EvolvedItem
        $evolvedItem->setName($item->getName());
        
        $this->entityManager->persist($evolvedItem);
        $this->entityManager->flush(); 
        
        return $evolvedItem;
    }
    
    public function takeEvolvedItem($characterInventoryId, $itemId)
    {
        $characterInventory = $this->entityManager->getRepository('GameBundle:Inventory')->find($characterInventoryId);
        $characterItemsOwned = $this->entityManager->getRepository('GameBundle:EvolvedItem')->findByInventory($characterInventoryId);
        
        if ($characterItemsOwned >= $characterInventory->getSpaces()) {
            throw new \Exception('Inventory limit reached!');
        }
        
        $evolvedItem = $this->createEvolvedItem($itemId);
        $evolvedItem->setInventory($characterInventory);
        
        $this->entityManager->persist($evolvedItem);
        $this->entityManager->flush(); 
        
        return $evolvedItem;
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
