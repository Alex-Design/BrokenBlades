<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use GameBundle\Repository\LootGroupRepository;

use GameBundle\Entity\EvolvedItem;

class DefaultController extends Controller
{
    public $entityManager;
    
    public function indexAction()
    {
        return $this->render('GameBundle:Default:index.html.twig');
    }
    
    public function playAction()
    {
        $this->setup();
        
//        $test = $this->entityManager->getRepository('GameBundle:LootGroup')->find(1); 
//        $test2 = $test->getLootTables();
//
//        foreach ($test2 as $id) {
//            var_dump($id->getName()); die;
//        }

        $gameplayFrontend = file_get_contents('GameFrontend/gameFrontend.html');
        return new Response($gameplayFrontend);
    }
    
    /*
     * Usage: Page (turn into AJAX later)
     */
    public function moveAction($characterId, $locationId)
    {
        $this->setup();
         
        $location = $this->entityManager->getRepository('GameBundle:Location')->find($locationId);     
        $character = $this->entityManager->getRepository('GameBundle:GameCharacter')->find($characterId);
        
        $character->setCurrentLocation($location);
        
        $this->entityManager->persist($character);
        $this->entityManager->flush();
        
        // final version would remove this current player from the list
        $otherPlayers = $this->entityManager->getRepository('GameBundle:GameCharacter')->findByCurrentLocation($locationId);
        
        // TODO refactor
        if ($location->getNorth()) {
            $north = $location->getNorth()->getId();
        } else {
            $north = null;
        }
        
        if ($location->getEast()) {
            $east = $location->getEast()->getId();
        } else {
            $east = null;
        }
        
        if ($location->getSouth()) {
            $south = $location->getSouth()->getId();
        } else {
            $south = null;
        }
        
        if ($location->getWest()) {
            $west = $location->getWest()->getId();
        } else {
            $west = null;
        }
        
        return $this->render('GameBundle:Default:location.html.twig', 
                [
                    'location' => $location, 
                    'character' => $character,
                    'otherPlayers' => $otherPlayers,
                    'north' => $north,
                    'east' => $east,
                    'south' => $south,
                    'west' => $west,
                ]
        );
    }
    
    /*
     * Usage: AJAX
     */
    public function pickUpItemAction($characterId, $itemId)
    {
        $this->setup();
        
        try {
            $character = $this->entityManager->getRepository('GameBundle:GameCharacter')->find($characterId);
            $item = $this->entityManager->getRepository('GameBundle:Item')->find($itemId);

            $characterInventory = $character->getInventory();

            $evolvedItem = new EvolvedItem();
            $evolvedItem->setOriginalItem($item);
            $evolvedItem->setInventory($characterInventory);

            // TODO: split this into its own method, get all relevant properties from Item and set all onto EvolvedItem
            $evolvedItem->setName($item->getName());

            $this->entityManager->persist($evolvedItem);
            $this->entityManager->flush(); 
            
            $success = true;
            $message = $evolvedItem->getName() . ' picked up by ' . $character->getName();
            
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        
        $JSONResponse = json_encode(['success' => $success, 'message' => $message]);
        $response = new Response($JSONResponse, 200);
        return $response;
    }
    
    /*
     * Testing the API
     * Usage: AJAX
     */
    public function apiLocationAction()
    {
        $responseString = json_encode(['success' => true, 'message' => 'well done']);
        
        $response = new Response($responseString);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        
        return $response;
    }
    
    // Call this at the start of each other function where required
    public function setup()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
    }

}
