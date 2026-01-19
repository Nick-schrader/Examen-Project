<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooster_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leerling_id');
            $table->unsignedBigInteger('instructeur_id');
            $table->dateTime('datum_en_tijd');
            $table->unsignedBigInteger('auto');
            $table->foreign('leerling_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('instructeur_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('auto')->references('id')->on('auto')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooster_items');
    }
};
