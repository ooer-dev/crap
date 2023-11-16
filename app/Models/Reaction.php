<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'guild_id',

        'trigger',
        'response',

        'contains_anywhere',
        'delete_trigger',
        'dm_response',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'has_target',
    ];

    /**
     * Determine if the reaction has a target in the response.
     */
    protected function hasTarget(): Attribute
    {
        return new Attribute(
            get: fn (mixed $value, array $attributes) => str_contains($attributes['response'], '%target%'),
        );
    }
}
