<?php
/**
 * Test suite for listing our Pokémon collection/endpoint testing.
 */

use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Carbon\Carbon;

class PokemonIndexTest extends TestCase
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
     * Test that our endpoint returns 200 OK
     */
    public function testIndexReturns200OK()
    {
        $this->get('/pokemon')->seeStatusCode(200);
    }

    /**
     * Test that our endpoint returns names of Pokemon
     *
     * NOTE: These names are created via Faker and won't be real.
     */
    public function testIndexReturnsCollectionOfPokemon()
    {
        $monsters = factory('App\Pokemon', 2)->create();

        $this->get('/pokemon');

        $content = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('data', $content);

        foreach ($monsters as $pokemon) {
            $this->seeJson([
                'id' => $pokemon->id,
                'name' => $pokemon->name,
                'number' => $pokemon->number,
                'description' => $pokemon->description,
                'created_at' => $pokemon->created_at->toIso8601String(),
                'updated_at' => $pokemon->updated_at->toIso8601String(),
            ]);
        }
    }

}
