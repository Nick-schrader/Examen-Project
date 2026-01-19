<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('strippenkaart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leerling_id')->constrained('users')->cascadeOnDelete();
            $table->integer('tegoed')->default(0);
            $table->date('verval_datum')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strippenkaart');
    }
};

