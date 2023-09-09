<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_can_view_home_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
