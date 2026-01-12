<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('verslagen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rooster_item_id');
            $table->text('verslag');
            // Domain-level creation date of the verslag (e.g. as entered by the user),
            // distinct from the Eloquent-managed created_at timestamp.
            $table->date('datum_gemaakt');
            // Domain-level last modification date of the verslag,
            // distinct from the Eloquent-managed updated_at timestamp.
            $table->date('datum_aangepast');
            $table->foreign('rooster_item_id')->references('id')->on('rooster_items')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verslagen');
    }
};
