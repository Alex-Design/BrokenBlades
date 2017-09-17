<?php

namespace GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public $entityManager;
    
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

}
