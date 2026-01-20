<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('verslag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rooster_item_id');
            $table->text('verslag');
            $table->date('datum_gemaakt');
            $table->date('datum_aangepast');
            $table->foreign('rooster_item_id')->references('id')->on('rooster_items')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verslag');
    }
};
