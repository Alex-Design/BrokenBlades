<?php

namespace ManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ManagementBundle:Default:index.html.twig');
    }
}
