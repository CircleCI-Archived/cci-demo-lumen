<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model {

    /**
     * If we don't set this manually Lumen tries to query `pokemons` ;)
     *
     * @var string
     */
    protected $table = 'pokemon';

    /**
     * What things are mass assignable?
     *
     * @var array
     */
    protected $fillable = ['name', 'number', 'description'];
}
