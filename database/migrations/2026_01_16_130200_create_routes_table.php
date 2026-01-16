<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('origin_address');
            $table->string('destination_address');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('days')->default('Segunda a Sexta');
            $table->decimal('distance_km', 5, 1)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
