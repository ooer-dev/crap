<?php

namespace App\Filament\Resources\ReactionResource\Pages;

use App\Filament\Resources\ReactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReaction extends CreateRecord
{
    protected static string $resource = ReactionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['guild_id'] = config('services.discord.guild_id');

        return $data;
    }
}
