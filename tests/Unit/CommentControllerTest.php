<?php

namespace Tests\Feature\tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Comment;

class CommentControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    use RefreshDatabase;

    public function testIndex()
    {
        // Create test comments
        Comment::factory()->count(15)->create();

        // Make a request to the index endpoint
        $response = $this->get('/comments');

        // Assert the response status code
        $response->assertStatus(200);

        // Assert the JSON structure
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'username',
                    'comment',
                ]
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links',
                'path',
                'per_page',
                'to',
                'total',
            ],
        ]);

        // Assert the pagination
        $response->assertJson([
            'meta' => [
                'per_page' => 10,
            ],
        ]);

        // Assert the expected comments
        $response->assertJsonCount(10, 'data');
    }

    public function testSearch()
    {
        // Create test comments
        Comment::factory()->count(20)->create();

        // Make a request to the search endpoint with a query
        $response = $this->get('/comments/search?query=a');
        // example query = 'a', confirmed that will be >= 10 data with substring 'a'

        // Assert the response status code
        $response->assertStatus(200);

        // Assert the JSON structure
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'username',
                    'comment',
                ]
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links',
                'path',
                'per_page',
                'to',
                'total',
            ],
        ]);

        // Assert the pagination
        $response->assertJson([
            'meta' => [
                'per_page' => 10,
            ],
        ]);

        // Assert the expected comments matching the search query
        $response->assertJsonCount(10, 'data');
    }

    public function testSearchNoData()
    {
        // Create test comments
        Comment::factory()->count(20)->create();

        // Make a request to the search endpoint with a query
        $response = $this->get('/comments/search?query=ahuihwiguqhiqhguq');
        // example query = random string

        // Assert the response status code
        $response->assertStatus(200);

        // Assert the JSON structure
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'username',
                    'comment',
                ]
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links',
                'path',
                'per_page',
                'to',
                'total',
            ],
        ]);

        // Assert the pagination
        $response->assertJson([
            'meta' => [
                'per_page' => 10,
            ],
        ]);

        // Assert the expected comments matching the search query
        $response->assertJsonCount(0, 'data');
    }
}
