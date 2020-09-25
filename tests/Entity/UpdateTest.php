<?php
namespace App\Tests\Entity;

use App\Entity\Update;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UpdateTest extends KernelTestCase{
    
    public function testTitle(){
        $update=(new Update())
        ->setContent('sujet du test');
        
        self::bootKernel();
        $error = self::$container->get('validator')->validate($update);
        $this->assertCount(0,$error);
    }
}