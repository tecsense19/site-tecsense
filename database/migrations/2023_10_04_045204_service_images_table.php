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
        Schema::create('service_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_id')->unsigned()->nullable();
            $table->integer('technology_id')->unsigned()->nullable();
            $table->integer('testimonial_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->string('step_title')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->foreign('service_id')->references('id')->on('our_services')->onDelete('cascade');
            $table->foreign('technology_id')->references('id')->on('technologys')->onDelete('cascade');
            $table->foreign('testimonial_id')->references('id')->on('testimonials')->onDelete('cascade');
            $table->foreign('servicedetial_id')->references('id')->on('ourservicedetails')->onDelete('cascade');
            $table->foreign('why_choose_detail_id')->references('id')->on('why_choose_details')->onDelete('cascade');
            $table->foreign('quick_look_detail_id')->references('id')->on('quick_look_details')->onDelete('cascade');
            $table->foreign('business_detail_id')->references('id')->on('business_details')->onDelete('cascade');
            $table->foreign('best_result_detail_id')->references('id')->on('best_results_details')->onDelete('cascade');
            $table->foreign('features_detail_id')->references('id')->on('features_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('service_images');
    }
};
