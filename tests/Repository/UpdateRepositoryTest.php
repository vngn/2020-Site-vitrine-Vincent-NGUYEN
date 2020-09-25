<?php

namespace App\Tests\Repository;

use App\Repository\UpdateRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UpdateRepositoryTest extends KernelTestCase
{
    public function testCount()
    {
        self::bootKernel();
        $update = self::$container->get(UpdateRepository::class)->count([]);
        $this->assertEquals(10, $update);
    }
}
