<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nsap_schemes', function (Blueprint $table) {
            $table->id();
            $table->string('scheme_code');
            $table->string('scheme_name');
            $table->string('central_state_scheme');
            $table->string('fin_year');
            $table->decimal('state_disbursement', 15, 2);
            $table->decimal('central_disbursement', 15, 2);
            $table->decimal('total_disbursement', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nsap_schemes');
    }
};
