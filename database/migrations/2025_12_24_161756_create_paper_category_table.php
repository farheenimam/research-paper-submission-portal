<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('paper_category', function (Blueprint $table) {
        $table->id();
        $table->foreignId('paper_id')->constrained('papers')->onDelete('cascade');
        $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('paper_category');
}

};