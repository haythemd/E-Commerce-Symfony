<?php

namespace TestBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedirectionCControllerTest extends WebTestCase
{
    public function testRedirectme()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/redirectme');
    }

}
