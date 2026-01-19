<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rooster_item_id')->unique();
            $table->unsignedTinyInteger('rating')->check('rating between 1 and 5');
            $table->text('comment');
            $table->string('status')->index();
            $table->unsignedBigInteger('leerling_id');
            $table->timestamps();
            $table->foreign('rooster_item_id')->references('id')->on('rooster_items')->cascadeOnDelete();
            $table->foreign('leerling_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
