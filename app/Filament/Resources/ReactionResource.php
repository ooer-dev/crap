<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReactionResource\Pages;
use App\Models\Reaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReactionResource extends Resource
{
    protected static ?string $model = Reaction::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->label('ID')
                    ->disabled()
                    ->hiddenOn(['create'])
                    ->integer(),

                Forms\Components\TextInput::make('trigger')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('response')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Toggle::make('contains_anywhere')
                    ->label('Match trigger anywhere in message')
                    ->inline(false),

                Forms\Components\Toggle::make('dm_response')
                    ->label('Direct message response to user')
                    ->inline(false),

                Forms\Components\Toggle::make('delete_trigger')
                    ->label('Delete trigger message')
                    ->inline(false),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->grow(false),

                Tables\Columns\TextColumn::make('trigger')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\CheckboxColumn::make('contains_anywhere')
                    ->label('Contains')
                    ->sortable(),

                Tables\Columns\CheckboxColumn::make('dm_response')
                    ->label('DM')
                    ->sortable(),

                Tables\Columns\CheckboxColumn::make('delete_trigger')
                    ->label('Delete')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReactions::route('/'),
            'create' => Pages\CreateReaction::route('/create'),
            'edit' => Pages\EditReaction::route('/{record}/edit'),
        ];
    }
}
