<?php

namespace TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RedirectionCController extends Controller
{
    public function redirectmeAction()
    {
        return $this->render('@TestBundle', array(
            // ...
        ));
    }

    public function testapiAction()
    {
        return $this->render('@TestBundle/index.html.twig', array(
            // ...
        ));
    }

}
