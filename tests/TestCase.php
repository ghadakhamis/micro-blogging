<?php

namespace Tests;

use Artisan;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $faker;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        $this->createFaker();
    }

    public function createFaker() 
    {
        $this->faker = Faker::create();
    }
}
