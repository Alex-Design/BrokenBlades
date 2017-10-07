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
    public function locationAction($id)
    {
        $this->entityManager = $this->getDoctrine()->getManager();
         
        $location = $this->entityManager->getRepository('GameBundle:Location')->find($id);
      
        $user = $this->getUser();
        
        $user->setCurrentLocation($id);
        
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        
        $otherPlayers = $this->entityManager->getRepository('GameBundle:Account')->findByCurrentLocation($id);
        
        return $this->render('GameBundle:Default:index.html.twig', ['location' => $location, 'otherPlayers' => $otherPlayers]);
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
