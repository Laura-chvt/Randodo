<?php

namespace App\Tests\Application\Controller;

use App\Factory\HikeFactory;
use App\Factory\LocationFactory;
use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Attribute\ResetDatabase;
use Zenstruck\Foundry\Attribute\WithStory;

#[ResetDatabase]
class HikeControllerTest extends WebTestCase
{
    /*
    * Fonction de test de la création d'une rando par un modérateur
    */
    public function testCreateHike(): void
    {
        $client = static::createClient();
        $user = UserFactory::createOne(['roles' => ['ROLE_MODO']]);
        $location = LocationFactory::createOne(['name' => 'Massif des Vosges']);
        $client->loginUser($user); 
        $client->request('GET', '/hike/create');
        $client->submitForm('Ajouter', [
            'hike_create_form[name]'       => 'La boucle des Lacs',
            'hike_create_form[time]'       => 120, 
            'hike_create_form[location]'   => $location->getId(),
            'hike_create_form[height]'     => 120, 
            'hike_create_form[length]'     => 3.5,
            'hike_create_form[level]'      => 'Facile',
            'hike_create_form[family]'     => true,
            'hike_create_form[description]'=> "C'est une description",
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertRouteSame('app_hike_show'); 
    }

    /*
    * Fonction de test de la page index du site
    */
    public function testIndexPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/hike/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', "Explorez l'Alsace en douceur");
        $this->assertAnySelectorTextContains('li', 'Aucune randonnée disponible');
    }

    /*
    * Fonction de test de la page d'affichage d'une randonnée
    */
    public function testPageShowOne(): void
    {
        $client = static::createClient();
        $location = LocationFactory::createOne(['name' => 'Massif des Vosges']);
        $hike = HikeFactory::createOne([
            'name'          => 'rando test',
            'location'      => $location,
            'time'          => 120,
            'level'         => 'Facile',
            'height'        => 120, 
            'length'        => 3.5,
            'family'        => true,
            'description'   => "C'est une description",
        ]);
        $client->request('GET', '/hike/' . $hike->getId());
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $hike->getName());
    }
    
}
