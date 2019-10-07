<?php

namespace GestionBoutiquesBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function testVerifierdemandes()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin/verifierDemandes');
    }

}
