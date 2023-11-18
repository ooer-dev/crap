<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReactionRequest;
use App\Http\Requests\UpdateReactionRequest;
use App\Http\Resources\ReactionResource;
use App\Models\Reaction;

class ReactionController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('ability:reaction:read')->only('index', 'show');
        $this->middleware('ability:reaction:write')->only('store', 'update', 'destroy');
    }

    /**
     * Return a listing of the resource.
     */
    public function index(int $guildId)
    {
        $reactions = Reaction::where('guild_id', $guildId)->get();

        return ReactionResource::collection($reactions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReactionRequest $request, int $guildId)
    {
        $attributes = $request->validated();
        $attributes['guild_id'] = $guildId;

        $reaction = Reaction::create($attributes);

        return new ReactionResource($reaction);
    }

    /**
     * Return the specified resource.
     */
    public function show(int $guildId, Reaction $reaction)
    {
        if ($reaction->guild_id !== $guildId) {
            abort(404);
        }

        return new ReactionResource($reaction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReactionRequest $request, int $guildId, Reaction $reaction)
    {
        if ($reaction->guild_id !== $guildId) {
            abort(404);
        }

        $reaction->update($request->validated());

        return new ReactionResource($reaction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $guildId, Reaction $reaction)
    {
        if ($reaction->guild_id !== $guildId) {
            abort(404);
        }

        $reaction->delete();

        return response()->noContent();
    }
}
