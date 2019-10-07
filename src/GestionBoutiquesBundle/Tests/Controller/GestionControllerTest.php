<?php

namespace GestionBoutiquesBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GestionControllerTest extends WebTestCase
{
    public function testDemandeboutique()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/demandeBoutique');
    }

    public function testAfficherboutiques()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/AfficherBoutiques');
    }

    public function testSupprimerboutiques()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/SupprimerBoutiques');
    }

    public function testModiferboutique()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ModiferBoutique');
    }

}
