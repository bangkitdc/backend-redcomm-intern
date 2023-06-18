# Backend Explanation

1. Routes
``` php
/* API for fetch all comments */
Route::get('/comments', [CommentsController::class, 'index']);

/* API for fetch searched comments using query */
Route::get('/comments/search', [CommentsController::class, 'search']);
```

Create simple routes for the backend, ```/comments``` route is for fetch all comments data with pagination (10 per page), and ```/comments/search?query=example``` route is for search comments that contains query in ```username``` or ```comment``` field (10 per page).

2. Controller
``` php
/**
 * Get a paginated list of comments.
 *
 * @return \App\Http\Resources\CommentResource
 */
public function index()
{
    // Get paginated comments
    $comments = Comment::paginate(10);

    return CommentResource::collection($comments);
}

/**
 * Search comments based on the provided query.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \App\Http\Resources\CommentResource
 */
public function search(Request $request)
{
    // Get query from request
    $query = $request->input('query');

    // Search for comments matched
    $comments = Comment::where('username', 'like', "%$query%")
                       ->orWhere('comment', 'like', "%$query%")
                       ->paginate(10);

    return CommentResource::collection($comments);
}
```

It's basically all the query using Eloquent ORM (like mySQL) and use ```paginate``` that laravel has provide.

3. Unit Test
``` php
/**
 * A basic feature test example.
 */
public function test_example(): void
{
    $response = $this->get('/');

    $response->assertStatus(200);
}

use RefreshDatabase;

/**
 * Test pagination, 10 per page
 */

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

/**
 * Test search with query
 */
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

/**
 * Test search with query random (no data in db)
 */
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
```

Using basic Unit Test, check for the API calls (status, data). Check the ```index``` function and the ```search``` function in Controllers, also check Edge Cases like if the data doesn't exist in DB.
