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
        Schema::create('saved_papers', function (Blueprint $table) {
            $table->id();
    
            $table->unsignedBigInteger('user_id');   // reader
            $table->unsignedBigInteger('paper_id');  // paper
    
            $table->timestamps();
    
            $table->unique(['user_id', 'paper_id']); // save once
    
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();
    
            $table->foreign('paper_id')
                  ->references('id')
                  ->on('papers')
                  ->cascadeOnDelete();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('saved_papers');
    }
    
};
