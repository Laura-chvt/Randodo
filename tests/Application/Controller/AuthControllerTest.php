<?php

namespace App\Tests\Application\Controller;

use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Attribute\ResetDatabase;

#[ResetDatabase]
class AuthControllerTest extends WebTestCase
{
    /*
    * Test de la page login importé du cours
    */
    public function testLoginPageShow(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        
        $this->assertResponseIsSuccessful();
    }
    
    /*
    * Test de la connexion réussie importé du cours
    */
    public function testLoginSuccessWithCorrectCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $objUser = UserFactory::createOne();

        $client->submitForm('Se connecter', [
            '_username' => $objUser->getEmail(),
            '_password' => UserFactory::DEFAULT_PASSWORD
        ]);

        $this->assertResponseRedirects();

        $client->followRedirect();
        $this->assertRouteSame('app_hike_index');
    }
    
    /*
    * Test de la connexion échouée cause mdp importé du cours
    */
    public function testLoginFailedWithBadPassword(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $objUser = UserFactory::createOne();

        $client->submitForm('Se connecter', [
            '_username' => $objUser->getEmail(),
            '_password' => "BadPassword"
        ]);

        $this->assertResponseRedirects();  
        $client->followRedirect();
        $this->assertRouteSame('app_login');    
        
        $this->assertAnySelectorTextContains('div', 'Invalid credentials.');
    }
    
    /*
    * Test de la connexion échouée cause mail importé du cours
    */
    public function testLoginFailedWithBadEmail(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $client->submitForm('Se connecter', [
            '_username' => "NotExist@mail.com",
            '_password' => "BadPassword"
        ]);

        $this->assertResponseRedirects();  
        $client->followRedirect();
        $this->assertRouteSame('app_login');    
        $this->assertAnySelectorTextContains('div', 'Invalid credentials.');
        
    }
}
