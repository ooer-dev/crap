<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Schemas\Schema;
use Filament\Support\Components\Attributes\Exposed;
use Filament\Support\Components\Attributes\Unexposed;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class Login extends BaseLogin
{
    #[Unexposed]
    public function mount(): void
    {
        if (request()->has('code')) {
            $this->callback();
        }

        parent::mount();
    }

    #[Exposed]
    public function authenticate(): ?LoginResponse
    {
        $resp = Socialite::driver('discord')->setScopes(['identify', 'guilds'])->redirect();
        $this->redirect($resp->getTargetUrl());

        return null;
    }

    protected function callback(): void
    {
        $discordUser = Socialite::driver('discord')->user();

        $guilds = Http::withToken($discordUser->token)
            ->get('https://discord.com/api/v10/users/@me/guilds')
            ->throw()
            ->json();

        $guild = collect($guilds)->firstWhere('id', config('services.discord.guild_id'));

        $permissions = (int) ($guild['permissions'] ?? 0);
        $isAdmin = ($permissions & 0x8) === 0x8;

        if (! $isAdmin) {
            abort(403);
        }

        $user = User::updateOrCreate([
            'discord_id' => $discordUser->id,
        ], [
            'name' => $discordUser->nickname,
        ]);

        Filament::auth()->login($user);
    }

    #[Unexposed]
    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('authenticate')
                ->label('Login with Discord')
                ->submit('authenticate'),
        ];
    }
}
