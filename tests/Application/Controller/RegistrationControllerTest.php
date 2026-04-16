<?php

namespace App\Tests\Application\Controller;

use App\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Attribute\ResetDatabase;

#[ResetDatabase]
class RegistrationControllerTest extends WebTestCase
{
    /*
    * Test de la page s'inscrire importé du cours
    */
    public function testRegisterPageShow(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
    }

    /*
    * Test de l'inscription réussie importé du cours
    */
    public function testRegisterSuccess(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
    
        $this->assertResponseIsSuccessful();
        $client->submitForm("Valider", [
            'registration_form[email]'                  => "john.doe@email.com",
            'registration_form[name]'                   => "Doe",
            'registration_form[firstname]'              => "John",
            'registration_form[plainPassword][first]'   => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[plainPassword][second]'  => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[agreeTerms]'             => true,
        ]);

        $this->assertResponseRedirects();
        $this->assertEmailCount(1);
        $email = $this->getMailerMessage();
        $this->assertEmailAddressContains($email, 'To', 'john.doe@email.com');
        $this->assertEmailSubjectContains($email, "Please Confirm your Email");

        $client->followRedirect();
        $this->assertRouteSame('app_hike_index');
    }

    /*
    * Test de l'inscription échouée cause mail existant importé du cours
    */
    public function testRegisterWithExistingEmail(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
        $objExtistingUser = UserFactory::createOne();
        $client->submitForm("Valider", [
            'registration_form[email]'                  => $objExtistingUser->getEmail(),
            'registration_form[name]'                   => "Doe",
            'registration_form[firstname]'              => "John",
            'registration_form[plainPassword][first]'   => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[plainPassword][second]'  => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[agreeTerms]'             => true,
        ]);

        $this->assertResponseStatusCodeSame(422);

        $this->assertAnySelectorTextContains('div', "There is already an account with this email");

        $this->assertEmailCount(0);
    }

    /*
    * Test de l'inscription échouée cause mdp différents importé du cours
    */
    public function testRegisterWithMismatchPassword(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
        $client->submitForm("Valider", [
            'registration_form[email]'                  => "john.doe@email.com",
            'registration_form[name]'                   => "Doe",
            'registration_form[firstname]'              => "John",
            'registration_form[plainPassword][first]'   => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[plainPassword][second]'  => "M?sth3erbe!!zzzzzzzzz",
            'registration_form[agreeTerms]'             => true,
        ]);
        $this->assertResponseStatusCodeSame(422);
        $this->assertAnySelectorTextContains('div', "Les champs doivent être identiques");
        $this->assertEmailCount(0);
    }

    /*
    * Test de l'inscription échouée cause AgreeTerms pas coché importé du cours
    */
    public function testRegisterWithoutAgreeTerms(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
        $client->submitForm("Valider", [
            'registration_form[email]'                  => "john.doe@email.com",
            'registration_form[name]'                   => "Doe",
            'registration_form[firstname]'              => "John",
            'registration_form[plainPassword][first]'   => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[plainPassword][second]'  => "M?sth3erbe!!yyyyyyyyy",
            'registration_form[agreeTerms]'             => false,
        ]);

        $this->assertResponseStatusCodeSame(422);

        $this->assertAnySelectorTextContains('div', "Il faut autoriser notre politique de confidentialité pour s'inscrire");

        $this->assertEmailCount(0);
    }
}
