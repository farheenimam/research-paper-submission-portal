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
    Schema::create('comments', function (Blueprint $table) {
        $table->id(); // primary key

        $table->unsignedBigInteger('paper_id');
        $table->unsignedBigInteger('user_id'); // reviewer

        $table->text('comment');
        $table->timestamps();

        $table->foreign('paper_id')
              ->references('id')
              ->on('papers')
              ->cascadeOnDelete();

        $table->foreign('user_id')
              ->references('id')
              ->on('users')
              ->cascadeOnDelete();
    });
}

public function down()
{
    Schema::dropIfExists('comments');
}

};
