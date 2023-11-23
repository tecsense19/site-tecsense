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
        Schema::create('ourservicedetails', function (Blueprint $table) {
            $table->id();
            $table->string('service_id')->nullable();
            $table->string('service_detail_title')->nullable();
            $table->string('servicedetail_pic')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ourservicedetails');
    }
};
