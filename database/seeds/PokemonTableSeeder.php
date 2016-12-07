<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PokemonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pokemon')->insert([
            'name' => 'Bulbasaur',
            'number' => '1',
            'description' => 'A strange seed was planted on its back at birth. The plant sprouts and grows with this Pokémon.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('pokemon')->insert([
            'name' => 'Charizard',
            'number' => '6',
            'description' => 'Spits fire that is hot enough to melt boulders. Known to cause forest fires unintentionally.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}