<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_example()
    {
        $response = $this->get('/login')->assertStatus(200);
    }
}
