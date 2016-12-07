<?php

use App\Pokemon;

use App\Transformers\PokemonTransformer;
use League\Fractal\TransformerAbstract;
use Laravel\Lumen\Testing\DatabaseMigrations;

class PokemonTransformerTest extends TestCase
{
    use DatabaseMigrations;

    public function testCanBeInitialized()
    {
        $subject = new PokemonTransformer();
        $this->assertInstanceOf(TransformerAbstract::class, $subject);
    }

    public function testTransformsPokemonModel()
    {
        $pokemon = factory(Pokemon::class)->create();
        $subject = new PokemonTransformer();
        $transform = $subject->transform($pokemon);
        $this->assertArrayHasKey('id', $transform);
        $this->assertArrayHasKey('name', $transform);
        $this->assertArrayHasKey('number', $transform);
        $this->assertArrayHasKey('description', $transform);
        $this->assertArrayHasKey('created_at', $transform);
        $this->assertArrayHasKey('updated_at', $transform);
    }
}
