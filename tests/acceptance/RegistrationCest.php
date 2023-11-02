<?php


namespace App\Tests\acceptance;

use App\Tests\AcceptanceTester;

class RegistrationCest
{
    public function frontpageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Welcome to LearnLex');
    }

    public function registrationPageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/register');
        $I->see('Register');
        $I->seeElement('#registration_form_username'); //id-name of username html tag
        $I->seeElement('#registration_form_email');
        $I->seeElement('#registration_form_roles');
        $I->seeElement('#registration_form_plainPassword');
        $I->seeElement('#registration_form_agreeTerms');
        $I->seeElement('button[type="submit"]');
    }

    public function registrationFormWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/register');
        $I->fillField('registration_form[username]', 'anotherStudent'); //name-name of username html tag
        $I->fillField('registration_form[email]', 'anotherStudent@gmail.com');
      
        $I->fillField('registration_form[plainPassword]', '123456');
        $I->checkOption('I agree to the terms and conditions');
        $I->click('Register');
        $I->amOnPage('/login');
        $I->see('Sign in');
    }
}

