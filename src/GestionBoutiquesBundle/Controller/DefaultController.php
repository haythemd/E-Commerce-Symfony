<?php

namespace GestionBoutiquesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GestionBoutiquesBundle:Default:index.html.twig');
    }
}
