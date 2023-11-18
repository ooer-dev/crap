<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreatePersonalAccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanctum:token {user} {name} {ability?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new personal access token';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->argument('name');
        $abilities = $this->argument('ability');

        $user = User::findOrFail($this->argument('user'));

        $token = $user->createToken($name, $abilities);

        $this->info('Token created: '.$token->plainTextToken);
    }
}
