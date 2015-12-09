<?php

namespace PairPI\Bundle\RecallcrmBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('PairPIRecallcrmBundle:Default:index.html.twig', array('name' => $name));
    }
}
