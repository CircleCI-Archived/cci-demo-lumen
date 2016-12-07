<?php
/**
 * Test suite for storing a Pokémon entry.
 */

use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Carbon\Carbon;

class PokemonStoreTest extends TestCase
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
     * Test that destroy() removes a valid Pokémon.
     */
    public function testDestroyRemovesValidPokemon()
    {
        $pokemon = factory('App\Pokemon')->create();

        $this
            ->delete("/pokemon/{$pokemon->id}")
            ->seeStatusCode(204)
            ->isEmpty();
        $this->notSeeInDatabase('pokemon', ['id' => $pokemon->id]);

    }

    /**
     * Test that we get a 404 if the Pokémon `id` is invalid.
     */
    public function testDestroyReturns404OnInvalidID()
    {
        $this
            ->delete('/pokemon/900000001')
            ->seeStatusCode(404)
            ->seeJsonEquals([
                'error' => [
                    'message' => 'Pokémon not found. Consider adding it!'
                ] ]);
    }

    /**
     * Test that destroy() does not work on invalid routes.
     */
    public function testDestroyDoesNotMatchInvalidRoute()
    {
        $this->delete('/pokemon/pikachu')
            ->seeStatusCode(404);
    }

}
