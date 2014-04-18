<?php

namespace Symfonyse\EventBundle\Tests\Controller;

use Symfonyse\CoreBundle\Tests\BaseTestCase;

class EventControllerTest extends BaseTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', $this->generateUrl('event_index'));

        $this->assertTrue($crawler->filter('html:contains("Detta är våra event")')->count() > 0);
    }

    public function testEntryAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', $this->generateUrl('event', array('permalink'=>'phparlor')));

        $this->assertTrue($crawler->filter('html:contains("Neo4j")')->count() > 0);
    }
}