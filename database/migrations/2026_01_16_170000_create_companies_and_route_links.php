<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address_line')->nullable();
            $table->string('address_number', 20)->nullable();
            $table->string('city')->nullable();
            $table->string('state', 2)->nullable();
            $table->string('zip_code')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lon', 10, 7)->nullable();
            $table->timestamps();
        });

        Schema::table('routes', function (Blueprint $table) {
            $table->unsignedBigInteger('driver_id')->nullable()->after('distance_km');
            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');
        });

        Schema::create('route_client', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('route_id');
            $table->unsignedBigInteger('client_id');
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->unique(['route_id','client_id']);
        });

        Schema::create('route_company', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('route_id');
            $table->unsignedBigInteger('company_id');
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->unique(['route_id','company_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('route_company');
        Schema::dropIfExists('route_client');
        Schema::table('routes', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn('driver_id');
        });
        Schema::dropIfExists('companies');
    }
};
