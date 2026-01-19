<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('auto', function (Blueprint $table) {
            $table->id();
            $table->string('kenteken');
            $table->string('merk');
            $table->tinyInteger('type');
            $table->boolean('beschikbaar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auto');
    }
};
