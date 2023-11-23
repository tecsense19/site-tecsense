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
        Schema::create('best_results_details', function (Blueprint $table) {
            $table->id();
            $table->string('service_id')->nullable();
            $table->string('text')->nullable();
            $table->string('best_result_detail_pic')->nullable();
            $table->text('step_title')->nullable();
            $table->string('main_title')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('best_results_details');
    }
};
