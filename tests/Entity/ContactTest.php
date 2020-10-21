<?php
namespace App\Tests\Entity;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ContactTest extends KernelTestCase{
    
    public function testTitle(){
        $contact=(new Contact())
        ->setName('DOE')
        ->setFirstName('Jane')
        ->setSociety('NBC')
        ->setPost('rôle principal')
        ->setEmail('jane@doe')
        ->setPhone('0123456789')
        ->setTitle('Blindspot')
        ->setContent('Une jeune femme amnésique est retrouvée totalement nue en plein milieu de Times Square à New York, recouverte de tatouages mystérieux, fraîchement réalisés.');

        
        self::bootKernel();
        $error = self::$container->get('validator')->validate($contact);
        $this->assertCount(0,$error);
    }
}