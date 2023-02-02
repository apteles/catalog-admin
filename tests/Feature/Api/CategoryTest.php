<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private const ENDPOINT = "/api/categories";

    public function testItShouldListAllCategories(): void
    {
        Category::factory()->count(30)->create();

        $response = $this->getJson(self::ENDPOINT);
        $response->assertStatus(200);
        $response->assertJsonCount(15, 'data');

    }
}
