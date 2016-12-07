<?php

namespace App\Http\Controllers;

use App\Pokemon;
use Illuminate\Http\Request;
use App\Transformers\PokemonTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PokemonController extends Controller {

    // TODO: constructor
    /**
     * GET /pokemon
     *
     * @return array
     */
    public function index()
    {
        return $this->collection(Pokemon::all(), new PokemonTransformer());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'number' => 'required',
            'description' => 'required'
        ],[
            'description.required' => 'Please fill out the :attribute.'
        ]);

        $pokemon = Pokemon::create($request->all());

        $data = $this->item($pokemon, new PokemonTransformer());

        return response()->json($data, 201, [
            'Location' => route('pokemon.show', ['id' => $pokemon->id])
        ]);
    }

    public function show($id)
    {
        return $this->item(Pokemon::findOrFail($id), new PokemonTransformer());

    }

    public function update(Request $request, $id)
    {
//        $this->validate($request, [
//            'title' => 'required|max:255',
//            'description' => 'required',
//            'author_id' => 'exists:authors,id'
//        ], [
//            'description.required' => 'Please fill out the :attribute.'
//        ]);

        try {
            $pokemon = Pokemon::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
            'error' => [
                'message' => 'Pokémon not found. Consider adding it!'
            ] ], 404);
        }

        $this->validate($request, [
            'name' => 'required|max:255',
            'number' => 'required',
            'description' => 'required'
        ],[
            'description.required' => 'Please fill out the :attribute.'
        ]);

        $pokemon->fill($request->all());
        $pokemon->save();

        return $this->item($pokemon, new PokemonTransformer());

    }

    public function destroy($id)
    {
        try {
            $pokemon = Pokemon::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
            'error' => [
                'message' => 'Pokémon not found. Consider adding it!'
            ] ], 404);
        }
        $pokemon->delete();

        return response(null, 204);
    }
}

