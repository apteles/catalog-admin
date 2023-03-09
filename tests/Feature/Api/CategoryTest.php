<?php

namespace Tests\Feature\Api;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
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
        $response->assertJsonStructure([
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
                'to',
                'from'
            ]
        ]);

    }

    public function testItShouldPaginate(): void
    {
        Category::factory()->count(30)->create();

        $response = $this->getJson(self::ENDPOINT . '?page=2');
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(2, $response['meta']['current_page']);
    }

    public function testItShouldReturnNotFound(): void
    {
        $response = $this->getJson(self::ENDPOINT . '/any-id');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testItShouldReturnAExistingCategory(): void
    {
        $category = Category::factory()->create();
        $response = $this->getJson(self::ENDPOINT  . '/' . $category->id);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(1);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
            ]
        ]);

        $this->assertEquals(
            expected: $category->id,
            actual: $response['data']['id']
        );

        $this->assertDatabaseHas('categories', [
            'id' => (string) $category->id,
            'status' => $category->status,
        ]);
        $this->assertEquals(
            expected: $category->status,
            actual: $response['data']['is_active']
        );
    }

    public function testItShouldNotBeAbleStoreCategoryWithEmptyBody()
    {
        $body = [];

        $response = $this->postJson(
            self::ENDPOINT,
            $body,
        );
        $response->assertStatus(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );

        $response->assertJsonStructure([
            'message',
            'errors' => ['name']
        ]);
    }

    public function testItShouldBeAbleStoreCategory()
    {
        $body = [
            'name' => 'Foo',
            'description' => 'foo'
        ];

        $response = $this->postJson(
            self::ENDPOINT,
            $body,
        );
        $response->assertStatus(
            Response::HTTP_CREATED
        );

        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'description',
                'is_active',
                'created_at'
            ],
        ]);
    }

    public function testItShouldNotUpdateCategoryThatNotExists(): void
    {
        $body = [
            'name' => 'Bar'
        ];
        $response = $this->putJson(
            self::ENDPOINT . '/{invalid-id}',
            $body,
        );
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testItShouldNotUpdateCategoryWithBodyIsEmpty(): void
    {
        $body = [];
        $response = $this->putJson(
            self::ENDPOINT . '/{invalid-id}',
            $body,
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testItShouldUpdateCategory(): void
    {
        $category = Category::factory()->create();

        $body = [
            'name' => 'Foo',
        ];
        $response = $this->putJson(
            self::ENDPOINT . "/{$category->id}",
            $body,
        );

        $this->assertDatabaseHas('categories', [
            'name' => 'Foo'
        ]);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testItShouldNotDeleteCategoryThatNotExists(): void
    {
        $response = $this->deleteJson(
            self::ENDPOINT . '/{invalid-id}',
        );
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testItShouldDeleteCategory(): void
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson(
            self::ENDPOINT . "/{$category->id}",
        );
        $response->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertSoftDeleted('categories', [
            'id' =>  $category->id
        ]);
    }
}
