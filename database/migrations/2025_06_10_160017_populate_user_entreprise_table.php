<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        $users = User::whereNotNull('entreprise_id')->get();
        
        foreach ($users as $user) {
            $user->entreprises()->syncWithoutDetaching([$user->entreprise_id]);
        }
    }

    public function down(): void
    {
        DB::table('user_entreprise')->truncate();
    }
};
