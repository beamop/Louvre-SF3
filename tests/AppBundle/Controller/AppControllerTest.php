<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppControllerTest extends WebTestCase
{
    public function testReservationIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/reservation');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testReservationContent()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/reservation');

        $this->assertSame(1, $crawler->filter('html:contains("Billetterie - Musée du Louvre")')->count());
    }

    public function testAddReservation()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/reservation');

        $form = $crawler->selectButton('Valider la réservation')->form();
        $form['appbundle_reservation[dateReservation]'] = '09-05-2018';
        $form['appbundle_reservation[billets][0][nom]'] = 'PHPUnit';
        $form['appbundle_reservation[billets][0][prenom]'] = 'PHPUnit';
        $form['appbundle_reservation[billets][0][pays]'] = 'FR';
        $form['appbundle_reservation[billets][0][dateNaissance][day]'] = 01;
        $form['appbundle_reservation[billets][0][dateNaissance][month]'] = 01;
        $form['appbundle_reservation[billets][0][dateNaissance][year]'] = 2000;
        $form['appbundle_reservation[email]'] = 'PHPUnit@bug.lol';

        $client->submit($form);

        $crawler = $client->followRedirect();

        $this->assertSame(1, $crawler->filter('html:contains("Réservation - Votre billet")')->count());
    }

    public function testConfirmationAllowed()
    {
        $client = static::createClient();
        $client->request('GET', '/confirmation/1');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testMerciForbidden()
    {
        $client = static::createClient();
        $client->request('GET', '/merci/1');

        $this->assertSame(403, $client->getResponse()->getStatusCode());
    }
}