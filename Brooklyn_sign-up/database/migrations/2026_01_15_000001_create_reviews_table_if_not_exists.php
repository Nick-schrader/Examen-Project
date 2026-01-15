<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Create reviews table if it doesn't exist
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('rooster_item_id')->unique();
                $table->unsignedTinyInteger('rating')->check('rating between 1 and 5');
                $table->text('comment');
                $table->tinyInteger('status')->index();
                $table->foreign('rooster_item_id')->references('id')->on('rooster_items')->cascadeOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
