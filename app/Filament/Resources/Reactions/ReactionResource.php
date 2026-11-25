<?php

namespace App\Filament\Resources\Reactions;

use App\Filament\Resources\Reactions\Pages\ManageReactions;
use App\Models\Reaction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReactionResource extends Resource
{
    protected static ?string $model = Reaction::class;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('id')
                    ->label('ID')
                    ->disabled()
                    ->hiddenOn(['create'])
                    ->integer(),

                TextInput::make('trigger')
                    ->required()
                    ->columnSpanFull(),

                Textarea::make('response')
                    ->required()
                    ->columnSpanFull(),

                Toggle::make('contains_anywhere')
                    ->label('Match trigger anywhere in message')
                    ->inline(false),

                Toggle::make('dm_response')
                    ->label('Direct message response to user')
                    ->inline(false),

                Toggle::make('delete_trigger')
                    ->label('Delete trigger message')
                    ->inline(false),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->grow(false),

                TextColumn::make('trigger')
                    ->searchable()
                    ->sortable(),

                CheckboxColumn::make('contains_anywhere')
                    ->label('Contains')
                    ->sortable(),

                CheckboxColumn::make('dm_response')
                    ->label('DM')
                    ->sortable(),

                CheckboxColumn::make('delete_trigger')
                    ->label('Delete')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageReactions::route('/'),
        ];
    }
}
