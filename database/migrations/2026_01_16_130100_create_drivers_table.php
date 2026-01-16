<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('cpf_cnpj')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('vehicle_plate')->nullable();
            $table->unsignedInteger('vehicle_capacity')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
