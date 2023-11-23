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
        Schema::create('blog', function (Blueprint $table) {
            $table->increments('id');
            $table->string('blog_title')->nullable();
            $table->string('blog_slug')->nullable();
            $table->text('blog_content')->nullable();
            $table->string('blog_category')->nullable();
            $table->string('blog_tag')->nullable();
            $table->string('seo_meta_title')->nullable();
            $table->string('seo_meta_description')->nullable();
            $table->string('seo_meta_keyword')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('blog');
    }
};
