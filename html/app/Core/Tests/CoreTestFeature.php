<?php

namespace App\Core\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

abstract class CoreTestFeature extends TestCase
{
    use WithFaker;
    public function setUp(): void
    {
        parent::setUp();

        if (!defined('LARAVEL_START')) {
            define('LARAVEL_START', microtime(true));
        }
    }
}