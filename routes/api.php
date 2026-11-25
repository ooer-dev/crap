<?php

use App\Http\Controllers\ReactionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('guild.reaction', ReactionController::class);
