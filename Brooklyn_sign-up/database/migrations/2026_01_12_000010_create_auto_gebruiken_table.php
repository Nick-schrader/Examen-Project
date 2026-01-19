<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('auto_gebruik', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auto_id');
            $table->dateTime('start_gebruik');
            $table->dateTime('eind_gebruik');
            $table->foreign('auto_id')->references('id')->on('auto')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auto_gebruiken');
    }
};
