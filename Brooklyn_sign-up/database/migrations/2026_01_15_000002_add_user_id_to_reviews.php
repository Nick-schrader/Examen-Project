<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add user_id column if it doesn't exist
        if (!Schema::hasColumn('reviews', 'user_id')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('rooster_item_id');
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            });
        }
        
        // Create unique index on rooster_item_id and user_id
        DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS reviews_rooster_user_unique ON reviews(rooster_item_id, user_id)');
    }

    public function down(): void
    {
        if (Schema::hasColumn('reviews', 'user_id')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};
