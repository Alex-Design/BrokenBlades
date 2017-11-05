<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public $entityManager;
    
    public function indexAction()
    {
        return $this->render('GameBundle:Default:index.html.twig');
    }
    
    // Testing the location/user idea works
    public function locationAction($characterId, $locationId)
    {
        $this->entityManager = $this->getDoctrine()->getManager();
         
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
    
    // Testing the API
    public function apiLocationAction()
    {
        $responseString = json_encode(['success' => true, 'message' => 'well done']);
        
        $response = new Response($responseString);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        
        return $response;
    }

}
