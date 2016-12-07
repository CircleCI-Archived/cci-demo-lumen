<?php

namespace App\Transformers;

use App\Pokemon;
use League\Fractal\TransformerAbstract;

class PokemonTransformer extends TransformerAbstract {

    /**
     * @param Pokemon $pokemon
     * @return array
     */
    public function transform(Pokemon $pokemon)
    {
        return [
            'id' => $pokemon->id,
            'name' => $pokemon->name,
            'number' => $pokemon->number,
            'description' => $pokemon->description,
            'created_at' => $pokemon->created_at->toIso8601String(),
            'updated_at' => $pokemon->updated_at->toIso8601String(),
        ];
    }
}
