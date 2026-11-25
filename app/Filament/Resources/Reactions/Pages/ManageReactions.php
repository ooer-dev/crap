<?php

namespace App\Filament\Resources\Reactions\Pages;

use App\Filament\Resources\Reactions\ReactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageReactions extends ManageRecords
{
    protected static string $resource = ReactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateDataUsing(function (array $data): array {
                    $data['guild_id'] = config('services.discord.guild_id');

                    return $data;
                }),
        ];
    }
}
