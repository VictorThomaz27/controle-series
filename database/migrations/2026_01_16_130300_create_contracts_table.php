<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['client','driver']);
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->nullOnDelete();
            $table->foreignId('route_id')->nullable()->constrained('routes')->nullOnDelete();
            $table->decimal('monthly_value', 10, 2)->nullable();
            $table->unsignedInteger('tolerance_minutes')->default(10);
            $table->boolean('vacation_policy')->default(true);
            $table->boolean('anticorruption_acceptance')->default(true);
            $table->string('payer_name')->nullable();
            $table->string('payer_cnpj')->nullable();
            $table->string('payer_address')->nullable();
            $table->date('start_date')->nullable();
            $table->string('status')->default('active');
            $table->text('content')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
