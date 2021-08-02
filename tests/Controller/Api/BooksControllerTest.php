<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BooksControllerTest extends WebTestCase
{
    public function testApiBooksList(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/books');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
    }
}
