<?php

namespace App\Models;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use BroadcastsEvents, HasFactory;

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
     * Get the channels that model events should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel|\Illuminate\Database\Eloquent\Model>
     */
    public function broadcastOn(string $event): array
    {
        return [new PrivateChannel('reactions')];
    }

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
