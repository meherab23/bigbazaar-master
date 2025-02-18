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
        if(!Schema::hasTable('categories')){
            Schema::create('categories', function (Blueprint $table) {
                $table->increments('category_row_id');
                $table->string('category_name');
                $table->string('category_slug');
                $table->string('category_image');
                $table->string('category_description');
                $table->integer('parent_id');
                $table->integer('has_child');
                $table->integer('is_featured');
                $table->integer('level');
                $table->integer('count_product');
                $table->integer('category_sort_order');
                $table->string('meta_key');
                $table->string('meta_description');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
