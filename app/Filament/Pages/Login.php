<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class Login extends BaseLogin
{
    public function mount(): void
    {
        if (request()->has('code')) {
            $this->callback();
        }

        parent::mount();
    }

    public function authenticate(): ?LoginResponse
    {
        $resp = Socialite::driver('discord')->scopes(['guilds'])->redirect();
        $this->redirect($resp->getTargetUrl());

        return null;
    }

    public function callback(): void
    {
        $discordUser = Socialite::driver('discord')->user();

        $guilds = Http::withToken($discordUser->token)
            ->get('https://discord.com/api/v10/users/@me/guilds')
            ->json();

        $guildId = config('services.discord.guild_id');
        $guild = collect($guilds)->firstWhere('id', $guildId);

        $permissions = (int) $guild['permissions'];
        $isAdmin = ($permissions & 0x8) === 0x8;

        if (! $isAdmin) {
            abort(403);
        }

        $user = User::updateOrCreate([
            'discord_id' => $discordUser->id,
        ], [
            'name' => $discordUser->nickname,
            'email' => $discordUser->email,
            'password' => Str::password(),
        ]);

        Filament::auth()->login($user);

        redirect()->intended(Filament::getUrl());
    }

    public function form(Form $form): Form
    {
        return $form->schema([]);
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
