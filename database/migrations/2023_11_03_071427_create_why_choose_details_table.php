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
        Schema::create('why_choose_details', function (Blueprint $table) {
            $table->id();
            $table->string('why_choose_id')->nullable();
            $table->string('why_choose_detail_title')->nullable();
            $table->text('why_choose_detail_description')->nullable();
            $table->string('why_choose_detail_pic')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('why_choose_details');
    }
};
