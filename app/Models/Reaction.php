<?php

namespace App\Models;

use Database\Factories\ReactionFactory;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Appends(['has_target'])]
#[Fillable([
    'guild_id',

    'trigger',
    'response',

    'contains_anywhere',
    'delete_trigger',
    'dm_response',
])]
class Reaction extends Model
{
    /** @use HasFactory<ReactionFactory> */
    use BroadcastsEvents, HasFactory;

    /**
     * Get the channels that model events should broadcast on.
     *
     * @return array<int, Channel|Model>
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
