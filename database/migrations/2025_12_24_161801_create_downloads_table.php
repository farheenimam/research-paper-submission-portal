<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('paper_id')->constrained('papers')->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->timestamp('created_at')->useCurrent();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('downloads');
    }
    
};