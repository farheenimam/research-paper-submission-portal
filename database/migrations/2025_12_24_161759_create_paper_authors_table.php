<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('paper_authors', function (Blueprint $table) {
        $table->id();
        $table->foreignId('paper_id')->constrained('papers')->onDelete('cascade');
        $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        $table->string('author_name', 150);
        $table->string('author_email', 150)->nullable();
        $table->string('affiliation')->nullable();
    });
}

public function down()
{
    Schema::dropIfExists('paper_authors');
}

};