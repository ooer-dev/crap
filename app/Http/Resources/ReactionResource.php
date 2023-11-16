<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'guild_id' => $this->guild_id,

            'trigger' => $this->trigger,
            'response' => $this->response,

            'contains_anywhere' => $this->contains_anywhere,
            'delete_trigger' => $this->delete_trigger,
            'dm_response' => $this->dm_response,
            'has_target' => $this->has_target,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
