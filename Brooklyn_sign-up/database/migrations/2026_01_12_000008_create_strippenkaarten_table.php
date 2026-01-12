<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('strippenkaarten', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('leerling_id');
            $table->integer('tegoed');
            $table->date('verval_datum');
            $table->foreign('leerling_id')->references('id')->on('accounts')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strippenkaarten');
    }
};
