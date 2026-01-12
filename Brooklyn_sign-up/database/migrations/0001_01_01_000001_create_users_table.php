<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('naam');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telefoon');
            $table->tinyInteger('type');
            $table->date('geboorte_datum');
            $table->string('geslacht');
            $table->string('adres');
            $table->unsignedBigInteger('auto_preference')->nullable();
            $table->foreign('auto_preference')->references('id')->on('autos')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
