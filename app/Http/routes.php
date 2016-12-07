<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});
// GET index()
$app->get('/pokemon', 'PokemonController@index');

// POST
$app->post('/pokemon', 'PokemonController@store');

// TODO: These are currently using an ID but should probably use the pokedex number

// Regex is expensive but effective. YOLO.
// An example named route.
$app->get('/pokemon/{id:[\d]+}', [
    'as' => 'pokemon.show',
    'uses' => 'PokemonController@show'
]);

// PUT $id
$app->put('/pokemon/{id:[\d]+}', 'PokemonController@update');

// DELETE $id
$app->delete('/pokemon/{id:[\d]+}', 'PokemonController@destroy');
