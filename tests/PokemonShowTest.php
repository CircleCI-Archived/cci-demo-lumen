<?php
/**
 * Test suite for showing a Pokémon entry.
 */

use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Carbon\Carbon;

class PokemonShowTest extends TestCase
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
    public function testShowReturnsValidPokemon()
    {
        // $pokemon = $this->pokemonFactory();
        $pokemon = factory('App\Pokemon')->create();

        $this ->get("/pokemon/{$pokemon->id}")
            ->seeStatusCode(200);

        $content = json_decode($this->response->getContent(), true);
        $this->assertArrayHasKey('data', $content);
        $data = $content['data'];

        $this->assertEquals($pokemon->id, $data['id']);
        $this->assertEquals($pokemon->name, $data['name']);
        $this->assertEquals($pokemon->number, $data['number']);
        $this->assertEquals($pokemon->description, $data['description']);
        $this->assertEquals($pokemon->created_at->toIso8601String(), $data['created_at']);
        $this->assertEquals($pokemon->updated_at->toIso8601String(), $data['updated_at']);
    }

    /**
     *
     */
    public function testShowFailsWhenIDDoesNotExist()
    {
        // There should never be 900000001 Pokémon. If there are Nintendo is desperate.
        // Actually, what would you even call stuff at that point? Lampchu? Peanutsaur? Pencilpuff?
        $this
            ->get('/pokemon/900000001', ['Accept' => 'application/json'])
            ->seeStatusCode(404)
            ->seeJson([
                'message' => 'Not Found',
                'status'  => 404
            ]);
    }

    /**
     * 
     */
    public function testShowRouteShouldBeValid()
    {
        $this->get('/pokemon/pikachu');

        $this->assertNotRegExp(
            '/not found/',
            $this->response->getContent(),
            'PokemonController@show route is matching but shouldn\'t be!'
        );
    }
}

