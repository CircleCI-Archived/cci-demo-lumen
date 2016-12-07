<?php
/**
 * Test suite for updating a Pokémon entry.
 */

use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Carbon\Carbon;

class PokemonUpdateTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::now('UTC'));
    }

    public function tearDown() {
        parent::tearDown();
        Carbon::setTestNow();
    }

    /**
     *
     */
    public function testUpdateOnlyChangesFillableFields()
    {

        $pokemon = factory('App\Pokemon')->create([
            'name' => 'Taylorchu',
            'number' => '999',
            'description' => 'Often found in tall grass. Evolves into DHH via a Ruby stone.'
        ]);

//        $this->notSeeInDatabase('pokemon', [
//            'name' => 'Taylorchu',
//            'number' => '999',
//            'description' => 'Often found in tall grass. Evolves into DHH via a Ruby stone.'
//        ]);

        $this->put("/pokemon/{$pokemon->id}", [
            'id' => 9001,
            'name' => 'Taylorchu',
            'number' => '999',
            'description' => 'Often found in tall grass. Evolves into DHH via a Ruby stone.'
        ], ['Accept' => 'application/json']);

        $this
        ->seeStatusCode(200)
        ->seeJson([
            'id' => 1,
            'name' => 'Taylorchu',
            'number' => '999',
            'description' => 'Often found in tall grass. Evolves into DHH via a Ruby stone.'
        ])
        ->seeInDatabase('pokemon', [
            'name' => 'Taylorchu'
        ]);

        $body = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('data', $body);

        $data = $body['data'];
        $this->assertArrayHasKey('created_at', $data);
        $this->assertEquals(Carbon::now()->toIso8601String(), $data['created_at']);
        $this->assertArrayHasKey('updated_at', $data);
        $this->assertEquals(Carbon::now()->toIso8601String(), $data['updated_at']);
    }


//    public function update_should_only_change_fillable_fields()
//    {
//        $book = $this->bookFactory();
//
//        $this->notSeeInDatabase('books', [
//            'title' => 'The War of the Worlds',
//            'description' => 'The book is way better than the movie.',
//        ]);
//
//        $this->put("/books/{$book->id}", [
//            'id' => 5,
//            'title' => 'The War of the Worlds',
//            'description' => 'The book is way better than the movie.'
//        ], ['Accept' => 'application/json']);
//
//        $this
//            ->seeStatusCode(200)
//            ->seeJson([
//                'id' => 1,
//                'title' => 'The War of the Worlds',
//                'description' => 'The book is way better than the movie.'
//            ])
//            ->seeInDatabase('books', [
//                'title' => 'The War of the Worlds'
//            ]);
//
//        $body = json_decode($this->response->getContent(), true);
//        $this->assertArrayHasKey('data', $body);
//
//        $data = $body['data'];
//        $this->assertArrayHasKey('created', $data);
//        $this->assertEquals(Carbon::now()->toIso8601String(), $data['created']);
//        $this->assertArrayHasKey('updated', $data);
//        $this->assertEquals(Carbon::now()->toIso8601String(), $data['updated']);
//    }



    /**
     *
     */
    public function testUpdateFailsWithInvalidID()
    {
        $this
            ->put('/pokemon/900000001')
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'error' => [
                    'message' => 'Pokémon not found. Consider adding it!'
                ] ]);
    }

    /**
     * Test that update only matches on valid routes
     */
    public function testUpdateMatchesValidRoute()
    {
        $this->put('/pokemon/pikachu')
            ->seeStatusCode(404);
    }

    public function testUpdateValidatesPokemonNameLength() {
        
        $pokemon = factory(\App\Pokemon::class)->create();

        $pokemon->name = str_repeat('Pikachu', 256);

        $this->put("/pokemon/{$pokemon->id}", [
            'name' => $pokemon->name,
            'number' => $pokemon->number,
            'description' => $pokemon->description
        ], ['Accept' => 'application/json']);

//        $this
//            ->seeStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
//            ->seeJson([
//                'name' => ["The name may not be greater than 255 characters."]
//            ])
//            ->notSeeInDatabase('pokemon', ['name' => $pokemon->name]);
        $this
            ->seeStatusCode(Response::HTTP_BAD_REQUEST)
//            ->seeJson([
//                'name' => ["The name may not be greater than 255 characters."]
//            ])
            ->notSeeInDatabase('pokemon', ['name' => $pokemon->name]);

    }








//    public function testUpdateValidatesPokemonNameWhenExactly255Characters()
//    {
//        $book = $this->bookFactory();
//        $book->title = str_repeat('a', 255);
//
//        $this->put("/books/{$book->id}", [
//            'title' => $book->title,
//            'description' => $book->description,
//            'author_id' => $book->author->id,
//        ], ['Accept' => 'application/json']);
//
//        $this
//            ->seeStatusCode(Response::HTTP_OK)
//            ->seeInDatabase('books', ['title' => $book->title]);
//    }





    
}



